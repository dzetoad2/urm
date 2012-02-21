<?php
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/activityFunctions.php';
require_once 'urm_secure/editActivityFunctions.php';
require_once 'urm_secure/sessionStateFunctions.php';
require_once( 'urm_secure/activity/activityFormDAO.php');    
require_once( 'urm_secure/createActivity/createActivityController.php');
if(!loggedin()){
  //echo "userarea but not loggedin!<br/>\n";
  header("Location: login.php");
  exit();
}
if(!isset($_SESSION['userid'])){
	$em="userid not set";
	throwMyExc($em);
}
$userId = $_SESSION['userid'];
$statusLabel = "";
//==== STRICT TEST ON SURVEYCATEGORYID ========
if(isset($_POST['surveyCategoryId'])){
	
//	$activityCategoryId = $_POST['activityCategoryId'];
	$em = 'impossible - surveyCategoryId was POSTed to editActivity!!! error in architecture.';
	throwMyExc($em);
}else if (isset($_SESSION['surveyCategoryId'])){
	$surveyCategoryId = $_SESSION['surveyCategoryId'];
}
if(isset($_POST['surveyCategoryId'])){
//	$activityCategoryDocId = $_POST['activityCategoryDocId'];
	$em = 'impossible - surveyCategoryId was POSTed to editActivity!!! error in architecture.';
	throwMyExc($em);
}
//-----------------end strict test
$activityType = "";
if(isset($_POST['activityId'])){
	$em='postvar activityid found, not supported!';
	throwMyExc($em);
} else if(isset($_SESSION['activityId'])){
	$em='sessionvar activityid found. not supported!';
	throwMyExc($em);
}
if(isset($_POST['customActivityId']) ){
	$customActivityId = $_POST['customActivityId'];
	$activityType = "customActivity";
	$_SESSION['customActivityId'] = $customActivityId;
}elseif(isset($_SESSION['customActivityId'])){
	$customActivityId = $_SESSION['customActivityId'];
}else{
	$em='customactivty neither in post nor session. ';
	throwMyExc($em);
}

if($activityType == "activity" && $userId != 1){
	$em="Non-admin users may not edit an Activity.";
	throwMyExc($em);
}
$activityData = array();

$afDao = new activityFormDAO();

//if(isset($activityId)){
// if(!$activityData = getActivityData($activityId)){
//	$em="Error in getActivityData()";
//	throwMyExc($em);
// }
// $em = 'editactivity: activityid was set,  should never happen.';
// throwMyExc($em);
//} 


$activityData = getCustomActivityData($userId, $customActivityId);
 if(!$activityData){
	$em="Error in getCustomActivityData()";
	throwMyExc($em);
   }
   
    $aid = $customActivityId;
	$fid = $activityData['fid'];
	$is_cf = $activityData['is_cf'];
   
   //================	GET THE CURRENT SURVEY ANSWER DATA HERE. 
   $is_ca = 1;

   
   
   
   
   
   
   
   

//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------
if ( !isset($_POST['submitEdit'])){
// ------ not a editpost, so now get the data from activityId or customActiv-id. -------
	
if(isset($customActivityId)){
  
 if(FALSE === ($row = getSurveyAnswerRow($userId, $fid,$aid,$is_cf,$is_ca))){ // 1:  is_ca is ALWAYS 1 here.
	 $em = "Error fetching survey answer row from survey answer table.";
	 throwMyExc($em);
 }else if($row == ""){
	//$statusLabel .= "New Activity";
 }else{
 	
 	$afDao = new activityFormDAO();
 	
	if(isset($row)){
	   $afDao->isPerformedAdult = $row['isPerformedAdult'];
	$afDao->isPerformedPediatric = $row['isPerformedPediatric'];
	$afDao->isPerformedNatal = $row['isPerformedNatal'];
	$afDao->hasTimestandardAdult = $row['hasTimestandardAdult'];
	$afDao->hasTimestandardPediatric = $row['hasTimestandardPediatric'];
	$afDao->hasTimestandardNatal = $row['hasTimestandardNatal'];
	$afDao->durationAdult = $row['durationAdult'];
	$afDao->durationPediatric = $row['durationPediatric'];
	$afDao->durationNatal = $row['durationNatal'];
	$afDao->volumeAdult = $row['volumeAdult'];
	$afDao->volumePediatric = $row['volumePediatric'];
	$afDao->volumeNatal = $row['volumeNatal'];
	$afDao->methodologyAdult = $row['methodologyAdult'];
	$afDao->methodologyPediatric = $row['methodologyPediatric'];
	$afDao->methodologyNatal = $row['methodologyNatal'];
	}else{
		$em='row not set';
		throwMyExc($em);
	}
 }
   
   
   
   
   
   
   
   
   
   
   
   
   
    
   
   
   
   
   
   
   
 }
} else if( isset($_POST['submitEdit'])){
	$errorLabel = "";
	$activityData['title'] = trim($_POST['title']);
	$activityData['descr'] = trim($_POST['descr']);
	
	if($activityData['title'] == ""){
		$errorLabel.='Please enter a title<br/>';
	}
	if($activityData['descr'] == ""){
		$errorLabel.='Please enter a description<br/>';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//=============== DO ALL FORM ERROR CHECKING HERE.============
	//- use createactivitycontroller to error check the form.
	createActivityController::errorCheck($_POST, $afDao);
	//if any errors, they are now put in the afdao!
	
	if($afDao->errorLabel != ''){
	  $separator = '';
	  if($errorLabel!=''){
	  	$separator = ', ';    //just for error msg formatting
	  } 
	  $errorLabel .= $separator . $afDao->errorLabel;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($errorLabel==''){   //if still no errors:
	//======================================== NO ERRORS - SO NOW DO THE UPDATES =============================
		    if(isset($customActivityId)){
//				echo "descr: ". $activityData['descr'];
		    	$rowsAffected =	
				updateCustomActivity(
				$activityData['title'],	$activityData['descr'], $customActivityId);
		    }else if(isset($activityId)){
//		    	echo "attempt to updateActivity() here.  descr: ". $activityData['descr'];
		    
		    	$rowsAffected =	
				updateActivity(
				$activityData['title'],	$activityData['descr'], $activityId);
		    }
			if($rowsAffected == 0){
//				echo ("<br/>0 rows affected");
			}
			if($rowsAffected == -1){
				$errorLabel .= "Error - Unable to update User Created Activity to the database.";
			}
		
			if($errorLabel==''){
				//if errorlabel is still blank, then we continue to do the submit survey answer.
				 
				createActivityController::submitSurveyAnswer($userId, $aid,$fid,$is_cf,$is_ca, $afDao);
				
			}
			//$statusLabel = "Success updating user created activity. <br/>";
			$_SESSION['updateCustomActivitySuccess'] = 'true';
			header('Location: activityCategories.php#activityCategories');
			exit();
		if(!isset($customActivityId)){
		 	$em="Error: Neither activityid nor customactivityid are set";
		 	throwMyExc($em);
		}
	}
}	
//======================== SUBMIT PROCESS DONE=========================
 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Edit Activity</title>

<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/textcontent.css" />
<link rel="stylesheet" type="text/css" href="css/animatedForm.css" />

<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/activityJs.js"></script>

<script type="text/javascript">                                         
//$(document).ready(function() {
//	   //$("#descr").elastic();
//	 });
</script>

</head>
<body>
<div class="header">
<?php
echo "You are logged in as: ". $_SESSION['username'].'<br/>';
?>

<?php if(isset($activityId)){?>
<a href="activities.php">Back to Activities</a>
<?php } else if(isset($customActivityId)){?>
<a href="activityCategories.php#activityCategories">Back to Activity Categories</a>
<?php 
}else{ 
 $em="neither customactivityid nor activityId were set! Please contact site administrator";
 throwMyExc($em);
}?>
</div>
<hr/>
<?php if(isset($statusLabel) && $statusLabel!=''){ ?>
<h4 id="statusLabel" class="statusLabel"><?php echo $statusLabel; ?></h4>
<?php }?>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
<h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>
 
<h3>Edit Activity</h3>


<form action="editActivity.php" method="post">

<div class="inputunit row">
 <fieldset class="inputBox">
  <ol>
  <li>
   <label>Title:</label><br/>  
   <input class="widefield" type="text" name="title" value="<?php echo $activityData['title']; ?>" />
  </li>
  <li> 
   <label>Description:</label><br/> 
   <textarea class="editcontent" name="descr"><?php echo $activityData['descr']; ?></textarea>
  </li>
  </ol>
 </fieldset> 
</div>


 
<?php  
// -------- MANIPULATE AFDAO TO YIELD OUTPUT INTO THIS FORM:
require_once('urm_secure/createActivity/createActivityFormContents.php');  //contents of the form.
?>


<div>
 <input type="submit" name="submitEdit" value="Submit Edit" />
</div>
</form>


<?php
if(defined('DEBUG')){
 echo "<hr/>Your activity type is : ".$activityType."<br/>";
       if(isset($activityId)) echo "Your activityId is : ".$activityId."<br/>";
       if(isset($customActivityId)) echo "Your customActivityId is : ".$customActivityId."<br/>";
}
?>

<!--</div>-->
<!--div has no start tag???-->






</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>