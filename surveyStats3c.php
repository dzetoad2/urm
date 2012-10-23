<?php
//----------required on every secure page----------------
try {

    require_once 'urm_secure/functions.php';
    require_once 'urm_secure/surveyStatsFunctions.php';
    require_once 'urm_secure/sessionStateFunctions.php';

    if (!loggedinAdmin()) {
        //echo "userarea but not loggedin!<br/>\n";
        header("Location: login.php");
        exit();
    }
//----------end required on every secure page----------------
    if (!isset($_SESSION['userid'])) {
        $errorMsg = "userid not set";
    }
    $userId = $_SESSION['userid'];
    if ($userId != 1) {
        $errorMsg = "Access denied:  Non admins may not access Admin function pages";
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
            <link rel="stylesheet" type="text/css" href="css/styles.css" />
            <link rel="stylesheet" type="text/css" href="css/tables.css" />

            <title>Incomplete surveys, sorted by user, for custom facilities</title>
        </head>
        <body>
            <?php
            echo "You are logged onto the UFM system as: " . $_SESSION['username'] . "<br/>";
            ?>
            <a href="adminHome.php">Back to Admin HomePage</a>

            <hr/>
            <?php
            if (defined('DEBUG')) {
                echo "Your session expires at: " . getExpiryDate($_SESSION['username']);
                echo "<br/>Your first name is: " . getParam($_SESSION['username'], "firstname");
                echo "<br/>Your first name is: " . getParam($_SESSION['username'], "lastname");
                echo "<br/>Your phone is: " . getParam($_SESSION['username'], "phone");
                echo '<hr/>';
            }
            ?>



            <div>
                <h3>Survey stats 3:  Incomplete surveys, sorted by user, for custom facilities</h3>

                <form id="chooseAction" name="chooseAction" action="myFacilities.php"
                      method="post">
                    <input type="hidden" id="facilityTypeId" name="facilityTypeId" value="nothing" /></form>

                <?php
                $rows = getStats3RowsHtml("user", "custom");
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Survey Type</th>
                            <th>User name</th>
                            <th>Facility Name</th>
                            <th>State</th>
                            <th>Custom Facility?</th>
                            <th>Answered</th>
                            <th>Out of total questions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        echo $rows;
                        ?>
                    </tbody>
                </table>


            </div>
        </body>
    </html>
    <?php
} catch (Exception $e) {
    goErrorPage($e);
}?>