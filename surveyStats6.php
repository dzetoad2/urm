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
    if (!isset($_GET['surveyCategoryId'])) {
        throw new Exception('surveystats6:  no surveyCategoryId set in GET');
    }
    $surveyCategoryId = $_GET['surveyCategoryId'];
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
            <link rel="stylesheet" type="text/css" href="css/styles.css" />
            <link rel="stylesheet" type="text/css" href="css/tables.css" />

            <title>Survey stats 6</title>
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
                <h3>Stats page 6: Report: User Created Activities, organized by Survey Category</h3>

                <form id="chooseAction" name="chooseAction" action="myFacilities.php"
                      method="post">
                    <input type="hidden" id="facilityTypeId" name="facilityTypeId" value="nothing" /></form>

                <?php
                $rows = getStats6RowsHtml($userId, $surveyCategoryId);
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Survey Category Title</th>
                            <th>Title</th> 
                            <th>Description</th>
                            <th>Is Performed Adult</th>
                            <th>Is Performed Pediatric</th>
                            <th>Is Performed Natal</th>
                            <th>Has Time Standard Adult</th>
                            <th>Has Time Standard Pediatric</th>
                            <th>Has Time Standard Natal</th>
                            <th>Duration Adult</th>
                            <th>Duration Pediatric</th>
                            <th>Duration Natal</th>
                            <th>Volume Adult</th>
                            <th>Volume Pediatric</th>
                            <th>Volume Natal</th>
                            <th>Methodology Adult</th>
                            <th>Methodology Pediatric</th>
                            <th>Methodology Natal</th>
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