<?php

session_start();

//destroy session
session_destroy();
//unset cookie
setcookie("username","",time()-2);     //invalidates
header("Location: login.php");