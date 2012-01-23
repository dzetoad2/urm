<?php
try{

require_once( 'urm_secure/functions.php');
require_once( 'urm_secure/createActivityFunctions.php');
require_once( 'urm_secure/sessionStateFunctions.php');
require_once( 'urm_secure/activity/activityFormDAO.php');
require_once( 'urm_secure/createActivity/createActivityController.php');

if(!loggedin()){
  //echo "userarea but not loggedin!<br/>\n";
    header("Location: login.php");
    exit();
}
if(!isset($_SESSION['userid'])){
	$em='userid not set';
	throwMyExc($em);
}
$userId = $_SESSION['userid'];
if(isset($_POST['activityCategoryId'])){
	$activityCategoryId = $_POST['activityCategoryId'];
}else if (isset($_SESSION['activityCategoryId'])){
	$activityCategoryId = $_SESSION['activityCategoryId'];
}else{
	
	
	//$em = 'activityCategoryId neither in session nor post: required.';  //
	 
	
	
	
	
	//throwMyExc($em);
	
	
	
	
	
	
	
	
	
	
//	$_SESSION['errorMsg'] = $em;
//    header('Location: errorPage.php');
//	exit();
}
if(isset($_POST['activityType'])){
	$activityType = $_POST['activityType'];
	$_SESSION['activityType'] = $activityType;
}else if (isset($_SESSION['activityType'])){
	$activityType = $_SESSION['activityType'];
}else{
	 $em= "activity type (custom or not) is neither in session nor post: required.";
	 throwMyExc($em);
}
if($activityType == "activity" && $userId != 1){
	$em = "Non-admin users may not create an Activity.";
	throwMyExc($em);
//	$_SESSION['errorMsg'] = $em;
//	header('Location: errorPage.php');
//	exit();
}
if($activityType == "customActivity"){
   if(!isset($_SESSION['surveyCategoryId'])){
	 $em='Error: surveycategoryid not set. for creating custom activity, surveycategoryid (session) is required.';
	 throwMyExc($em);
	 //	 $_SESSION['errorMsg'] = $em;
//	 header('Location: errorPage.php');
//	 exit();
   }else{
   	$surveyCategoryId = $_SESSION['surveyCategoryId'];
   	$is_ca = 1;
   }
}else{
	$is_ca = 0;
}
//====NEW REQUIREMENT... MUST HAVE FACILITY ID, AND KNOW IF ITS IS_CF 1 OR 0======
// VALIDATE POST FID, IS_CF, IS_CA:
if(!isset($_POST['fid']) || trim($_POST['fid'])==''){
 	 $em='createactivity: post fid not set or is blank';
	 throwMyExc($em);
// 	 $_SESSION['errorMsg'] = $em;
//	 header('Location: errorPage.php');
//	 exit();
}
if(!isset($_POST['is_cf']) || trim($_POST['is_cf'])==''){
 	 $em='createactivity: post is_cf not set or is blank';
	 throwMyExc($em);
 	 //	 $_SESSION['errorMsg'] = $em;
//	 header('Location: errorPage.php');
//	 exit();	
}
if(!isset($_POST['is_ca']) || trim($_POST['is_ca'])==''){
  	 $em='createactivity: post is_ca not set or is blank';
	 throwMyExc($em);
  	 //	 $_SESSION['errorMsg'] = $em;
//	 header('Location: errorPage.php');
//	 exit();	
}
$fid=$_POST['fid'];
$is_cf=$_POST['is_cf'];

$activityId = 'dummyValue'; //just a placetaker, the form requires this to be set
$customActivityId = 'dummyValue'; //just a placetaker, the form requires this to be set
$activityData = array();
$activityData['isForAdult'] = "yes";
$activityData['isForPediatric'] = "yes";
$activityData['isForNatal'] = "yes";
//savePostAndSessionVars($userId,$_POST,$_SESSION,"createActivity.php");
//========= INIT THE ACTIVITY VARS HERE - external======
//require_once 'urm_secure/activity/activityInitVars.php';
$afDao = new activityFormDAO();

//======================================================
$title = '';
$descr = '';
if ( isset($_POST['createSubmit'])){
	$afDao->errorLabel='';
	$title = trim($_POST['title']);
	$descr = trim($_POST['descr']);
	if($title == ''){
		$afDao->errorLabel.= "Error: Please enter a title<br/> ";
	}
	if($descr == ''){
		$afDao->errorLabel.= "Error: Please enter a description<br/> ";
	}	
	//=======================START ACTIVITY ERRORCHECKING - IF NO ERROR THEN ERRORLABEL WILL STILL BE BLANK.
	createActivityController::errorCheck($_POST, $afDao);  //this checks errorlabel, and adds to it if there are errors. 
	//========================END ACTIVITY ERROR CHECKING
	if($afDao->errorLabel == ''){ // if still no error here, then its ok to create in the db.
		$activityTypeLabel = '';
			if(!isset($_POST['fid'])){  throwMyExc('submitsurveyanswer(): post fid not set');  }
 		    if(!isset($_POST['is_cf'])) throwMyExc('submitsurveyanswer(): post is_cf not set');
 		    $fid = $_POST['fid'];
 		    $is_cf = $_POST['is_cf'];
		    if($activityType == "customActivity"){
		      $activityTypeLabel = "custom";
			  $aid = createCustomActivity( $title, $descr, $surveyCategoryId, $userId);  // need to add fid + is_cf to params.
		    }else if($activityType == "activity"){
		      $rowsAffected = 
			  createActivity($title, $descr, $activityCategoryId);
			  if($rowsAffected == 0){
				$afDao->errorLabel.= "Error - Unable to add this activity entry to the database.<br/>";
			  }
		    }
			
			if($afDao->errorLabel == ''){
			//still no error - so it was successfully added. proceed.
			//just now we created a custom activity - we need the activityId of it. must extract from table.
			createActivityController::submitSurveyAnswer($userId, $aid,$fid,$is_cf,$is_ca, $afDao);
			}
		if($afDao->errorLabel==''){
			$afDao->statusLabel =  "Success creating ".$activityTypeLabel." activity.<br/>";
			$_SESSION['createActivitySuccess'] = $afDao->statusLabel;
			 if($activityType == "activity"){
  				$location = "activities.php"; 
			 }elseif($activityType == "customActivity"){
 				$location = "activityCategories.php"; 
 			 }
			header('Location: '.$location);
		}
	}



 


}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Create Activity</title>
<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/textcontent.css" />
<link rel="stylesheet" type="text/css" href="css/animatedForm.css" />

<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/activityJs.js"></script>

</head>
<body>
<div>
<?php
echo "You are logged in as: ". $_SESSION['username'].'<br/>';
?>

<?php if($activityType == "activity"){?>
 <a href="activities.php">Return to Activities</a>
<?php }else if($activityType == "customActivity"){?>
 <a href="activityCategories.php">Return to Activity Categories</a>
<?php }?>
</div>

<hr/>

<?php if(isset($afDao->errorLabel) && $afDao->errorLabel!=''){ ?> 
<h4 class="errorLabel"><?php echo $afDao->errorLabel; ?></h4>
<?php }?>
<?php if(isset($afDao->statusLabel) && $afDao->statusLabel!=''){ ?>
<h4 class="statusLabel"><?php  echo $afDao->statusLabel; ?></h4>
<?php }?>
 
<?php if($activityType == "customActivity"){?>
<h3>Create User Created Activity</h3>
<?php }elseif($activityType == "activity"){?>
<h3>Create Activity</h3>
<?php }?>
<form action="createActivity.php" method="post">

<div class="inputunit row">
<fieldset class="inputBox">
  <ol>
  <li>
  <label>Title:</label><br/>  
  <input class="widefield" type="text" name="title" value="<?php echo $title; ?>" />
  </li>
  <li> 
  <label>Description:</label><br/>  
  <textarea class="editcontent" name="descr"><?php echo $descr; ?></textarea>
  </li>
  </ol>
</fieldset> 
</div>


<?php 


// -------- MANIPULATE AFDAO TO YIELD OUTPUT INTO THIS FORM:
require_once('urm_secure/createActivity/createActivityFormContents.php');  //contents of the form.
?>

<div>
<input type="submit" name="createSubmit"
	
	value="<?php 
		if($activityType=="customActivity") echo "Create and add to my User Created Activities"; 
		else if($activityType=="activity") echo "Create and add to Activities";
		else {
		    $em="error: activityType not set";
			$_SESSION['errorMsg'] = $em;
			header('Location: errorPage.php');
			exit();
		}
		?>" 
	
	/>
</div>










</form>

<?php
if(defined('DEBUG')){
 echo "<hr/>Your activity type is : ".$activityType."<br/>";
 if(isset($surveyCategoryId)) echo "Your survey category id is: ".$surveyCategoryId.'<br/>';
}
?>

</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>