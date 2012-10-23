<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Activity</title>
        <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
        <link rel="stylesheet" type="text/css" href="css/tables.css" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/textcontent.css" />
        <link rel="stylesheet" type="text/css" href="css/formStyles.css" />
        <link rel="stylesheet" type="text/css" href="css/animatedForm.css" />

        <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="js/activityJs.js"></script>

    </head>
    <body>

        <div class="header">
            <h2><a href="home.php" title="Survey home">Uniform Reporting Manual Survey Home</a></h2>

            <p class="header_utilities"><i>You are logged in as: dzetoad2@gmail.com</i>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="header_link" href="logout.php" title="Log out of this survey"><b>Log out</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.aarc.org/" title="AARC Home"><b>AARC.org</b></a></p>
        </div>






        <h3>Activity: 2411 Small Particle Aerosol Generator (SPAG) System - Subsequent</h3>

        <div class='centerDiv'>



            <button class="linkButton" onclick="parent.location = 'activities.php'" ><span>Back to Activities</span></button>



        </div>


        <div class="content"   >
            Definition: The periodic evaluation of the SPAG system for proper function and of patient response to therapy.<br />
            <br />
            Components:<br />
            1. Refer to "Tasks Common to All Activities."<br />
            <br />
            2. Assess the SPAG for continuous aerosol delivery and proper function. Check for continuous nebulization and gas flow.<br />
            <br />
            3. Evaluate patient response (e.g., breath sounds, cough, sputum production and adverse reactions) and modify therapy as necessary.<br />
            <br />
            Note: This activity applies both to spontaneously breathing and mechanically ventilated patients.  Although setup is different, the techniques are of similar complexity and require similar time.<br/>            </div>








        <form id="submitSurveyForm" name="submitSurveyForm" action="activity.php" method="post">
            <!-- echo form data here --> 
            <!--form declaration (<form... line) must be defined before this script component is included. -->
<!-- <input class="button" type="submit" id="submitSurveyButton" name="submitSurveyButton" value="Submit survey answer" />-->

            <button class="clearer button"  id="skipButton" name="skipButton" >Don't perform - Skip this item</button>          
            <!-- type="submit"-->

            <input type="hidden" id="hiddenSubmitId" name="hiddenSubmitName" value="abc"/>
            <br/>
            <h4>Please input the following:</h4>


            <div id="populationBoxes"> 
                <!-- ======================= ADULTS ================================-->

                <fieldset class="inputBox clearer">
                    <legend><span>For Adults:</span></legend>
                    <div class="radio" id="doYouPerformAdult">
                        <fieldset>
                            <legend><span>Do you perform this procedure?</span></legend>
                            <span><input type="radio" name="isPerformedAdult" id="isPerformedAdultYes" value="yes"     /><label for="isPerformedAdultYes">Yes</label></span>
                            <span><input type="radio" name="isPerformedAdult" id="isPerformedAdultNo" value="no"  /><label for="isPerformedAdultNo">No</label></span>
                        </fieldset>
                    </div>


                    <div class="radio startHidden" id="timeStandardAdult">
                        <fieldset>
                            <legend><span>Do you have a time standard for this?</span></legend>
                            <span><input type="radio" name="hasTimestandardAdult" id="hasTimestandardAdultYes" value="yes"    /><label for="hasTimestandardAdultYes">Yes</label></span>
                            <span><input type="radio" name="hasTimestandardAdult" id="hasTimestandardAdultNo" value="no"     /><label for="hasTimestandardAdultNo">No</label></span>
                        </fieldset>
                    </div>
                    <div class="textInput startHidden" id="durVolAdult">
                        <fieldset>

                            <ol>
                                <li>
                                    <label>Duration (mins):</label><br/>
                                    <input type="text" class="smallfield" id="durationAdult" name="durationAdult" value=""/>
                                </li>

                            </ol>

                        </fieldset>
                    </div>
                    <div class="radio startHidden" id="methodologyAdult">
                        <fieldset>
                            <legend><span>Methodology:</span></legend>
                            <input type="radio" name="methodologyAdult" id="methodologyAdultMeasured" value="measured"  /><label for="methodologyAdultMeasured">Measured</label>
                            <input type="radio" name="methodologyAdult" id="methodologyAdultExpert_Opinion" value="expert_opinion"  /><label for="methodologyAdultExpert_Opinion">Expert Opinion</label>
                            <input type="radio" name="methodologyAdult" id="methodologyAdultUnknown" value="unknown"  /><label for="methodologyAdultUnknown">Unknown</label>
                        </fieldset>
                    </div>
                </fieldset>


                <br/>


                <fieldset class="inputBox">
                    <legend><span>For Pediatrics</span></legend>
                    <div class="radio" id="doYouPerformPediatric">
                        <fieldset> 
                            <legend><span>Do you perform this procedure?</span></legend>
                            <span><input type="radio" name="isPerformedPediatric" id="isPerformedPediatricYes" value="yes"  /><label for="isPerformedPediatricYes" >Yes</label> </span>
                            <span><input type="radio" name="isPerformedPediatric" id="isPerformedPediatricNo" value="no"  /><label for="isPerformedPediatricNo">No</label></span>
                        </fieldset>
                    </div>
                    <div class="radio startHidden" id="timeStandardPediatric">
                        <fieldset>
                            <legend><span>Do you have a time standard for this?</span></legend>
                            <span><input type="radio" name="hasTimestandardPediatric" id="hasTimestandardPediatricYes" value="yes"  /><label for="hasTimestandardPediatricYes" >Yes</label></span>
                            <span><input type="radio" name="hasTimestandardPediatric" id="hasTimestandardPediatricNo" value="no"    /><label for="hasTimestandardPediatricNo"  >No</label></span>

                        </fieldset>
                    </div>
                    <div class="textInput startHidden" id="durVolPediatric">
                        <fieldset>
                            <ol>
                                <li>
                                    <label>Duration (mins):</label>  <br/>  
                                    <input type="text" class="smallfield" id="durationPediatric" name="durationPediatric" value=""/>
                                </li>
                            </ol>
                        </fieldset>
                    </div>
                    <div class="radio startHidden" id="methodologyPediatric">
                        <fieldset  > 
                            <legend><span>Methodology:</span></legend>
                            <input type="radio" name="methodologyPediatric" id="methodologyPediatricMeasured" value="measured"  /><label for="methodologyPediatricMeasured">Measured</label>
                            <input type="radio" name="methodologyPediatric" id="methodologyPediatricExpert_Opinion" value="expert_opinion"  /><label for="methodologyPediatricExpert_Opinion">Expert Opinion</label>
                            <input type="radio" name="methodologyPediatric" id="methodologyPediatricUnknown" value="unknown"  /><label for="methodologyPediatricUnknown">Unknown</label>
                        </fieldset>
                    </div>
                </fieldset>

                <br/>

                <fieldset class="inputBox">
                    <legend><span>For Neonatal:</span></legend>
                    <div class="radio" id="doYouPerformNatal">
                        <fieldset>
                            <legend><span>Do you perform this procedure?</span></legend>
                            <span><input type="radio" name="isPerformedNatal" id="isPerformedNatalYes" value="yes" /><label for="isPerformedNatalYes">Yes</label></span>
                            <span><input type="radio" name="isPerformedNatal" id="isPerformedNatalNo"  value="no"  /><label for="isPerformedNatalNo">No</label></span>
                        </fieldset>
                    </div>
                    <div class="radio startHidden" id="timeStandardNatal">
                        <fieldset>
                            <legend><span>Do you have a time standard for this?</span></legend>
                            <span><input type="radio" name="hasTimestandardNatal" id="hasTimestandardNatal1" value="yes"    /><label for="hasTimestandardNatal1">Yes</label></span>
                            <span><input type="radio" name="hasTimestandardNatal" id="hasTimestandardNatal2" value="no"     /><label for="hasTimestandardNatal2">No</label></span>

                        </fieldset>
                    </div>
                    <div class="textInput startHidden" id="durVolNatal">
                        <fieldset>
                            <ol>
                                <li>
                                    <label>Duration (mins):</label> <br/>
                                    <input type="text" class="smallfield" id="durationNatal" name="durationNatal" value=""/>
                                </li>
                            </ol>


                        </fieldset>
                    </div>
                    <div class="radio startHidden" id="methodologyNatal">
                        <fieldset>
                            <legend><span>Methodology:</span></legend>
                            <input type="radio" name="methodologyNatal" id="methodologyNatal1" value="measured"  /><label for="methodologyNatal1">Measured</label>
                            <input type="radio" name="methodologyNatal" id="methodologyNatal2" value="expert_opinion"  /><label for="methodologyNatal2">Expert Opinion</label>
                            <input type="radio" name="methodologyNatal" id="methodologyNatal3" value="unknown"  /><label for="methodologyNatal3">Unknown</label>
                        </fieldset>
                    </div>
                </fieldset>

            </div>

<!--<input class="floater button" type="submit" id="submitSurveyButton" name="submitSurveyButton2" value="Submit survey answer" />-->
            <div> 
                <button class="button" id="replacement-1" type="submit">Submit survey answer</button>
            </div>
            <hr/>
            <!--<input class="clearer button" type="submit" id="skipButton2" name="skipButton2" value="Don't perform - Skip this item" />-->
            <!--fid:    either userFacilityId, or else customFacilityId.-->




            <input type="hidden" id="fid" name="fid" value="180"/>
            <!--aid: activityID or else customActivityId.-->
            <input type="hidden" id="aid" name="aid" value="78"/>
            <!--is_cf: is custom facility? is_ca is customactivity?  both are bools-->
            <input type="hidden" id="is_cf" name="is_cf" value="1"/>
            <input type="hidden" id="is_ca" name="is_ca" value="0"/>
        </form>
























        <!-- stop form data here -->

        <p class="footerNav">



            <button class="linkButton" onclick="parent.location = 'activities.php'" ><span>Back to Activities</span></button>



        </p>






    </body>
</html>