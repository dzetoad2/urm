<?php
try {

    require_once 'urm_secure/functions.php';
    require_once 'urm_secure/customFacilityFunctions.php';
    require_once 'urm_secure/sessionStateFunctions.php';



    if (!isset($_SESSION['userid'])) {
        $errorMsg = "error: userId not set in session (debug on)";
        $fromPage = 'editCustomFacility.php';
        throwMyExc($errorMsg);
        exit();
    }
    if (!isset($_POST['customFacilityId'])) {
        $errorMsg = 'error: customfacilityid not set in post';
        $fromPage = 'editCustomFacility.php';
        throwMyExc($errorMsg);
        exit();
    }

    $userId = $_SESSION['userid'];
    $customFacilityId = $_POST['customFacilityId'];
    if (trim($customFacilityId) == '') {
        $errorMsg = 'customfacilityid is blank! error';
        $fromPage = 'editCustomFacility.php';
        throwMyExc($errorMsg);
    }

    unset($_SESSION['customFacilityId']);
    $cfBean = getCustomFacilityFullBean($customFacilityId);    //new CustomFacilityFullBean();
    $name = $cfBean->name;
    $address = $cfBean->address;
    $city = $cfBean->city;
    $state = $cfBean->state;
    $zip = $cfBean->zip;
    $phone = $cfBean->phone;

    if ($cfBean->facilityTypeId == 9)
        $isOutpatientPRC = 'yes';
    else
        $isOutpatientPRC = 'no';

    $isMoreThan26TLB = $cfBean->isMoreThan26TLB;
    $isCriticalAccessHospital = $cfBean->isCriticalAccessHospital;
    $totalFacilityBeds = $cfBean->totalFacilityBeds;
    $medicalSurgicalIntensiveCareBeds = $cfBean->medicalSurgicalIntensiveCareBeds;
    $neoNatalIntensiveCareBeds = $cfBean->neoNatalIntensiveCareBeds;
    $otherIntensiveCareBeds = $cfBean->otherIntensiveCareBeds;
    $pediatricIntensiveCareBeds = $cfBean->pediatricIntensiveCareBeds;
    $errorLabel = '';
    $statusLabel = '';
    if (false === isValidState($state)) {
        //$errorLabel .= "State is somehow invalid - Please choose state<br/>";
    }



    if (isset($_POST['editSubmit'])) {
        $name = trim($_POST['name']);
        $address = trim($_POST['address']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        if ($state == 'Other')
            $state = '--';
        $zip = trim($_POST['zip']);
        $phone = trim($_POST['phone']);
        //$criticalAccessHospital = trim($_POST['criticalAccessHospital']);
        if ($name == "")
            $errorLabel .= "Please enter a facility name<br/>";
        if ($address == "")
            $errorLabel .= "Please enter an address<br/>";
        if ($city == "")
            $errorLabel .= "Please enter a city<br/>";
        if (false === isValidState($state)) {
            $errorLabel .= "Please choose a state<br/>";
        }
        if (isValidZip($zip) === false)
            $errorLabel .= "Please enter a valid zip code in one of the following formats: xxxxx, or xxxxx-yyyy<br/>";
        if ($phone == "")
            $errorLabel .="Please enter a phone number<br/>";


        if ($isOutpatientPRC == 'no') {
            (isset($_POST['isMoreThan26TLB'])) ? $isMoreThan26TLB = trim($_POST['isMoreThan26TLB']) : $isMoreThan26TLB = "na";
            (isset($_POST['isCriticalAccessHospital'])) ? $isCriticalAccessHospital = trim($_POST['isCriticalAccessHospital']) : $isCriticalAccessHospital = "na";
            $totalFacilityBeds = trim($_POST['totalFacilityBeds']);
            $medicalSurgicalIntensiveCareBeds = trim($_POST['medicalSurgicalIntensiveCareBeds']);
            $neoNatalIntensiveCareBeds = trim($_POST['neoNatalIntensiveCareBeds']);
            $otherIntensiveCareBeds = trim($_POST['otherIntensiveCareBeds']);
            $pediatricIntensiveCareBeds = trim($_POST['pediatricIntensiveCareBeds']);

            if ($isMoreThan26TLB == 'na')
                $errorLabel .='Please answer the question "Does your hospital have 26 or more total licensed beds?"<br/>';
            if ($isMoreThan26TLB == 'no' && $isCriticalAccessHospital == "na")
                $errorLabel .= "Please click Yes or No for the \"Is this a critical access hospital?\" question <br/>";
            //-------------
            if ($totalFacilityBeds == "")
                $errorLabel .= "Please enter the total number of facility beds<br/>";
            else if (!isNonNegInt($totalFacilityBeds))
                $errorLabel .= "Total Facility Beds must be a positive integer<br/>";
            //--------
            if ($medicalSurgicalIntensiveCareBeds == "")
                $errorLabel .= "Please enter the total number of medical surgical intensive care beds <br/>";
            else if (!isNonNegInt($medicalSurgicalIntensiveCareBeds))
                $errorLabel .= "Total medical surgical intensive care beds must be zero or a positive integer<br/>";
            //----------
            if ($neoNatalIntensiveCareBeds == "")
                $errorLabel .= "Please enter the total number of neo natal intensive care beds <br/>";
            else if (!isNonNegInt($neoNatalIntensiveCareBeds))
                $errorLabel .= "Total neo natal intensive care beds must be zero or a positive integer<br/>";
            //---------
            if ($otherIntensiveCareBeds == "")
                $errorLabel .= "Please enter the total number of other intensive care beds<br/>";
            else if (!isNonNegInt($otherIntensiveCareBeds))
                $errorLabel .= "Total number of other intensive care beds must be zero or a positive integer<br/>";
            //------
            if ($pediatricIntensiveCareBeds == "")
                $errorLabel .= "Please enter the total number of pediatric intensive care beds <br/>";
            else if (!isNonNegInt($pediatricIntensiveCareBeds))
                $errorLabel .= "Total pediatric intensive care beds must be zero or a positive integer<br/>";
            if ($errorLabel == '') {
                //check for sum total.
                $sumICB = $medicalSurgicalIntensiveCareBeds + $neoNatalIntensiveCareBeds + $otherIntensiveCareBeds + $pediatricIntensiveCareBeds;
                if ($totalFacilityBeds < $sumICB) {
                    $errorLabel .= 'Total facility beds must not be less than the sum of Intensive Care Beds';
                }
            }
        }

        if ($errorLabel == "") { // if still no error here, then its ok to create in the db.
            $rowsAffected = updateCustomFacility($customFacilityId, $userId, $name, $address, $city, $state, $zip, $phone, $isMoreThan26TLB, $isCriticalAccessHospital, $totalFacilityBeds, $medicalSurgicalIntensiveCareBeds, $neoNatalIntensiveCareBeds, $otherIntensiveCareBeds, $pediatricIntensiveCareBeds);
            if ($rowsAffected == 0) {
                $errorLabel.="No values were changed<br/>";
            } elseif ($rowsAffected == 1) {
                $_SESSION['updatedCustomFacilitySuccess'] = 'true';
                header("Location: myFacilities.php");
                exit();
            } else {
                $errorLabel.="unknown error, rowsaffected: " . $rowsAffected . "<br/>";
            }
        }//if errorlabel is blank
    } //end if post submit
    //savePostAndSessionVars($userId,$_POST,$_SESSION,"editCustomFacility.php");
    $stateRows = getStatesRowsHtml($state);  //this does the transofrmation of '--' to 'Other'.
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
            <link rel="stylesheet" type="text/css" href="css/styles.css" />
            <link rel="stylesheet" type="text/css" href="css/formStyles.css" />
            <link rel="stylesheet" type="text/css" href="css/textcontent.css" />
            <link rel="stylesheet" type="text/css" href="css/animatedForm.css" />
            <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {

                    $('#CAH').hover(function() {
                        $('#CAHDefinition').stop().animate({opacity:1}, 'slow');
                        $('#CAHDefinition').css('display','inline');
                        $('#CAHDefinition').css('color','#444');
                    },
                    function(){
                        $('#CAHDefinition').stop().animate({opacity:0}, 'slow');
                        $('#CAHDefinition').css('display','none');
                    });
        	   
                    // if the input id='isMT26TLB1' has an attributed 'checked', then show the id CAH div.
                    if ($('#isMT26TLB2').is(':checked')){
                        $('#CAH').show();
                    }
        	   
                    $('#isMT26TLB1').click(function(e) {  //26 OR MORE ? THE YES BUTTON
                        //hide the id CAH div.
                        $('#CAH').hide(300);
                        $('#isCAH1').removeAttr('checked');
                        $('#isCAH2').removeAttr('checked');
                    });
                    $('#isMT26TLB2').click(function(e) {          //26 OR MORE ? THE NO BUTTON
                        //show the id CAH div.
                        $('#CAH').show(300);
                        $('#isCAH1').removeAttr('checked');
                        $('#isCAH2').removeAttr('checked');
                    });
        	   
                });


            </script>


            <title>Create a User Created Facility</title>
        </head>
        <body>
            <div class="header"> 
                <?php echo "You are logged in as: " . $_SESSION['username'] . '<br/>'; ?>
                <a href="logout.php">Log out</a> | 
                <a href="myFacilities.php">Return to My Facilities page</a>
            </div>

            <?php if (isset($errorLabel) && $errorLabel != '') { ?>
                <h4 class="errorLabel"><?php echo $errorLabel; ?></h4>
            <?php } ?>
            <?php if (isset($statusLabel) && $statusLabel != '') { ?>
                <h4 class="statusLabel"><?php echo $statusLabel; ?></h4>
            <?php } ?>
            <h3>Edit User Created Facility</h3>
            <form action="editCustomFacility.php" method="post">

                <div>

                    <div class="inputunit row"> 

                        <fieldset>
                            <ol>
                                <li>
                                    <label>Facility Name:</label><br/>
                                    <input class="widefield"  type="text" name="name" value="<?php echo $name; ?>" />
                                </li>
                                <li>
                                    <label>Facility Address:</label><br/>
                                    <input class="widefield" type="text" name="address" value="<?php echo $address; ?>" />
                                </li>
                                <li>
                                    <label>Phone:</label><br/>
                                    <input class="medfield" type="text" name="phone" value="<?php echo $phone; ?>" />
                                </li>
                                <li>
                                    <label>City:</label><br/>
                                    <input class="medfield" type="text" name="city" value="<?php echo $city; ?>" />
                                </li>
                                <li>
                                    <label>State or Territory:</label><br/>
                                  <!--   <input class="tinyfield" type="text" name="state" value="<?php /* echo $state; */ ?>" />-->
                                    <select name="state" id="state">
                                        <?php echo $stateRows; ?>
                                    </select>
                                    <label class="gray italic">* If your facility is outside of the US, please specify so in the Facility Address box above</label>
                                </li>
                                <li>
                                    <label>Zip:*</label><br/>
                                    <input class="smallfield" type="text" name="zip" value="<?php echo $zip; ?>" />
                                    <span class="gray">
                                        *: Zip must be in the formats xxxxx, or xxxxx-yyyy
                                    </span>
                                </li>
                            </ol>
                        </fieldset>

                    </div>  
                    <!--   row div-->






                    <?php if ($isOutpatientPRC == 'no') { ?>

                        <div id="radioRow" class="inputBox row "> 

                            <div class="inputBox radio">
                                <fieldset>
                                    <legend><span>Licensed Beds - Does your hospital have 26 or more total licensed beds?</span></legend>
                                    <div><input type="radio" name="isMoreThan26TLB" id="isMT26TLB1" value="yes" <?php if (isset($isMoreThan26TLB) && $isMoreThan26TLB == 'yes') { ?> checked="checked" <?php } ?> /><label for="isMT26TLB1">Yes</label></div>
                                    <div><input type="radio" name="isMoreThan26TLB" id="isMT26TLB2" value="no"  <?php if (isset($isMoreThan26TLB) && $isMoreThan26TLB == 'no') { ?> checked="checked" <?php } ?> /><label for="isMT26TLB2">No</label></div>
                                </fieldset>
                            </div>
                            <div id="CAH" class="inputBox radio startHidden">
                                <fieldset>
                                    <legend><span id="CAHspan">Is this a Critical Access Hospital?</span></legend>
                                    <div><input type="radio" name="isCriticalAccessHospital" id="isCAH1" value="yes"  <?php if (isset($isCriticalAccessHospital) && $isCriticalAccessHospital == 'yes') { ?> checked="checked" <?php } ?>/><label for="isCAH1">Yes</label></div>
                                    <div><input type="radio" name="isCriticalAccessHospital" id="isCAH2" value="no" <?php if (isset($isCriticalAccessHospital) && $isCriticalAccessHospital == 'no') { ?> checked="checked" <?php } ?>  /><label for="isCAH2">No</label></div>
                                </fieldset>
                            </div>

                            <div class="hiddenDefinition">

                                <div id="CAHDefinition" class="startHidden">
                                    <label class="bold CAHDefinitionLabel">Critical Access Hospital (CAH)</label><br/>
                                    <label class="CAHDefinitionLabel">A small, generally geographically remote facility with no more than 25 beds that provides outpatient and inpatient hospital services to people in rural areas.</label>
                                </div>
                            </div>

                        </div>
                        <!--<div class="row">-->
                        <!--<div class="inputunit  ">Service Code:<br />-->
                        <!--<input class="medfield" type="text" name="serviceCode"-->
                        <!--	value="
                        <?php
//echo $serviceCode; 
                        ?>" /></div>-->
                        <!--</div>-->
                        <div class="inputunit row"> 
                            <fieldset>
                                <legend><span>Staffed Bed Counts</span></legend>
                                <ul>
                                    <li>  
                                        <label>Total Staffed Facility beds:</label><br />
                                        <input class="tinyfield" type="text" name="totalFacilityBeds"
                                               value="<?php echo $totalFacilityBeds; ?>" />
                                        <label class="grayText">* Must be not be less than the sum of all Intensive Care Beds (below)</label>
                                    </li>
                                    <li> 
                                        <label>Total Staffed Medical/Surgical Intensive Care Beds:</label><br/>
                                        <input class="tinyfield" type="text" name="medicalSurgicalIntensiveCareBeds"
                                               value="<?php echo $medicalSurgicalIntensiveCareBeds; ?>" />
                                    </li>
                                    <li> 
                                        <label>Total Staffed Pediatric Intensive Care Beds:</label><br />
                                        <input class="tinyfield" type="text" name="pediatricIntensiveCareBeds"
                                               value="<?php echo $pediatricIntensiveCareBeds; ?>" />
                                    </li>
                                    <li> 
                                        <label>Total Staffed Neonatal Intensive Care Beds:</label><br />
                                        <input class="tinyfield" type="text" name="neoNatalIntensiveCareBeds"
                                               value="<?php echo $neoNatalIntensiveCareBeds; ?>" />
                                    </li>

                                    <li>
                                        <label>Total Staffed Other Intensive Care Beds :</label><br />
                                        <input class="tinyfield" type="text" name="otherIntensiveCareBeds"
                                               value="<?php echo $otherIntensiveCareBeds; ?>" />
                                    </li>
                                </ul>
                            </fieldset>

                        </div>

                    <?php } //if outpatientPRC is no  ?>

                    <div> 
                        <input type="submit" name="editSubmit"
                               value="Go" />
                        <input type="hidden" name="customFacilityId"  value="<?php echo $customFacilityId; ?>"/>	  
                    </div>
                </div>
            </form>

            <p class="footerNav">    <a href="myFacilities.php">Return to My Facilities page (Discard Changes)</a>   </p>

        </body>
    </html>
    <?php
} catch (Exception $e) {
    $em = "editcustomfacility page:  " . $e->getMessage();
    $page = 'editCustomFacility.php';
    goErrorPage($em, $page);
}
?>