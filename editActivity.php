<?php
try{

require_once 'urm/functions.php';
require_once 'urm/activityFunctions.php';
require_once 'urm/editActivityFunctions.php';
require_once 'urm/sessionStateFunctions.php';

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
	
//	$activityId = $_POST['activityId'];
//	$activityType = "activity";
//	$_SESSION['activityId'] = $activityId;
//	unset($_SESSION['customActivityId']);
} else if(isset($_SESSION['activityId'])){
	$em='sessionvar activityid found. not supported!';
	
	
	
	
	
	
	
	
	
	throwMyExc($em);
//    $activityId = $_SESSION['activityId'];
//	unset($_SESSION['customActivityId']);
}
if(isset($_POST['customActivityId']) ){
	$customActivityId = $_POST['customActivityId'];
	$activityType = "customActivity";
	$_SESSION['customActivityId'] = $customActivityId;
//	unset($_SESSION['activityId']);
} else if(isset($_SESSION['customActivityId'])){
	$customActivityId = $_SESSION['customActivityId'];
//	unset($_SESSION['activityId']);
}
if($activityType == "activity" && $userId != 1){
	$em="Non-admin users may not edit an Activity.";
	throwMyExc($em);
}
$activityData = array();



//savePostAndSessionVars($userId,$_POST,$_SESSION,"editActivity.php");


//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------
if ( !isset($_POST['submitEdit'])){
// ------ not a editpost, so now get the data from activityId or customActiv-id. -------
if(isset($activityId)){
 if(!$activityData = getActivityData($activityId)){
	$em="Error in getActivityData()";
	
	throwMyExc($em);
 }
}else if(isset($customActivityId)){
   if(!$activityData = getCustomActivityData($userId, $customActivityId)){
	$em="Error in getCustomActivityData()";
	throwMyExc($em);
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
	if($errorLabel==''){   //if still no errors:
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
				echo ("Error - Unable to update activity (or customActivity) entry to the database.");
			}
		if(isset($activityId)){
			//$statusLabel = "Success updating activity. <br/>";
			$_SESSION['updateActivitySuccess'] = 'true';
			header('Location: activityCategories.php');
			exit();
		}
		else if(isset($customActivityId)){
			//$statusLabel = "Success updating user created activity. <br/>";
			$_SESSION['updateCustomActivitySuccess'] = 'true';
			header('Location: activityCategories.php');
			exit();
		}
		else{
		 	$em="Error: Neither activityid nor customactivityid are set";
		 	throwMyExc($em);
		}
	}
}	 

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
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript">                                         
$(document).ready(function() {
	   $("#descr").elastic();
	 });
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
<a href="activityCategories.php">Back to Activity Categories</a>
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