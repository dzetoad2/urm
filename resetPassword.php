

<?php
try {

    require_once 'urm_secure/functions.php';
    require_once 'urm_secure/validationFunctions.php';
    require_once 'urm_secure/resetPasswordFunctions.php';

    if (isset($_POST['resetPasswordSubmit'])) {
        $errorLabel = "";
        $statusLabel = "";
        $username = trim($_POST['username']);
        $username2 = trim($_POST['username2']);
//0. check that both fields have content.
        if ($username == '') {
            $errorLabel.='Please enter your username (email address)<br/>';
        }
        if ($username2 == '') {
            $errorLabel.='Please enter your username in the second box<br/>';
        }
//1. check that both fields are sam.e username and username2.
        if ($username != $username2) {
            $errorLabel.='Please make sure both usernames match<br/>';
        }
//2. check that username is an actual email address. use filter.  
        if (!isEmailAddress($username)) {
            $errorLabel.='Please make sure username is a valid email address<br/>';
        }
//3. check that the captcha is valid.  use the google captcha.

        require_once 'urm/securimage/securimage.php';  //$_SERVER['DOCUMENT_ROOT'] .
        $securimage = new Securimage();

        if ($errorLabel == '') {
            if ($securimage->check($_POST['captcha_code']) == false) {
                // the code was incorrect
                // you should handle the error so that the form processor doesn't continue
                // or you can use the following code if there is no validation or you do not know how
                sleep(4);
                $errorLabel .= 'The security code entered was incorrect.<br />';
                //Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
            }
        }

//4. check that username is an actual user in our database.
        if ($errorLabel == "") {
            if (!userInDb($username)) {
                $errorLabel.='User not found in database. Please verify the username<br/>';
            }
        }

//5.   Send the email.  If the email fails, do errorlabel.   
// 	If the email succeeds, then do the database update on password hash.
        if ($errorLabel == '') {
            $msg = sendPasswordResetEmail($username);
            $o = strstr($msg, 'Successful');
            if ($o == false) {
                $errorLabel.=$o;
            } else {
                $statusLabel.=$o;
            }

            if ($errorLabel == '') {
                //$statusLabel='Success sending password reset email to your user account email address';// no use anymore

                $_SESSION['resetEmailSent'] = 'true';
                header('Location: login.php');
                exit();
            }
        }
    }
    ?>



    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">


        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

            <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
            <link rel="stylesheet" type="text/css" href="css/styles.css" />
            <link rel="stylesheet" type="text/css" href="css/formStyles.css" />

            <title>Reset Password</title>
        </head>

        <body>
            <div class="header"><a href="login.php">Login</a>
            </div>
            <hr/>
            <?php if (isset($statusLabel) && $statusLabel != '') { ?>
                <h4 class="statusLabel"><?php echo $statusLabel; ?></h4>
            <?php } ?>

            <?php if (isset($errorLabel) && $errorLabel != '') { ?>
                <h4 class="errorLabel"><?php echo $errorLabel; ?></h4>
            <?php } ?>
            <form action="resetPassword.php" method="post">
                <div class="inputunit row">
                    <h3>Reset Password</h3>
                    <fieldset class="inputBox">
                        <ul>
                            <li>
                                <label>Username:</label><br/>
                                <input type="text" name="username" />
                            </li>
                            <li>
                                <label>Re-type Username:</label><br/>
                                <input type="text" name="username2" /><p/>
                            </li>
                            <li>
                                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /><br/>
                            </li>
                            <li>
                                <input type="text" name="captcha_code" size="10" maxlength="6" />
                                <a href="#" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a><br/>
                            </li>
                        </ul>
                    </fieldset>
                </div>
                <div class="row">
                    <input type="submit" name="resetPasswordSubmit" value="Reset My Password" />
                </div>
            </form>


        </body>
    </html>
    <?php
} catch (Exception $e) {
    goErrorPage($e);
}?>