<?php
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/surveyCategoriesFunctions.php';
require_once 'urm_secure/breadCrumbFunctions.php';
require_once 'urm_secure/sessionStateFunctions.php';


if(!loggedin()){
	//echo "userarea but not loggedin!<br/>\n";
	header("Location: login.php");
	exit();
}
if(!isset($_SESSION['username']) || !isset($_SESSION['userid'])){
	$errorMsg="error: un or userid not set";
	$_SESSION['errorMsg'] = $errorMsg;
	header('Location: errorPage.php');
  	exit();
}
//--------- if userfacid or customfacid is not set, error!! ---
if(!isset($_POST['userFacilityId']) && !isset($_POST['customFacilityId']) && !isset($_SESSION['userFacilityId']) && 
    !isset($_SESSION['customFacilityId'])){
	$errorMsg="error: neither userfacilityid or customfacilityid are set! one 
	      of these must be *posted* (not session!!) by surveyHome and sent to here.";
}
$un = $_SESSION['username'];
$userId = $_SESSION['userid'];

 savePostAndSessionVars($userId,$_POST,$_SESSION,"surveyCategories.php");

$statusLabel = '';
$errorLabel = '';
//====this is to delete a set of survey answers for 1 survey category, by 1 user, for the given facility (or customFacility).
if(isset($_POST['dropAction'])){
 
 //  ('delete action here: userid: '.$userId.", fid: ".$_POST['fid'].", is_cf: ".$_POST['is_cf'].', surveycatid: '.$_POST['surveyCategoryId']);
  $fid = $_POST['fid'];
  $is_cf = $_POST['is_cf']; // is custom facility 0 or 1
  $surveyCategoryId = $_POST['surveyCategoryId'];
  // ('about to call dropysruveyanswers: userid:'.$userId.",fid:".$fid.",iscf:".$is_cf.",surveycatid:".$surveyCategoryId);
  $count = dropSurveyAnswers($userId,$fid,$is_cf,$surveyCategoryId);
  if($count > 0)
   $statusLabel = 'Successfully deleted '.$count. ' survey answer(s)';
  else if ($count === 0)
   $errorLabel = 'No records found to delete';
 
} //==== end delete action

if(isset($_POST['userFacilityId'])){
	$userFacilityId = $_POST['userFacilityId'];
	$is_cf=0;
	$fid = $userFacilityId;
	$_SESSION['userFacilityId'] = $userFacilityId;
	$facilityName = getFacilityName($userFacilityId);
}else if(isset($_SESSION['userFacilityId'])){
	$userFacilityId = $_SESSION['userFacilityId'];
	$is_cf=0;
	$fid = $userFacilityId;
	$facilityName = getFacilityName($userFacilityId);
}else if(isset($_POST['customFacilityId'])){
	$customFacilityId = $_POST['customFacilityId'];
	$is_cf=1;
	$fid = $customFacilityId;
	$_SESSION['customFacilityId'] = $customFacilityId;
	$facilityName = getCustomFacilityName($customFacilityId);
}else if(isset($_SESSION['customFacilityId'])){
	$customFacilityId = $_SESSION['customFacilityId'];
	$is_cf=1;
	$fid = $customFacilityId;
	$facilityName = getCustomFacilityName($customFacilityId);
}else{
  $errorMsg='error: none are set:  userfacilityid post or session, customfacilityid post or session.';
  $_SESSION['errorMsg'] = $errorMsg;
  header('Location: errorPage.php');
  exit();
} 
if(!isset($facilityName)){ 
	//facilityname is not defined - a big error! so redirect to surveyHome.php.
	//header("Location: surveyHome.php");
	$errorMsg='surveycategories.php error: facilityname not set. line 69';
	$_SESSION['errorMsg'] = $errorMsg;
	header('Location: errorPage.php');
	exit();
}
if(!isset($is_cf)){
	$errorMsg='is_cf not set! impossible to continue.';
	$_SESSION['errorMsg'] = $errorMsg;
	header('Location: errorPage.php');
  	exit();
}

//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------
  $surveyCategoriesRows = getSurveyCategoriesRowsHtml($userId,$fid,$is_cf);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Survey Categories</title>
<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/tables.js"></script>

<script type="text/javascript">                                         
$(document).ready(function() {
	
	  /* $("a#clearFacilitiesLink").click(function(e) {
		   	 e.preventDefault();
       		 alert("Clearing all of your facility entries");
		     document.forms["clearFacilitiesForm"].submit();
	   });
	   $("a#clearCustomFacilitiesLink").click(function(e) {
		   	 e.preventDefault();
     		 alert("Clearing all of your user created facility entries");
		     document.forms["clearCustomFacilitiesForm"].submit();
	   });

	   */
	   $("a.unclickable").click(function(e) {
			  e.preventDefault();
	   });
	   $(".surveyCategoryRow .nameCell").click(function() {
		     var rowId = $(this).closest(".surveyCategoryRow").attr("id");
		     var title = $(this).closest(".surveyCategoryRow").children(".nameCell:first").attr("id");
       //    alert("Survey Category: "+title +" was chosen.");
		     //now add to the form 'chooseAction' and submit it.
		     $("#chooseAction").children("input#surveyCategoryId").val(rowId);
		     
		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );
		     document.forms["chooseAction"].submit();
	   });
	   
	   $(".surveyCategoryRow .drop").click(function() {
		     var rowId = $(this).closest(".surveyCategoryRow").attr("id");
		     var answer = confirm("Delete all answers for this survey category?")
		     if (answer){
		       $("#dropAction").children("input#surveyCategoryId").val(rowId);
		       document.forms["dropAction"].submit();
		     }
	   });
	   
});
</script>

</head>
<body>
<div class="header"> 
<?php  echo "You are logged in as: ". $_SESSION['username'].'<br/>';
?>
<a href="logout.php">Log out</a> | 
<a href="surveyHome.php">Survey Home</a>
</div>
<hr/>
<?php if(isset($statusLabel) && $statusLabel != ''){?>
  <h4 class="statusLabel"><?php echo $statusLabel;?></h4>
<?php }?>
<?php if(isset($errorLabel) && $errorLabel != ''){?>
  <h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>


<h4 class="notificationBanner" >Please click on the survey(s) you wish to complete.</h4>


<h3>Survey Categories for <?php echo $facilityName;?>:</h3>
<p>Click on a survey category title to see its activity categories.</p>
<div>
<table>
<col class="1">
<col class="2"/>
	<thead>
	   <?php if(defined('DEBUG')){?>
		<th>Id</th>
	   <?php }?>
	    <th>Clear all<br/> answers</th>
		<th>Title</th>
		<th>Completion<br/>Status</th>
		<?php if(!isset($customFacilityId)){?>
		<th>Started or<br/>Completed By*</th>
		<?php }?>
	</thead>
	<tbody>
	<?php
	echo $surveyCategoriesRows;  // identified by "customFacilityRow" in a cell.
	?>
	</tbody>
</table>
<hr/>
<p><label class="gray">* Survey Categories for this facility already started by another user are locked.  We suggest you email
     that user if a facility or survey category was chosen in error.  
     A user may clear answers via the "Clear my answers" box above, after which the survey category will be unlocked. </label> </p>
</div>

<form id="chooseAction" name="chooseAction"
	action="activityCategories.php" method="post">
	<input type="hidden"
	id="surveyCategoryId" name="surveyCategoryId" value="nothing" /> 
</form>

<form id="dropAction" name="dropAction"
	action="surveyCategories.php" method="post">
	<input type="hidden" id="surveyCategoryId" name="surveyCategoryId" value="nothing" />
	<input type="hidden" id="fid" name="fid" value="<?php echo $fid; ?>" />
	<input type="hidden" id="is_cf" name="is_cf" value="<?php echo $is_cf; ?>" />

	<input type="hidden" id="dropA2" name="dropAction" value="" />
</form>

<?php 
if(defined('DEBUG')){
 echo '<hr/><div>';
 if(isset($userFacilityId)) echo " userFacilityId (posted and now put in Session):  ". $userFacilityId.'<br/>';
 if(isset($customFacilityId)) echo " customFacilityId (posted and now put in Session):  ". $customFacilityId.'<br/>';
 if(isset($surveyCategoryId)) echo " surveyCategoryId posted:  ". $customFacilityId.'<br/>';
 echo '</div>';
}



?>
</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>