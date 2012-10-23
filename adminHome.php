<?php
//----------required on every secure page----------------
try {

    require_once 'urm_secure/functions.php';
    require_once 'urm_secure/facilityFunctions.php';
    require_once 'urm_secure/facilityTypeFunctions.php';
    require_once 'urm_secure/modProfileFunctions.php';
    require_once 'urm_secure/breadCrumbFunctions.php';

    if (!loggedinAdmin()) {
        //echo "userarea but not loggedin!<br/>\n";
        header("Location: login.php");
        exit();
    }
//----------end required on every secure page----------------
    if (!isset($_SESSION['userid'])) {
        $errorMsg = 'userid not set';
        throwMyExc($errorMsg);
    }
    $userId = $_SESSION['userid'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Admin Pages</title>
            <link rel="stylesheet" type="text/css" href="css/styles.css" />
            <link rel="stylesheet" type="text/css" href="css/tables.css" />
        </head>
        <body>
            <?php
            echo "You are logged onto the URM system as: " . $_SESSION['username'] . "<br/>";


            if (defined('DEBUG')) {
                echo "You are logged onto the URM system as: " . $_SESSION['username'] . ", userid: " . $userId . "<br/>";
                echo "Your session expires at: " . getExpiryDate($_SESSION['username']);
                echo "<br/>Your first name is: " . getParam($_SESSION['username'], "firstname");
                echo "<br/>Your first name is: " . getParam($_SESSION['username'], "lastname");
                echo "<br/>Your phone is: " . getParam($_SESSION['username'], "phone");
            }

            $sc1name = getSurveyCategoryName(1);
            $sc2name = getSurveyCategoryName(2);
            $sc3name = getSurveyCategoryName(3);
            $sc4name = getSurveyCategoryName(4);
            $sc5name = getSurveyCategoryName(5);
            $sc6name = getSurveyCategoryName(6);
            $sc7name = getSurveyCategoryName(7);
            ?>
            <a href="logout.php">Log out</a> |
            <a href="home.php">Home</a>
            <hr/>
            <h3>Admin Pages:</h3>
            <a href="surveyStats1.php">Stats page 1:  Table of users and survey response counts</a> <br/> 
            <a href="surveyStats2.php">Stats page 2:  Total surveys completed, sorted by survey type</a>  <br/>
            <a href="surveyStats3a.php">Stats page 3a: Incomplete surveys, sorted by survey type (both normal and custom)</a>  <br/>
            <a href="surveyStats3b.php">Stats page 3b: Incomplete surveys, sorted by user, for normal facilities</a>  <br/>
            <a href="surveyStats3c.php">Stats page 3c: Incomplete surveys, sorted by user, for custom facilities</a>  <br/>
            <a href="surveyStats5a.php">Stats page 5a: Complete surveys, sorted by survey type (both normal and custom)</a>  <br/>
            <a href="surveyStats5b.php">Stats page 5b: Complete surveys, sorted by user, for normal facilities</a>  <br/>
            <a href="surveyStats5c.php">Stats page 5c: Complete surveys, sorted by user, for custom facilities</a>  <br/>

            <a href="surveyStats6.php?surveyCategoryId=1">Stats page 6-1: Report: User Created Activities, for Survey Category 1, <?php echo $sc1name; ?></a>  <br/>
            <a href="surveyStats6.php?surveyCategoryId=2">Stats page 6-2: Report: User Created Activities, for Survey Category 2, <?php echo $sc2name; ?></a>  <br/>
            <a href="surveyStats6.php?surveyCategoryId=3">Stats page 6-3: Report: User Created Activities, for Survey Category 3, <?php echo $sc3name; ?></a>  <br/>
            <a href="surveyStats6.php?surveyCategoryId=4">Stats page 6-4: Report: User Created Activities, for Survey Category 4, <?php echo $sc4name; ?></a>  <br/>
            <a href="surveyStats6.php?surveyCategoryId=5">Stats page 6-5: Report: User Created Activities, for Survey Category 5, <?php echo $sc5name; ?></a>  <br/>
            <a href="surveyStats6.php?surveyCategoryId=6">Stats page 6-6: Report: User Created Activities, for Survey Category 6, <?php echo $sc6name; ?></a>  <br/>
            <a href="surveyStats6.php?surveyCategoryId=7">Stats page 6-7: Report: User Created Activities, for Survey Category 7, <?php echo $sc7name; ?></a>  <br/>



            <!--  <a href="surveyStats4.php">Stats page 4: Both incomplete and complete surveys</a>  <br/> -->



        </body>
    </html>
    <?php
} catch (Exception $e) {
    goErrorPage($e);
}?>