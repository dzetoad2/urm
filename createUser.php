<?php 
try{

require_once 'urm/functions.php';
require_once 'urm/validationFunctions.php'; 
 
if ( isset($_POST['register'])){

 $new_username = "";
 $new_password = "";
 $new_password2 = "";
 $errorLabel='';
 if(isset($_POST['new_username'])) {   $new_username = trim($_POST['new_username']);}         //is username set?
 if($new_username == ""){
//    echo "1";
 	$errorLabel.= 'Please enter a username.<br/>';
 }
 if(!isEmailAddress($new_username)){
 	$errorLabel.= 'Please enter a valid email address for the username.<br/>';
 }
 if(isset($_POST['new_password'])) { $new_password = trim($_POST['new_password']); }            //is 1st pw set?
 if( $new_password == ""){
 	$errorLabel.= 'Please enter your new password in the first box.<br/>';
 }
 if(isset($_POST['new_password2'])) { $new_password2 = trim($_POST['new_password2']);   }           //is 2nd password set?
 if($new_password2 == ""){
 	$errorLabel.= 'Please repeat your new password in the second box.';
 }
 if($new_password != $new_password2){        //do passwords match one another? bx1 and bx2
 	$errorLabel.= 'The second password does not match the first.';
 }
 
 $errorLabel.= isValidPassword($_POST['new_password']);
 
if(userInDb($new_username)){                //does user already exist in db?
	 	$errorLabel.= 'User already exists in database. Please choose another.<br/>';
 }
 
 require_once 'urm/securimage/securimage.php';  //$_SERVER['DOCUMENT_ROOT'] .
 $securimage = new Securimage();
 
if($errorLabel==''){ 
 if ($securimage->check($_POST['captcha_code']) == false) {
  // the code was incorrect
  // you should handle the error so that the form processor doesn't continue
  // or you can use the following code if there is no validation or you do not know how
  $errorLabel .= 'The security code entered was incorrect.<br />';
   //Please go <a href='javascript:history.go(-1)'>back</a> and t r y again.";
 }
}

if($errorLabel==''){ 
 if(createNewUserAccount($new_username,$new_password)){
 	// true = success
 	$statusLabel= "Account creation successful!";  //not used now
 	//redirect to login.
 	$_SESSION['createUserSuccessful'] = 'true';
 	header('Location: login.php');
 }else{
 	//false = failure
 	$errorLabel="System failed to create this account. Please contact the administrator for help.";
 }
}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />


<title>Registration</title>
</head>

<body>
<div class="header"> 
 <a href="login.php">Log in</a>
</div>
<hr/>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
<h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }
if(isset($statusLabel) && $statusLabel!=''){
?>
<h4 class="statusLabel"><?php echo $statusLabel;?></h4>
<?php }?>

<form action="createUser.php" method="post">
 <div class="inputunit row">
  <h3>Registration</h3>
  <fieldset class="inputBox">
  <legend><span>Create User</span></legend>
  <ol>
   <li>
    <label>Username (valid email address):</label><br/>
    <input type="text" name="new_username" />
   </li>
   <li>
    <label>Password:</label><br/>
    <input type="password" name="new_password" />
   </li>
   <li>
    <label>Password (confirm):</label><br/>
    <input type="password" name="new_password2" />
   </li>
   <li>
    <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
   </li>
   <li>
   <label>Security Code (case-insensitive):</label><br/>
    <input type="text" name="captcha_code" size="10" maxlength="6" />
    <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
   </li>
  </ol>
  </fieldset>
 </div>
 <div> 
  <input type="submit" name="register" value="Register" />
 </div>
</form>
<hr/>  
<div>
<h4>Instructions:</h4>
<p>
Passwords must be 8 characters long and include at least one letter and one number. 
You must enter the characters displayed in the space below them then click on “register” to register your username. 
If you cannot read the characters, you may select a different image. After you have successfully created your Username and Password a confirming message 
will appear on the screen. Save your access information for future access. To access the survey, click on Log in and enter your access information and click on Log In.
</p>
</div>

</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>