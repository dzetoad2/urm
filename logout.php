<?php

session_start();

//destroy session
session_destroy();
//unset cookie
setcookie("username","",time()-2);     //invalidates cookie
header("Location: login.php");


//if(isset($_COOKIE['authtoken'])){
// 	 	setcookie("authtoken", "", time() - 3600); 
// 	 	unset($_COOKIE['authtoken']);
// 	 }
// 	 if(isset($_SESSION['authtoken'])){
// 	 	unset($_SESSION['authtoken']);
// 	 }
 	 //wipeOutAuthtoken($username);
