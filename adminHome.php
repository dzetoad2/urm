<?php 
//----------required on every secure page----------------
try{
	
require_once 'urm/functions.php';
require_once 'urm/facilityFunctions.php';
require_once 'urm/facilityTypeFunctions.php';
require_once 'urm/modProfileFunctions.php';

if(!loggedin()){
  //echo "userarea but not loggedin!<br/>\n";
  header("Location: login.php");
  exit();
}
//----------end required on every secure page----------------
if(!isset($_SESSION['userid'])){
	$errorMsg='userid not set';
	$_SESSION['errorMsg'] = $errorMsg;
	header('Location: errorPage.php');
	exit();
}
$userId = $_SESSION['userid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin HomePage</title>
</head>
<body>
<?php
  echo "You are logged onto the URM system as: ". $_SESSION['username']."<br/>";
  
  
if(defined('DEBUG')){
  echo "You are logged onto the URM system as: ". $_SESSION['username'].", userid: ".$userId."<br/>";
  echo "Your session expires at: ". getExpiryDate($_SESSION['username']);
	   echo "<br/>Your first name is: ". getParam($_SESSION['username'],"firstname");
	   echo "<br/>Your first name is: ". getParam($_SESSION['username'],"lastname");
	   echo "<br/>Your phone is: ". getParam($_SESSION['username'],"phone");
}

?>
<a href="logout.php">Log out</a> |
<a href="home.php">Home</a>
<hr/>
<h3>Admin pages:</h3>
<a href="surveyStats.php">See table of users and survey response counts</a> | 
 
</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>