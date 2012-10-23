

<?php
require_once 'urm_secure/functions.php';
require_once 'urm_secure/sessionStateFunctions.php';

if (loggedin()) {

    header("Location: home.php");
    exit();
}
$errorLabel = '';
$statusLabel = '';

if (isset($_SESSION['resetEmailSent']) && $_SESSION['resetEmailSent'] == 'true') {
    $statusLabel = 'Success sending password reset email to your user account email address';
    unset($_SESSION['resetEmailSent']);
}

if (isset($_SESSION['createUserSuccessful']) && $_SESSION['createUserSuccessful'] == 'true') {
    unset($_SESSION['createUserSuccessful']);
    $statusLabel .= 'Account creation successful';
} else if (isset($_POST['login'])) {  //the submit itself
    //get data
    //$username = "none"; $password="none"; $rememberme = "none";
    if (isset($_POST['username']))
        $username = trim($_POST['username']);
    if (isset($_POST['password']))
        $password = trim($_POST['password']);
    if (isset($_POST['rememberme']))
        $rememberme = $_POST['rememberme']; else
        $rememberme = "";
    //echo "un: ".$username.", pw: ".$password.", rememberme: ".$rememberme."<br/>";
    if (!isset($username) || $username == "") {
        $errorLabel .= "Please type your username<br/>";
    }
    if (!isset($password) || $password == "") {
        $errorLabel .= "Please type your password<br/>";
    }
    if ($errorLabel == '') {
        //now we know un and pw are both okay and not blank.

        $username = cleanStrForDb($username);

        $login = mysql_queryCustom("SELECT * FROM user WHERE username='$username'   ");           //check un/pw against db.
        if (!isset($login) || $login === FALSE) {
            $errorMsg = "Error checking login against database";
            throwMyExc($errorMsg);
        }
        $row = mysql_fetch_assoc($login);   //associative. this is first row of the result.
        $db_password = $row['pwhash'];
        $userId = $row['id'];
        $pwhash = constant("SALT") . sha1($password . constant("SALT"));
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($pwhash == $db_password) {         // md5($password)
            $loginok = TRUE;
        } else {
            $loginok = FALSE;
        }
        if ($loginok == TRUE) {
            $authtoken = createAuthToken($username);
            if ($rememberme == "on") {  //the checkbox thing in html post.
                setcookie("username", $username, time() + 7200);
                setcookie("authtoken", $authtoken, time() + 7200);            //cookie has expire time set here.
            }
            $_SESSION['username'] = $username;
            $_SESSION['authtoken'] = $authtoken;
            $_SESSION['userid'] = $row['id'];
//		    $curr_date = new DateTime("now");
//		    $expire_date = $curr_date->add(new DateInterval('P2D'));
//		    $expire_timestamp = $expire_date->getTimestamp();
            //store cookie and expiry in userSession table.
            //------------------------------------------------
            $t = time();

            $authtoken = cleanStrForDb($authtoken);

            $result = mysql_queryCustom("UPDATE user  set  authtoken='" . $authtoken . "' WHERE   username='$username'  ");           //update authtoken in db.
            if ($result === FALSE) {
                $errorMsg = "Error updating authtoken for " . $username . ", please contact the adinistrator.";
                throwMyExc($errorMsg);
                exit();
            }
            //------------------------------------------------
            //$row = mysql_fetch_assoc();   //associative. this is first row of the result.
            $db_password = $row['pwhash'];  //REPEAT, NOT NEEDED
            //finally, relocate to user area.
            loadState($userId);
            $_SESSION['userid'] = $userId;
//	 		 ('login.php:  done loadstate, nothing happened, about to redir to home.php');
            logLoginSuccess($username, $password, $ip);
            header("Location: home.php");
            //echo 'login ok true:  header go to home.php here.';
            exit();       //some browseres dont respect the header redirects so exit here.
        } else {
            $errorLabel .= "The login was unsuccessful.";

            logLoginError($username, $password, $ip);
        }
        //if username and pw  
    }
} // end if login submit 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/formStyles.css" />

        <!--<link rel="stylesheet" type="text/css" href="css/textcontent.css" />-->

        <title>Login</title>
    </head>

    <body>
        <?php
//	echo "ip is: ".$ip."<br/>";
        ?>
        <div class="header"> 
            <?php if (isset($statusLabel) && $statusLabel != '') { ?>
                <h4 id="statusLabel" class="statusLabel"><?php echo $statusLabel; ?></h4>
            <?php } ?>
            <?php if (isset($errorLabel) && $errorLabel != '') { ?>
                <h4 class="errorLabel"><?php echo $errorLabel; ?></h4>
                <?php
            }
            echo "Your IP is: " . $ip . "<br/>";
            ?>
        </div>

        <form action="login.php" method="post">
            <div class="inputunit row">
                <h3>URM Login Page</h3>
                <fieldset class="inputBox">
                    <legend><span>Login</span></legend>
                    <ol>
                        <li>
                            <label>Username (email address):</label><br/>
                            <input type="text" name="username" />
                            <span class="gray italic">Do Not Use AARC Member Username and Password</span>
                        </li>
                        <li>
                            <label>Password:</label><br/>
                            <input type="password" name="password" />
                        </li>
                        <li>
                            <input type="checkbox" name="rememberme" />Remember me<br/>
                        </li>
                    </ol>
                </fieldset>
            </div>
            <div>
                <input type="submit" name="login" value="Log in" />
            </div>
        </form>
        <div> 
            <a href="resetPassword.php">Forgot Password?</a> |
            <label class="grayText">Register as a New User (Registraion no longer available)</label><br/>
            <label class="grayText">The survey is available only to those who have not yet completed their surveys until Friday June 22, 2012</label>
        </div>
        <hr/>
        <div>
            <h4>Instructions:</h4>
            <p>If you have previously registered, 
                provide your access information to enter the survey again. 


            </p>
            <p>
                <img src="images/warning_notice.JPG" alt="warning notice" height="21" width="21" />
                <span><b>Warning:  Do not use the browser's back button while in the survey system.  Javascript must be enabled.</b></span>
            </p>

            <p>
                If you encounter difficulty in accessing the survey, please contact:
            </p>
            <p> 
                Email: <a   href="mailto:dubbs@aarc.org" >URM Support</a>
            </p>
            <p>

            </p>
        </div>

    </body>
</html>