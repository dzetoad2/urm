<?php
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/activityCategoriesFunctions.php';
require_once 'urm_secure/breadCrumbFunctions.php';
require_once 'urm_secure/sessionStateFunctions.php';
require_once 'urm_secure/activitiesSupplementalFunctions.php';

if(!loggedin()){
	//echo "userarea but not loggedin!<br/>\n";
	header("Location: login.php");
	exit();
}
if(!isset($_SESSION['username']) || !isset($_SESSION['userid'])){
	$errorMsg= "error: un or userid not set";
	throwMyExc($errorMsg);
}
//scrub any activitycategoryid or activitycategorydocid from session.
unset($_SESSION['activityCategoryId']);
unset($_SESSION['activityCategoryDocId']);
unset($_SESSION['activityId']);
unset($_SESSION['customActivityId']);



//--------- if userfacid or customfacid is not set, error!! ---
if(!isset($_SESSION['userFacilityId']) && !isset($_SESSION['customFacilityId'])){
	$errorMsg = "error: neither userfacilityid or customfacilityid are set! one 
	      of these must be sent over from surveyCategories to here.";
	throwMyExc($errorMsg);
}
if(!isset($_POST['surveyCategoryId']) && !isset($_SESSION['surveyCategoryId'])){
	$errorMsg = "error: surveycategoryid not set - it is required.";
	throwMyExc($errorMsg);
}

$un = $_SESSION['username'];
$userId = $_SESSION['userid'];
$statusLabel='';
$errorLabel='';
if(isset($_SESSION['updateCustomActivitySuccess']) && $_SESSION['updateCustomActivitySuccess']=='true'){
	$statusLabel.='Success updating user created activity';
	unset($_SESSION['updateCustomActivitySuccess']);
}
if(isset($_SESSION['createActivitySuccess']) && $_SESSION['createActivitySuccess']=='true'){
	$statusLabel.='Success creating user created activity';
	unset($_SESSION['createActivitySuccess']);
}

if(isset($_SESSION['userFacilityId'])){
	$userFacilityId = $_SESSION['userFacilityId'];
	unset($_SESSION['customFacilityId']);
}
else if(isset($_SESSION['customFacilityId'])){
	$customFacilityId = $_SESSION['customFacilityId'];
	unset($_SESSION['userFacilityId']);
}else{
	$errorMsg = "Error: Neither userfacilityid nor customfacilityid were set in session.";
	throwMyExc($errorMsg);
}

if(isset($_POST['surveyCategoryId'])){
	$surveyCategoryId = $_POST['surveyCategoryId'];
	$_SESSION['surveyCategoryId'] = $surveyCategoryId;
}else if(isset($_SESSION['surveyCategoryId'])){
	$surveyCategoryId = $_SESSION['surveyCategoryId'];
}else{
	$errorMsg = "Error: surveycategoryid is not set. it must be set to use this page correctly.";
	throwMyExc($errorMsg);
}


savePostAndSessionVars($userId,$_POST,$_SESSION,"activityCategories.php");






$surveyCategoryName = getSurveyCategoryName($surveyCategoryId);
$surveyCategoryDoc = getSurveyCategoryDoc($surveyCategoryId);







if(isset($_POST['dropAction2'])){
	//===========THIS DROPS A CUSTOM ACTIVITY================
	$customActivityId = $_POST['customActivityId'];
   //now do the customactiv drop.
     $res = deleteCustomActivity($userId, $customActivityId);
     if($res['hasError']===false){
     	$statusLabel = $res['msg'];
     }else if($res['hasError']===true){
     	$errorLabel = $res['msg'];
     }else{
     	$em = 'activitycategories:  del custom (user created) activity result does not make sense';
     	throwMyExc($em);
     }
     
      
}


//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------


if(isset($userFacilityId)){
 	$fid = $userFacilityId;
 	$is_cf = 0;
 }
 else if(isset($customFacilityId)){
    $fid = $customFacilityId;
    $is_cf = 1;
 }
 else{
    $em = 'impossible: error here.';
    throwMyExc($em);
 }

    $activityCategoriesRows = getActivityCategoriesRowsHtml($userId, $fid, $is_cf,  $surveyCategoryId);
    if($activityCategoriesRows === false){
	$errorMsg = "Error in getmyfacilitiesrowshtml(un)";
	throwMyExc($errorMsg);
}
$activityCategoriesAreComplete = isSurveyCategoryComplete($userId, $fid,$is_cf,$surveyCategoryId);   // in activitiessupplementalfunctions.php  
  // note:  its 'are all the categories complete?' for this particular survey category. 
$hasCustomActivities = hasCustomActivities($userId,$surveyCategoryId); 


$is_ca = 1; //this page only shows customActivities not activities.
if(!$customActivityRows = getCustomActivityRowsHtml($userId, $fid,$is_cf,1, $surveyCategoryId)){
	$errorMsg = "Error in getCustomActivityRowshtml(un)";
	throwMyExc($errorMsg);
}











?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Activity Categories</title>
<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/textcontent.css" />

<link rel="stylesheet" href="css/jqueryui/1.7.1/themes/blitzer/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="js/jquery.alerts-1.1/jquery.alerts.css" type="text/css" />







<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>-->
<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>

<!--<script src="js/jquery.easy-confirm-dialog.js"></script>-->

<script src="js/jquery.alerts-1.1/jquery.alerts.js"></script>



<script type="text/javascript" src="js/tables_noAlternatingColor.js"></script>



<script type="text/javascript">                                         
$(document).ready(function() {
	
	   $("a#clearFacilitiesLink").click(function(e) {
		   	 e.preventDefault();
//       		 alert("Clearing all of your user's facility entries");
		     document.forms["clearFacilitiesForm"].submit();
	   });
	   $("a#clearCustomFacilitiesLink").click(function(e) {
		   	 e.preventDefault();
     		 alert("Clearing all of your user created facility entries");
		     document.forms["clearCustomFacilitiesForm"].submit();
	   });
	   $(".activityCategoryRow").click(function() {
		     var rowId = $(this).closest(".activityCategoryRow").attr("id"); //children(".cell1:first")
		     var title = $(this).closest(".activityCategoryRow").children(".nameCell:first").attr("id"); //children(".nameCell:first")
//		     alert("Activity: "+title +" was chosen.");
		     //now add to the form 'chooseAction' and submit it.
		     $("#chooseAction").children("input#activityCategoryId").val(rowId);
		     document.forms["chooseAction"].submit();
		   });
	   $(".activityCategoryDocRow").click(function() {
		     var rowId = $(this).closest(".activityCategoryDocRow").attr("id"); //children(".cell1:first")
		     //var title = $(this).closest(".activityCategoryDocRow").children(".nameCell:first").attr("id"); //children(".nameCell:first")
//		     alert("Activity: "+title +" was chosen.");
		     //now add to the form 'chooseAction' and submit it.
		     $("#chooseAction2").children("input#activityCategoryDocId").val(rowId);
		     document.forms["chooseAction2"].submit();
		   });
	   
	   $(".customActivityRow .nameCell").click(function() {
		     var rowId = $(this).closest(".customActivityRow").attr("id");
		     //var title = $(this).closest(".customActivityRow").children(".nameCell:first").attr("id");
//		     alert("User Created Activity: "+title +" was chosen.");
		     //now add to the form 'chooseAction' and submit it.
		     $("#viewAction2").children("input#customActivityId").val(rowId);
		     
		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );
		     document.forms["viewAction2"].submit();
		   });
	   $(".customActivityRow .edit").click(function() {
		     var rowId = $(this).closest(".customActivityRow").attr("id");
		     $("#editAction2").children("input#customActivityId").val(rowId);
		     document.forms["editAction2"].submit();
			 
	   });
	   $(".customActivityRow .drop").click(function() {
		   var answer = confirm("Delete this User Created Activity?")
		   if (answer){
		     var rowId = $(this).closest(".customActivityRow").attr("id");
		     $("#dropAction2").children("input#customActivityId").val(rowId);
		     document.forms["dropAction2"].submit();
		   }
	   });  	

	   $("#createCustomActivity").click(function(e) {
		   	 
		     document.forms["chooseAction4"].submit();
		     e.preventDefault();
		   });






//	   $("#doneWithSurveyCategoryButton").easyconfirm({locale: { title: 'Confirm', button: ['No','Yes']}});

	   
	   $("#doneWithSurveyCategoryButton").click(function(e) {
			 //javascript popup, yes or no.
//		     var answer = confirm("Are there other surveys you wish to complete for this facility?")
//		     if (answer){
////		       $("#dropAction").children("input#surveyCategoryId").val(rowId);
////		       document.forms["dropAction"].submit();
//			   //go to the finish page
//		    	 window.location = 'surveyFinished.php';
//		     }	
//		     $dialog.dialog('open');
				// prevent the default action, e.g., following a link
				
//	    $("#yesno").click(function() {
//			alert("You clicked yes");
//		});
			
			
			jConfirm('Are there other surveys you wish to complete for this facility?', 'Confirmation Dialog', function(r) {
				if(r == true){
					window.location = 'surveyCategories.php';
    				//jAlert('Confirmed true: ' + r, 'Confirmation Results');
				}
				else{
					window.location = 'surveyFinished.php';
	    			//jAlert('Confirmed false: ' + r, 'Confirmation Results');
				}
				
			});
			
				
//			alert("You clicked yes");





			//click yes:   go up to surveyCategories.
			//click no:  log out.	

			
			
			e.preventDefault();
			  
			 
	   });


		
	   //document.ready:
//	   var $dialog = $('<div class="dialog"></div>')
//		.html('This dialog will show every time!')
//		.dialog({
//			autoOpen: false,
//			title: 'Basic Dialog'
//		});

	    






	   
});
</script>

</head>
<body>
<div class="header"> 
<?php  echo "You are logged in as: ". $_SESSION['username'].'<br/>';?>
<a href="logout.php">Log out</a> | 
<a href="surveyCategories.php">Back to Survey Categories</a>
</div>
<hr/>
<?php if(isset($statusLabel) && $statusLabel!=''){?>
  <h4 class="statusLabel"><?php echo $statusLabel;?></h4>
<?php }?>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
  <h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>
<?php if($activityCategoriesAreComplete===true){?>
<h4 class="notificationBanner" >All activities for this survey are complete. Please complete any custom procedures (User Created Activities, at bottom of the page) before continuing
with other surveys. </h4>
<?php }?>

<h3> <?php echo $surveyCategoryName;?>:</h3>


<div class="content" readonly="readonly">
<?php echo nl2br($surveyCategoryDoc);  ?>
</div>




<h3>Activity Categories for <?php echo $surveyCategoryName;?>:</h3>
<p>Click on an activity category to see its activities.</p>
<div>
<table>
<col class="1">
<col class="2"/>
	<thead>
	   <?php if(defined('DEBUG')){?>
		<th>Id</th>
		<?php }?>
		<th>Id Num</th>
		<th>Title</th>
		<th>Completion<br/>Status</th>
	</thead>
	<tbody>
	<?php
	echo $activityCategoriesRows;  // identified by "customFacilityRow" in a cell.
	?>
	</tbody>
</table>
</div>




<?php 
//if and only if the activitycategories have been 'looked at', here this means click skip or answer, can the user start creating and answering
// custom activities.    so this block is hidden unless that is true.
if($activityCategoriesAreComplete===true   ||  defined('debug')  || defined('debugActivityCategories') ){
?>
 <h4 class="notificationBanner" >All activities for this survey are complete. Please complete any custom procedures (User Created Activities, below) before continuing
with other surveys. </h4>

<h3>User Created Activities:</h3>

<div>


<div> 
  <a id="createCustomActivity" name="createCustomActivity" href="createActivity.php">Create a User Created Activity</a><br/>
</div>


<table>
<col class="1">
<col class="2"/>
	<thead>
		<tr>
		<th>Edit</th>
		<th>Drop</th>
	   <?php if(defined('DEBUG')){?>
		<th>Id</th>
	   <?php }?>
		<th>Title</th>
		<th>Completion<br/>Status</th>
		</tr>
	</thead>
	<tbody>
	<?php
	echo $customActivityRows;  // identified by "customFacilityRow" in a cell.
	?>
	</tbody>
</table>

<p>
<b>Changing a Created Activity</b><br/>
 You may edit or drop custom activities after you have created them. <br/>
 To edit the name or description, click on the edit pen.   <br/>
 To edit survey answer data, click on the activity title. <br/>
 You may drop the activity by clicking the red X.
</p>



</div>




<?php }?>
<p>

If there are any activities performed in your facility that you believe are in this category but were not identified in the survey, 
report them in the "User Created Activities" section that follows.  You may add them by clicking on "Create a User Created Activity". 
You will be asked to provide a name, a brief 1-2 sentence description and a few critical elements to further clarify the activity. 
This activity will be added to the list of activities for this section as a User Created Activity. Then select it from the list of User 
Created Activities and provide the same information as you did for the other activities in this survey. When your User Created Activity
has been successfully submitted, a green check will appear on the completion status column. You may add as many user created procedures 
as you like.

</p>





 

<?php if($activityCategoriesAreComplete===true){?>
 <p class="buttonParagraph"> 
   <a href="#" class="doneSurvCatbutton_link" id="doneWithSurveyCategoryButton" >Done with this Survey Category (including its Custom Procedures)</a>
 </p>
<?php }?>
 
 
 
 
 

<hr/>
<p>
  Go back to <a href="surveyCategories.php">Survey Categories</a>.
</p>


<form id="chooseAction" name="chooseAction"
	action="activities.php" method="post">
	<input type="hidden"
	id="activityCategoryId" name="activityCategoryId" value="nothing" />
</form>
<form id="chooseAction2" name="chooseAction2"
	action="activities.php" method="post">
	<input type="hidden"
	id="activityCategoryDocId" name="activityCategoryDocId" value="nothing" />
</form>


<form id="viewAction2" name="viewAction2"
	action="activity.php" method="post">
	<input type="hidden"
	id="customActivityId" name="customActivityId" value="nothing" />
</form>
<form id="editAction2" name="editAction2"
	action="editActivity.php" method="post">
	<input type="hidden"
	id="customActivityId" name="customActivityId" value="nothing" />
</form>
<form id="dropAction2" name="dropAction2"
	action="activityCategories.php" method="post">
	<input type="hidden"
	id="customActivityId" name="customActivityId" value="nothing" />
	<input type="hidden"
	id="dropA2" name="dropAction2" value="" />
</form>


<form id="chooseAction4" name="chooseAction4"
	action="createActivity.php" method="post">
	<input type="hidden" id="activityType" name="activityType" value="customActivity" />
	<input type="hidden" id="fid" name="fid" value="<?php echo $fid;?>" />
	<input type="hidden" id="is_cf" name="is_cf" value="<?php echo $is_cf;?>" />
	<input type="hidden" id="is_ca" name="is_ca" value="1" />
	
</form>



<?php  
if(defined('DEBUG')){
 echo '<hr/>';
 if(isset($userFacilityId)) echo " userFacilityId (Session):  ". $userFacilityId.'<br/>';
 if(isset($customFacilityId)) echo " customFacilityId (Session):  ". $customFacilityId.'<br/>';
 if(isset($surveyCategoryId)) echo " surveyCategoryId (posted and now put in session):  ". $surveyCategoryId.'<br/>';
}
?>
</body>
</html>
<?php 
}catch(Exception $e){
 goErrorPage($e);
}?>