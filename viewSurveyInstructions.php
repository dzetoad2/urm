<?php  try{//----------required on every secure page----------------require_once 'urm_secure/functions.php';require_once 'urm_secure/sessionStateFunctions.php';if(!loggedin()){  //echo "userarea but not loggedin!<br/>\n";  header("Location: login.php");  exit();}if(!isset($_SESSION['userid'])){	$errorMsg = "userid not set";	throwMyExc($errorMsg);}$userId = $_SESSION['userid'];// -------------end required on every secure page -------   savePostAndSessionVars($userId,$_POST,$_SESSION,'viewSurveyInstructions.php');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Survey Instructions</title><link rel="stylesheet" type="text/css" href="css/reset-min.css"/><link rel="stylesheet" type="text/css" href="css/styles.css" /></head><body><?php// include the top header barinclude("includes/header.php");?><h3>Survey Instructions</h3><div> <h5>Review the Introduction to each of the survey sections.</h5> <p>This provides a quick overview of the types of activities included. Pay particular attention to clarification of how activities in any section may differ from activities described in other sections.</p><h5>Review the Tasks Common to All Activities.</h5><p>These tasks are performed in conjunction with any activity in which the first activity component is "Refer to Tasks Common to All Activities".  Each survey has a unique list of tasks common to all activities list.It is important to note that the time to perform these tasks is included with the total time reported for the activity.</p><h5>Review each activity.</h5><p>   1. Click on each activity title to review its description and critical components.    If you do not perform the activity essentially as described, click on the button that allows you to skip the activity and move to the next.</p><p>   2. If you perform this activity in this facility, report each of the patient populations       to which it is provided. For each patient population you will be asked if you have determined a time standard for this activity       in this facility.If you do, report that time standard and identify if the methodology for determining the time standard.  Choose either        (1) actual measurement, (2) determined by expert opinion or (3) methodology is unknown. Finally submit this information by       clicking <i>submit survey answers</i>.</p><p> When each section of the survey is completed, you will have an opportunity to describe any activities performed in your facility but not listed in the survey.  These are referred to  as "User Created Activities".  You may add them by clicking on "Create a User Created Activity".   You will be asked to provide a name, a brief 1-2 sentence description and a few critical elements to further clarify the activity.  This activity will be added to  the list of activities for this section as a User Created Activity.  After it has been added, select it from the list of User Created Activities and provide the same information as described  in step 2 above. When your User Created Activity has been successfully submitted, a green check will appear on the completion status column. You may add as many user created procedures as you like in  each survey section.</p><p> Complete all surveys related to your facility(s) then submit all of your data.</p><p>On the next screen you can select the specific survey(s) you wish to complete for each facility you identified. Surveys that have been completed and submitted are indicated by a green check mark in the completion status column.  Select the desired survey by hovering your mouse over the facility and clicking on it.</p><p> <img src="images/warning_notice.JPG" alt="warning notice" height="21" width="21" /> <span><b>Warning:  Do not use the browser's back button while in the survey system.</b></span></p><hr/><p style="text-align:center;margin-top:20px;"><a href="surveyHome.php" class="button_link">Start survey now</a></p></div></body></html><?php }catch(Exception $e){  goErrorPage($e);}?>