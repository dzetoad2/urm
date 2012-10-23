<?php
require_once 'urm_secure/functions.php';


if (!loggedin()) {
    //echo "userarea but not loggedin!<br/>\n";
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['username']) || !isset($_SESSION['userid'])) {
    error_log("Error: username or user id not set");
}
if (!isset($_SESSION['errorMsg'])) {
    $errorMsg = "errorpage: An unknown error has occurred (errorMsg was not set!)";
    error_log($errorMsg);
} else {
    $errorMsg = $_SESSION['errorMsg'];
    //error_log($e) or die('could not log error');
}
//$errorMsg .= '<br/> HTTP_REFERER: '.$_SERVER['HTTP_REFERER'] . ' - DEBUG';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Error</title>
        <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/tables.css" />
        <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>

    </head>
    <body>
        <div class="header"> 
            <?php echo "You are logged in as: " . $_SESSION['username'] . '<br/>';
            ?>
            <a href="logout.php">Log out</a> | 
            <a href="home.php">Home</a>
        </div>
        <hr/>
        <h3>Error:</h3>
        <div>
            <?php ?>

            <?php
            if (defined('debug')) {
                if (isset($errorMsg)) {
                    ?>
                    <h4 class="errorLabel"><?php echo $errorMsg; ?></h4>
                    <?php
                }//if set errormsg
            }//if defined debug
            else {
                ?>
                <h4 class="errorLabel">An error has occurred. You may go to the home page and proceed to try again. 
                    If this inhibits you from completing the survey,
                    please contact the system administrator (dubbs@aarc.org).</h4>
            <?php } ?>
        </div> 
        <?php
        if (defined('DEBUG')) {
            echo '<hr/><div>';
            if (isset($userFacilityId))
                echo " userFacilityId (posted and now put in Session):  " . $userFacilityId . '<br/>';
            if (isset($customFacilityId))
                echo " customFacilityId (posted and now put in Session):  " . $customFacilityId . '<br/>';
            if (isset($surveyCategoryId))
                echo " surveyCategoryId posted:  " . $customFacilityId . '<br/>';
            echo '</div>';
        }
        ?>
    </body>
</html>
