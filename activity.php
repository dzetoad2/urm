<?php
try{

require_once( 'urm/functions.php');
require_once( 'urm/activityFunctions.php');
require_once( 'urm/activitiesSupplementalFunctions.php');
require_once( 'urm/sessionStateFunctions.php');
require_once( 'urm/surveyCategoriesFunctions.php');

 if(!loggedin()){
	//echo "userarea but not loggedin!<br/>\n";
	header("Location: login.php");
	exit();
 }
 if(!isset($_SESSION['username']) || !isset($_SESSION['userid'])){
	$em="error: un or userid not set";
	throwMyExc($em);
	
 }
 //--------- if userfacid or customfacid is not set, error!! ---
 if(!isset($_SESSION['userFacilityId']) && !isset($_SESSION['customFacilityId'])){
	if(defined('debug')){
	  $em="error: neither userfacilityid nor customfacilityid are set! one 
	      of these must be sent over from surveyCategories, to activitycategories, to activities, and finally to to activity.php";
	  throwMyExc($em);
	}else{
	  $em= 'Neither userfacilityid nor customfacilityid are set - one 
	      of these must be sent over from surveyCategories, to activitycategories, to activities, and finally to to activity.php';
	  throwMyExc($em);
	}
 }
 if(!isset($_POST['surveyCategoryId']) && !isset($_SESSION['surveyCategoryId'])){  //this is right
	$em="error: surveycategoryid not set - it is required.";
	throwMyExc($em);
 }
 if(!isset($_POST['activityId']) && !isset($_SESSION['activityId']) &&
   !isset($_POST['customActivityId']) && !isset($_SESSION['customActivityId'])){
	$em="error: neither activityId nor customActivityId set - one is required.";
	throwMyExc($em);
 }

$un = $_SESSION['username'];
$userId = $_SESSION['userid'];
savePostAndSessionVars($userId,$_POST,$_SESSION,"activity.php");

//------------------
if(isset($_SESSION['userFacilityId'])){
	$userFacilityId = $_SESSION['userFacilityId'];
	unset($_SESSION['customFacilityId']);
	$fid = $userFacilityId;
	$is_cf = 0;
}else if(isset($_SESSION['customFacilityId'])){
	$customFacilityId = $_SESSION['customFacilityId'];
	unset($_SESSION['userFacilityId']);
	$fid = $customFacilityId;
	$is_cf = 1;
}
//------------------

if(isset($_SESSION['surveyCategoryId'])){
	$surveyCategoryId = $_SESSION['surveyCategoryId'];
}


if(isset($_POST['activityCategoryId'])){
//	die('activity cat id was set in post');
	$activityCategoryId = $_POST['activityCategoryId'];
	$_SESSION['activityCategoryId'] = $activityCategoryId;
	unset($_SESSION['activityCategoryDocId']);  
}else if(isset($_SESSION['activityCategoryId'])){
//	die('activity cat id was set in session.');
	$activityCategoryId = $_SESSION['activityCategoryId'];
	unset($_SESSION['activityCategoryDocId']);
}elseif(isset($_SESSION['activityCategoryDocId'])){
	$activityCategoryDocId = $_SESSION['activityCategoryDocId'];
	unset($_SESSION['activityCategoryId']);
}elseif(isset($_POST['activityCategoryDocId'])){
	$em=' in activity.php near top: post activitycategorydocid was set, impossible!';
	throwMyExc($em);
}else{
	$em='none was set: act cat id , nor act cat doc id, neither post nor session';
	throwMyExc($em);
}
//----------------------------
if(isset($activityCategoryId) && isset($activityCategoryDocId)){
	$em='only one of two may be set! must be one not 0!  act cat id  and act cat doc id';
	throwMyExc($em);
}
//----------------------------



//-----------------------------
if(isset($_POST['activityId'])){
	$activityId = $_POST['activityId'];
	$_SESSION['activityId'] = $activityId;
} else if(isset($_SESSION['activityId'])){
	$activityId = $_SESSION['activityId'];
//	unset($_SESSION['activityId']);
}
if(isset($_POST['customActivityId'])){
	$customActivityId = $_POST['customActivityId'];
	$_SESSION['customActivityId'] = $customActivityId;
} else if(isset($_SESSION['customActivityId'])){
	$customActivityId = $_SESSION['customActivityId'];
//	unset($_SESSION['customActivityId']);
}
//-----------------------------




//------------------- NOW GET ALL THE ROW DATA.---------------
$aid = "-1";
if(isset($activityId)){
 $aid = $activityId;
 $is_ca = 0;
 if(!$activityData = getActivityData($activityId)){
	$em="Error in getActivityData()";
	throwMyExc($em);
 }
}else if(isset($customActivityId)){
 $aid = $customActivityId;
 $is_ca = 1;
 if(!$activityData = getCustomActivityData($userId, $customActivityId)){
	$em="Error in getCustomActivityData()";
	throwMyExc($em);
 }
}


//=============PREPARE BEFORE FORM SUBMITTAL

$errorLabel="";
$statusLabel="";

//========= DOING ACTIVITY INIT VARS HERE. INCLUDES IMG ARROWS AND THE FORM VARS.
require_once 'urm/activity/activityInitVars.php';


//================================ SURVEY SUBMITTAL HERE========================

if(isset($_POST['hiddenSubmitName'])){  //submitSurveyForm
	
	//=====DO SUBMIT BLOCK CONTENTS HERE
 require_once('urm/activity/activitySubmitProcessing.php');
	
}
//=============END SUBMIT=======
else{
//otherwise, lastly, retrieve the possibly updated data from db.
	//moved activityinit vars from here up to top. --done
	
	
	
 if(FALSE === ($row = getSurveyAnswerRow($userId, $fid,$aid,$is_cf,$is_ca))){
	 $em = "Error fetching full survey answer row from survey answer table";
	 throwMyExc($em);
 }else if($row == ""){
	//$statusLabel .= "New Activity";
 }else{
	if(isset($row)){
	$isPerformedAdult = $row['isPerformedAdult'];
	$isPerformedPediatric = $row['isPerformedPediatric'];
	$isPerformedNatal = $row['isPerformedNatal'];
	$hasTimestandardAdult = $row['hasTimestandardAdult'];
	$hasTimestandardPediatric = $row['hasTimestandardPediatric'];
	$hasTimestandardNatal = $row['hasTimestandardNatal'];
	$durationAdult = $row['durationAdult'];
	$durationPediatric = $row['durationPediatric'];
	$durationNatal = $row['durationNatal'];
	$volumeAdult = $row['volumeAdult'];
	$volumePediatric = $row['volumePediatric'];
	$volumeNatal = $row['volumeNatal'];
	$methodologyAdult = $row['methodologyAdult'];
	$methodologyPediatric = $row['methodologyPediatric'];
	$methodologyNatal = $row['methodologyNatal'];
	}else{
	}
 }
}//else (if not submit then...)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Activity</title>
<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/textcontent.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />
<link rel="stylesheet" type="text/css" href="css/animatedForm.css" />

<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/activityJs.js"></script>

</head>
<body>
<div class="header"> 
<?php
echo "You are logged in as: ". $_SESSION['username'].'<br/>';
?>
<a href="logout.php">Log out</a> | 
<?php if(isset($activityId)){?>
<a href="activities.php">Back to Activities</a>
<?php } else if(isset($customActivityId)){?>
<a href="activityCategories.php">Back to Activity Categories</a>
<?php }?>
</div>
<hr/>
<?php 
if(isset($statusLabel) && $statusLabel!='') 
  echo "<h4 class='statusLabel'>". $statusLabel."</h4>";
if(isset($errorLabel) && $errorLabel!='')
  echo "<h4 class='errorLabel'>". $errorLabel."</h4>";
?>
<h3>Activity: <?php echo $activityData['title'];?></h3>
<div class="content" readonly="readonly">
<?php 
   echo nl2br($activityData['descr']) . '<br/>';
?>
</div>








 <form id="submitSurveyForm" name="submitSurveyForm" action="activity.php" method="post">
<!-- echo form data here --> 
<?php require_once('urm/activity/activityFormContents.php');?>
</form>










 













<!-- stop form data here -->






<?php 
if(defined('DEBUG')){
 echo '<hr/><div>';
 if(isset($userFacilityId)) echo " userFacilityId (Session):  ". $userFacilityId.'<br/>';
 if(isset($customFacilityId)) echo " customFacilityId (Session):  ". $customFacilityId.'<br/>';
 if(isset($surveyCategoryId)) echo " surveyCategoryId (session):  ". $surveyCategoryId.'<br/>';
 if(isset($activityCategoryId)) echo " activityCategoryId (posted and now put in session):  ". $activityCategoryId.'<br/>';
 echo '</div>';
 
}
}catch(Exception $e){
	goErrorPage($e);
}
?>





</body>
</html>