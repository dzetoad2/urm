<?php 
try{

require_once 'urm_secure/functions.php';
 


 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />


<title>Registration (now disabled)</title>
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
  <h3>Registration (now disabled)</h3>
  <p>
  <label class="grayText">The survey is available only to those who have not yet completed their surveys until Friday June 22, 2012</label>
  </p>
 </div>
  
</form>
<hr/>  
 

</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>