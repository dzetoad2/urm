<?phptry{require_once( 'urm_secure/functions.php');require_once( 'urm_secure/activitiesFunctions.php');require_once( 'urm_secure/breadCrumbFunctions.php');require_once( 'urm_secure/sessionStateFunctions.php');if(!loggedin()){	if(defined('DEBUG'))echo "activities.php :  but not loggedin!<br/>\n";	header("Location: login.php");	exit();}if(!isset($_SESSION['username']) || !isset($_SESSION['userid'])){	$em = "error: un or userid not set";	throwMyExc($em);}$un = $_SESSION['username'];$userId = $_SESSION['userid'];savePostAndSessionVars($userId,$_POST,$_SESSION,"activities.php");//------------------ENFORCEMENT --------------//--------- if userfacid or customfacid is not set, error!! ---if(!isset($_SESSION['userFacilityId']) && !isset($_SESSION['customFacilityId'])){	$em = "error: neither userfacilityid or customfacilityid are set! one 	      of these must be sent over from surveyCategories to here.";	throwMyExc($em);}if(!isset($_SESSION['surveyCategoryId'])){		$em = "error: surveycategoryid not set in session - it is required.";		throwMyExc($em);}if(!isset($_POST['activityCategoryId']) && !isset($_SESSION['activityCategoryId']) && !isset($_POST['activityCategoryDocId']) && !isset($_SESSION['activityCategoryDocId'])){	 	$em = "error: activitycategoryid/activitycategorydocid not set, neither post nor session - one of the 4 is required.";		throwMyExc($em);//	exit();	 }if(isset($_POST['activityCategoryId'])  && isset($_POST['activityCategoryDocId'])){	$em='activities: both post activitycat id and post activitycat doc id are set! error';	throwMyExc($em);}//-----------------------------END ENFORCEMENT---------------//-------------- now scrub stale activityId or customActivityId, as this page sets exactly one of those (XOR) and//      goes to 'activity.php' for choice of activity or customactivity.if(isset($_SESSION['activityId'])){	unset($_SESSION['activityId']);}if(isset($_SESSION['customActivityId'])){	unset($_SESSION['customActivityId']);}//====================== end stale activityid/customacdtivityid  cleaning================$statusLabel = "";if(isset($_POST['dropAction'])){	if($userId!=1){   		   $em = "Non-admin users may not delete Activities.";   		   throwMyExc($em);   }else{   	    $activityId = $_POST['activityId'];   	    //now do the activity drop.   	    $dropResultErrorMsg = deleteActivity($activityId);   	    	$statusLabel = $dropResultErrorMsg;   }} if(isset($_SESSION['userFacilityId'])){	$userFacilityId = $_SESSION['userFacilityId'];	unset($_SESSION['customFacilityId']);}if(isset($_SESSION['customFacilityId'])){	$customFacilityId = $_SESSION['customFacilityId'];	unset($_SESSION['userFacilityId']);}if(isset($_SESSION['surveyCategoryId'])){     //this if check is redundant i think.	$surveyCategoryId = $_SESSION['surveyCategoryId'];  // }if(isset($_POST['activityCategoryId'])){//	die('activitycategoryid was set in post');	$activityCategoryId = $_POST['activityCategoryId'];	$_SESSION['activityCategoryId'] = $activityCategoryId;}if(isset($_POST['activityCategoryDocId'])){	//die('activitycategorydocid was set in post');	$activityCategoryDocId = $_POST['activityCategoryDocId'];	$_SESSION['activityCategoryDocId'] = $activityCategoryDocId;}if(isset($_SESSION['activityCategoryId'])){//	die('activity cat id  was set in session');	$activityCategoryId = $_SESSION['activityCategoryId'];}if(isset($_SESSION['activityCategoryDocId'])){//	die('activity cat docid was set in session');	$activityCategoryDocId = $_SESSION['activityCategoryDocId'];} //	 $em = 'error: neither activitycategoryid nor activitycategorydocid avail (neither in session nor post): one exclusively is required.';//	 throwMyExc($em);//if both are set, or both are unset, its error. need XOR one.if(         (isset($activityCategoryId) && isset($activityCategoryDocId))        ){	$em=' activities.php:  error: both act cat id and act cat doc id are set';	throwMyExc($em);}if(  (!isset($activityCategoryId) && !isset($activityCategoryDocId)  )   ){	$em=' activities.php:  error: neither  act cat id nor act cat doc id are set';	throwMyExc($em);}if(isset($activityCategoryId)){		//	die('activitycategoryid is set!');					    $activityCategoryName = getActivityCategoryName($activityCategoryId);    $activityCategoryDoc = getActivityCategoryDoc($activityCategoryId);        $activityCategoryNameOrDocTitle = $activityCategoryName;    }elseif(isset($activityCategoryDocId)){	//do name and doc for the doc situation ,i.e. survey category 1. 		$acDocDAO = getActivityCategoryDocDAO($activityCategoryDocId);			$activityCategoryDocTitle =  $acDocDAO->title;	$activityCategoryDoc =  $acDocDAO->doc;		$activityCategoryNameOrDocTitle = $activityCategoryDocTitle;							}//------------------- NOW GET ALL THE ROW DATA , THIS IS only AFTER ALL CHANGES HAVE BEEN UPDATED.---------------$is_ca = 0; //this page only shows activities, not customActivities.if(isset($userFacilityId)){ 	$fid = $userFacilityId; 	$is_cf = 0; } else if(isset($customFacilityId)){    $fid = $customFacilityId;    $is_cf = 1; } else{    $em ='impossible: error here.';    throwMyExc($em); }if($userId == 1){ if(!$activityRows = getActivityRowsAdminHtml($userId, $fid, $is_cf, $is_ca, $activityCategoryId)){	$errorMsg = "Error in getActivityRowsAdminhtml(un)";	throwMyExc($errorMsg); }}else {//$userId, $facilityId (this is the same as userfacilityId or else customFacilityId), $activityId, $isCustomFacility, $isCustomActivity, $activityCategoryId if(isset($activityCategoryId)){     $activityRows = getActivityRowsHtml($userId,$fid,$is_cf,$is_ca,  $activityCategoryId); }elseif(isset($activityCategoryDocId)){ 	   $activityRows = getActivityGroupRowsHtml($userId,$fid,$is_cf,$is_ca,  $activityCategoryDocId);                } if(!$activityRows){ 	$errorMsg = "Error in activities.php regarding the call to getActivityRowshtml(un)";	throwMyExc($errorMsg); }}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Activities</title><link rel="stylesheet" type="text/css" href="css/reset-min.css"/><link rel="stylesheet" type="text/css" href="css/styles.css" /><link rel="stylesheet" type="text/css" href="css/tables.css" /><link rel="stylesheet" type="text/css" href="css/textcontent.css" /><script type="text/javascript" src="js/jquery-1.6.2.min.js"></script><script type="text/javascript" src="js/tables.js"></script><script type="text/javascript">                                         $(document).ready(function() {//	   $('table th:even').addClass('alt');	   $("a#clearCustomActivitiesLink").click(function(e) {		   	 e.preventDefault();//     		 alert("Clearing all of your user created facility entries");		     document.forms["clearCustomFacilitiesForm"].submit();	   });	   $("a.unclickable").click(function(e) {			  e.preventDefault();	   });	   $(".activityRow .nameCell").click(function() {		     var rowId = $(this).closest(".activityRow").attr("id");		     var title = $(this).closest(".activityRow").children(".nameCell:first").attr("id");//		     alert("Activity: "+title +" was chosen.");		     //now add to the form 'chooseAction' and submit it.		     $("#viewAction").children("input#activityId").val(rowId);		     		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );		     document.forms["viewAction"].submit();		   });	   $(".activityRow .edit").click(function() {		     var rowId = $(this).closest(".activityRow").attr("id");		     $("#editAction").children("input#activityId").val(rowId);		     document.forms["editAction"].submit();			 	   });	   $(".activityRow .drop").click(function() {		     var rowId = $(this).closest(".activityRow").attr("id");		     $("#dropAction").children("input#activityId").val(rowId);		     document.forms["dropAction"].submit();	   }); 	   		   $("#createActivity").click(function(e) {		     document.forms["chooseAction3"].submit();		     e.preventDefault();		   });	   	   	   });</script></head><body><?php// include the top header barinclude("includes/header.php");?><?php if(isset($activityCategoryDoc) && $activityCategoryDoc != ''){?> <h3> <?php echo $activityCategoryNameOrDocTitle; ?>:</h3> <div class="content"    > <?php echo nl2br($activityCategoryDoc);?> </div><?php } ?><h3>Activities for <?php echo $activityCategoryNameOrDocTitle; ?>:</h3><p>Click on a title to input survey data for that activity.</p><div><table>	<thead>		<tr> 		<?php if($userId == 1){?>		 <th>Edit</th> 		 <th>Drop</th> 		<?php } ?>		<?php if(defined('DEBUG')){?>		 <th>Id</th>		<?php }?>		<th>Id Num</th>		<th>Title</th>		<th>Completion<br/>Status</th>			</tr>	</thead>	<tbody>	<?php	echo $activityRows;  // identified by "customFacilityRow" in a cell.	?>	</tbody></table><?php if($userId==1){?><a id="createActivity" name="createActivity" href="createActivity.php">Create an Activity</a><br/><?php }?></div><p class="footerNav"><a href="activityCategories.php#activityCategories">Activity Categories</a></p><form id="viewAction" name="viewAction"	action="activity.php" method="post">	<input type="hidden"	id="activityId" name="activityId" value="nothing" /></form><form id="editAction" name="editAction"	action="editActivity.php" method="post">	<input type="hidden"	id="activityId" name="activityId" value="nothing" /></form><form id="dropAction" name="dropAction"	action="activities.php" method="post">	<input type="hidden"	id="activityId" name="activityId" value="nothing" />	<input type="hidden"	id="dropA" name="dropAction" value="" /></form><form id="chooseAction3" name="chooseAction3"	action="createActivity.php" method="post">	<input type="hidden"	id="activityType" name="activityType" value="activity" /></form><?phpif(defined('debug')){ echo '<hr/><div>'; if(isset($userFacilityId)) echo " userFacilityId (Session):  ".     $userFacilityId.'<br/>'; if(isset($customFacilityId)) echo " customFacilityId (Session):  ". $customFacilityId.'<br/>'; if(isset($surveyCategoryId)) echo " surveyCategoryId (session):  ". $surveyCategoryId.'<br/>'; if(isset($activityCategoryId)) echo " activityCategoryId (posted and now put in session):  ". $activityCategoryId.'<br/>'; echo '</div>';}}catch(Exception $e){	goErrorPage($e);}?></body></html>