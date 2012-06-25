

<?php
 require_once('urm_secure/constants.php');
 require_once('urm_secure/validationFunctions.php');
 $today = getdate();
 
 //==========IF ITS LATER THAN JUNE 22 2012, LOGIN IS NO LONGER POSSIBLE. ==========
if($today['year'] >= constant('endingYear') && 
   $today['mon'] >= constant('endingMonth')    && 
   $today['mday'] > constant('endingDay') &&
   isLANIP($ip)=== false){
	//do nothing
	//echo "DOING NOTHING!";
	//exit();
   	
}else{
	//echo 'HEADER TO LOGIN!!!!!';
	header('Location: login.php');
	exit();
}
 
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />

<!--<link rel="stylesheet" type="text/css" href="css/textcontent.css" />-->

<title>Login (disabled)</title>
</head>

<body>
<div class="header"> 
<?php if(isset($statusLabel) && $statusLabel!=''){ ?>
<h4 id="statusLabel" class="statusLabel"><?php echo $statusLabel; ?></h4>
<?php }?>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
<h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>
</div>

<form action="login.php" method="post">
 <div class="inputunit row">
 <h3>URM Login Page (disabled)</h3>
 <p>
 <label class="grayText">The URM system is now disabled. Thank you for visiting.</label>
 </p>
  </div>
  
</form>
 
<hr/>
 

</body>
</html>