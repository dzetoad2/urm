<?phptry {    require_once 'urm_secure/functions.php';    require_once 'urm_secure/modProfileFunctions.php';    require_once 'urm_secure/validationFunctions.php';    if (!loggedin()) {        //echo "userarea but not loggedin!<br/>\n";        header("Location: login.php");        exit();    }    if (isset($_SESSION['userid'])) {        $userId = $_SESSION['userid'];    }    $errorLabel = '';// -------------------- IF THIS IS A FORM POST UPDATE... ---------------    if (isset($_POST['modProfile'])) {        if (!isset($_SESSION['username'])) {            //header("Location: login.php");            $errorMsg = "Error: Username not set, impossible to continue! <br/>";            throwMyExc($errorMsg);        }        $firstname = trim($_POST['firstname']);        $lastname = trim($_POST['lastname']);        $phone = trim($_POST['phone']);        if (!updateUserProfile($_SESSION['username'], $firstname, $lastname, $phone)) {            $errorLabel = "Update user profile failed.";        }        if ($errorLabel == '') {            $statusLabel = 'Profile has been updated'; //no longer use this            //redir to home.            $_SESSION['profileUpdateSuccessful'] = 'true';            header('Location: home.php');            exit();        }    } else if (isset($_POST['updatePassword'])) {        //only update password, as that is the button that was clicked.        //validate password.  at least 8 chars long. max 30 chars long, at least one letter, at least one digit.        if (!isset($_POST['password']) || $_POST['password'] == '') {            $errorLabel .= 'Please enter a password<br/>';        }        if ($errorLabel == '') {            if (!isset($_POST['password2']) || $_POST['password2'] != $_POST['password']) {                $errorLabel .= 'The passwords do not match<br/>';            }        }        if ($errorLabel == '') {            $pw = $_POST['password'];            $errorLabel .= isValidPassword($pw);        }        if ($errorLabel == '') {            //no errors, so update the pw.            if (TRUE == updatePasswordForUserAccount($userId, $pw)) {                $statusLabel = "Successfully updated password"; //no longer use this                //redir to home                $_SESSION['updatePasswordSuccessful'] = 'true';                header('Location: home.php');                exit();            } else {                $errorLabel .= "Failure updating password";            }        }    }//---------------------END IF UPDATE POST--------//no matter whether we post or not, we have to pull data from db and show it.    if (!$firstname = getParam($_SESSION['username'], "firstname")) {        $errorMsg = "Error: Error getting first name from db. fname is: " . $firstname . "<br/>";        throwMyExc($errorMsg);    }    if (!$lastname = getParam($_SESSION['username'], "lastname")) {        $errorMsg = "Error: Error getting last name from db. <br/>";        throwMyExc($errorMsg);    }    if (!$phone = getParam($_SESSION['username'], "phone")) {        $errorMsg = "Error: Error getting phone from db.   <br/>";        throwMyExc($errorMsg);    }    ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">    <html xmlns="http://www.w3.org/1999/xhtml">        <head>            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />            <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>            <link rel="stylesheet" type="text/css" href="css/styles.css" />            <link rel="stylesheet" type="text/css" href="css/formStyles.css" />            <title>Create / Modify Profile</title>        </head>        <body>            <?php// include the top header bar            include("includes/header.php");            ?>            <?php if (isset($errorLabel) && $errorLabel != '') { ?>                <h4 class="errorLabel"><?php echo $errorLabel; ?></h4>            <?php } ?>            <?php if (isset($statusLabel) && $statusLabel != '') { ?>                <h4 class="statusLabel"><?php echo $statusLabel; ?></h4>            <?php } ?>            <form   action="modProfile.php" method="post">                <div class=" inputunit row">                    <h3>Create / Modify Profile</h3>                     <fieldset class="inputBox">                        <legend>Profile</legend>                        <ul>                             <li>                                <label>First Name:</label><br/>                                  <input type="text" name="firstname" value="<?php if ($firstname != 'Not set') echo $firstname; ?>" />                            </li>                            <li>                                <label>Last Name:</label> <br/>                                <input type="text" name="lastname" value="<?php if ($lastname != 'Not set') echo $lastname; ?>"/>                            </li>                            <li>                                <label>Phone:</label>  <br/>                                <input type="text" name="phone" value="<?php if ($phone != 'Not set') echo $phone; ?>"/>                            </li>                        </ul>                    </fieldset>                </div>                <div class="row">                    <input type="submit" name="modProfile" class="surveyLinkButton linkButton" value="Update Profile" />                </div>            </form>            <br/><br/>            <form action="modProfile.php" method="post">                <div class="inputunit row">                     <h4>Change Password</h4>                    <fieldset class="inputBox">                        <ol>                            <li>                                 <label>Change Password to*:</label><br/>                                <input type="password" name="password" value="" /><br/>                            </li>                            <li>                                <label>Re-type Password:</label><br/>                                <input type="password" name="password2" value="" /><br/>                            </li>                        </ol>                    </fieldset>                </div>                <div class="row">                    <input type="submit" class="surveyLinkButton linkButton" name="updatePassword" value="Update Password" />                </div>            </form>            <div>                <p class="gray">                    * Passwords must be at least 8 characters long, include at least one letter and one number, and only be composed of alphanumerics and the following: !@#$%^&amp;*                 </p>            </div>            <div>                <p class="footerNav"><a href="home.php">Home</a></p>            </div>        </body>    </html>    <?php} catch (Exception $e) {    goErrorPage($e);}?>