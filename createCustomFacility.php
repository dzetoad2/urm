<?php 
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/customFacilityFunctions.php';
require_once 'urm_secure/sessionStateFunctions.php';

if(!isset($_SESSION['userid'])){
  if(defined('DEBUG')){
    $errorMsg="error: userid not set (debug on)";
    throwMyExc($errorLabel);
  }else{
  	$errorMsg="error: userid not set";
  	throwMyExc($errorLabel);
  }
}
$userId = $_SESSION['userid'];
$name ="";
$address = "";
$city = "";
$state ="";
$zip = "";
$phone = "";
$chiefAdmin ="";
$criticalAccessHospital = "";
//$serviceCode = "";
$totalFacilityBeds = "";
$medicalSurgicalIntensiveCareBeds =  "";
$neoNatalIntensiveCareBeds = "";
$otherIntensiveCareBeds= "";
$pediatricIntensiveCareBeds= "";

$errorLabel = '';
$statusLabel = '';





if ( isset($_POST['createSubmit'])){

$name = trim($_POST['name']);
$address = trim($_POST['address']);
$city = trim($_POST['city']);
$state = trim($_POST['state']);
$zip = trim($_POST['zip']);
$phone = trim($_POST['phone']);

(isset($_POST['isOutpatientPRC'])) ? $isOutpatientPRC = trim($_POST['isOutpatientPRC']) : $isOutpatientPRC = "na";


if($isOutpatientPRC!='yes'){

	(isset($_POST['isMoreThan26TLB'])) ? $isMoreThan26TLB = trim($_POST['isMoreThan26TLB']) : $isMoreThan26TLB = "na";
	(isset($_POST['isCriticalAccessHospital'])) ? $isCriticalAccessHospital = trim($_POST['isCriticalAccessHospital']) : $isCriticalAccessHospital = "na";
	$totalFacilityBeds = trim($_POST['totalFacilityBeds']);
	$medicalSurgicalIntensiveCareBeds = trim($_POST['medicalSurgicalIntensiveCareBeds']);
	$neoNatalIntensiveCareBeds = trim($_POST['neoNatalIntensiveCareBeds']);
	$otherIntensiveCareBeds= trim($_POST['otherIntensiveCareBeds']);
	$pediatricIntensiveCareBeds= trim($_POST['pediatricIntensiveCareBeds']);
	if($name == "")
	$errorLabel .= "Please enter a facility name<br/>";
	if($address == "")
	$errorLabel .= "Please enter an address<br/>";
	if($phone == "")
		$errorLabel .="Please enter a phone number<br/>";
	if($city == "")
	$errorLabel .= "Please enter a city<br/>";
	if(false===isValidState($state)){
	 $errorLabel .= "Please choose a state<br/>";
	}if(isValidZip($zip) === false)
	 $errorLabel .= "Please enter a valid zip code in one of the following formats: xxxxx, or xxxxx-yyyy<br/>";
	
	if($isOutpatientPRC!='yes' && $isOutpatientPRC!='no'){
		//force them to asnwer this radiobutton.
		$errorLabel .= 'Please specify if this facility is a free-standing Pulmonary Rehabilitation center<br/>';
	}
	if($isMoreThan26TLB=='na')
	$errorLabel .='Please answer the question "Does your hospital have 26 or more total licensed beds?"<br/>';
	if($isMoreThan26TLB=='no' && $isCriticalAccessHospital == "na")
	$errorLabel .= "Please click Yes or No for the \"Is this a critical access hospital?\" question <br/>";
	//-------new------
	if($isCriticalAccessHospital=='yes' && $totalFacilityBeds >= 26)
	  $errorLabel .= 'Total staffed beds must be less than 26 if "Critical Access Hospital" is selected';
	//=============== 
	if($totalFacilityBeds == "")
	$errorLabel .= "Please enter the total number of facility beds<br/>";
	else if(!isPosInt($totalFacilityBeds))
	$errorLabel .= "Total Facility Beds must be a positive integer<br/>";
	//--------
	if($medicalSurgicalIntensiveCareBeds == "")
	$errorLabel .= "Please enter the total number of medical surgical intensive care beds <br/>";
	else if(!isPosInt($medicalSurgicalIntensiveCareBeds))
	$errorLabel .= "Total medical surgical intensive care beds must be a positive integer<br/>";
	//----------
	if($neoNatalIntensiveCareBeds == "")
	$errorLabel .= "Please enter the total number of neo natal intensive care beds <br/>";
	else if(!isPosInt($neoNatalIntensiveCareBeds))
	$errorLabel .= "Total neo natal intensive care beds must be a positive integer<br/>";
	//---------
	if($otherIntensiveCareBeds=="")
	$errorLabel .= "Please enter the total number of other intensive care beds<br/>";
	else if(!isPosInt($otherIntensiveCareBeds))
	$errorLabel .= "Total number of other intensive care beds must be a positive integer<br/>";
	//------
	if($pediatricIntensiveCareBeds=="")
	$errorLabel .= "Please enter the total number of pediatric intensive care beds <br/>";
	else if(!isPosInt($pediatricIntensiveCareBeds))
	$errorLabel .= "Total pediatric intensive care beds must be a positive integer<br/>";
	if($errorLabel==''){
	  //check for sum total.
	  $sumICB = $medicalSurgicalIntensiveCareBeds + $neoNatalIntensiveCareBeds + $otherIntensiveCareBeds + $pediatricIntensiveCareBeds; 
	  if($totalFacilityBeds < $sumICB){
	    $errorLabel .= 'Total facility beds must not be less than the sum of Intensive Care Beds';
	  }
	}
}//isoutpatientPRC  or  isfreestandingPRC, same thing

 if($errorLabel == ""){ // if still no error here, then its ok to create in the db.
  if($isOutpatientPRC=='no'){
  $rowsAffected = createCustomFacility($userId,
  $name,
  $address,
  $city,
  $state,
  $zip,
  $phone,
  'false', // isoutpatientprc input flag
  $isMoreThan26TLB,
  $isCriticalAccessHospital,
  $totalFacilityBeds,
  $medicalSurgicalIntensiveCareBeds,
  $neoNatalIntensiveCareBeds,
  $otherIntensiveCareBeds,
  $pediatricIntensiveCareBeds);
  }else{
  	$rowsAffected = createCustomFacility($userId,
  			$name,
  			$address,
  			$city,
  			$state,
  			$zip,
  			$phone,
  			'isOutpatientPRC',   // the 'isoutpatientprcflag' input.
  			'na',
  			'na',
  			-1, //$totalFacilityBeds,
  			-1,//$medicalSurgicalIntensiveCareBeds,
  			-1,//$neoNatalIntensiveCareBeds,
  			-1,//$otherIntensiveCareBeds,
  			-1//$pediatricIntensiveCareBeds);
  	);
  }
  if($rowsAffected == 0){
   $errorLabel.="Error - unable to add this entry to the database<br/>";
  }
//$statusLabel .= "User Created facility successfully created<br/>";//no use anymore
  $_SESSION['addedCustomFacilitySuccess']='true';
  header('Location: myFacilities.php');
  exit();
 }
} //end if post submit

//savePostAndSessionVars($userId,$_POST,$_SESSION,"createCustomFacility.php");

$stateRows = getStatesRowsHtml($state);



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
//	        $('#CAHDefinition').css('display','none'); //have to leave this out as it was breaking. 
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
	   
	   $('#isOutpatientPRC1').click(function(e) {  //the yes button
			$('input.canDisable').attr('disabled','disabled');
			$('input.canDisable').css('background-color','#EEE');
		   
	   });
	   $('#isOutpatientPRC2').click(function(e) {     // the no button
		   $('input.canDisable').removeAttr('disabled');
		   $('input.canDisable').css('background-color','#FFD');
		   
	   });
});


</script>

 
<title>Edit User Created Facility</title>
</head>
<body>
<div class="header"> 
 <?php  echo "You are logged in as: ". $_SESSION['username'].'<br/>';?>
 <a href="logout.php">Log out</a> | 
 <a href="myFacilities.php">Return to My Facilities page</a>
</div>
<hr/>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
<h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>
<?php if(isset($statusLabel) && $statusLabel!=''){?>
<h4 class="statusLabel"><?php echo $statusLabel;?></h4>
<?php }?>
<h3>Add a User Created Facility</h3>
<form action="createCustomFacility.php" method="post">

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
<!--  <input class="tinyfield" type="text" name="state" value="<?php echo $state; ?>" />-->
 <select name="state" id="state">
  <?php echo $stateRows; ?>
  </select>
 </li>
 <li>
  <label>Zip:*</label><br/>
  <input class="smallfield" type="text" name="zip" value="<?php echo $zip; ?>" />
 </li>
</ol>
</fieldset>

</div>  
  <!--   row div-->
  
  
<div id="radioRow" class="inputBox row "> 
 <div class="gray">
 *: Zip must be in the formats xxxxx, or xxxxx-yyyy
 </div>
 
 
 
 <div class="inputBox radio ">
   <fieldset>
   <legend><span>This facility is a free-standing Pulmonary Rehabilitation center. (If yes, do not provide bed information)</span></legend>
   <div><input  type="radio" name="isOutpatientPRC" id="isOutpatientPRC1" value="yes" <?php if(isset($isOutpatientPRC) && $isOutpatientPRC=='yes'){?> checked="checked" <?php }?>    /><label for="isOutpatientPRC1">Yes</label></div>
   <div><input    type="radio" name="isOutpatientPRC" id="isOutpatientPRC2" value="no" <?php if(isset($isOutpatientPRC) && $isOutpatientPRC=='no'){?> checked="checked" <?php }?>   /><label for="isOutpatientPRC2">No</label></div>
   </fieldset>
 </div>
 
  <div class="inputBox radio">
   <fieldset>
    <legend><span>Licensed Beds - Does your hospital have 26 or more total licensed beds?</span></legend>
    <div><input class="canDisable" type="radio" name="isMoreThan26TLB" id="isMT26TLB1" value="yes" <?php if(isset($isMoreThan26TLB) && $isMoreThan26TLB=='yes'){?> checked="checked" <?php }?> /><label for="isMT26TLB1">Yes</label></div>
    <div><input class="canDisable" type="radio" name="isMoreThan26TLB" id="isMT26TLB2" value="no"  <?php if(isset($isMoreThan26TLB) && $isMoreThan26TLB=='no'){?> checked="checked" <?php }?> /><label for="isMT26TLB2">No</label></div>
   </fieldset>
  </div>
  <div id="CAH" class="inputBox radio startHidden">
   <fieldset>
    <legend><span id="CAHspan">Is this a Critical Access Hospital?</span></legend>
	 <div><input class="canDisable" type="radio" name="isCriticalAccessHospital" id="isCAH1" value="yes"  <?php if(isset($isCriticalAccessHospital) && $isCriticalAccessHospital=='yes'){?> checked="checked" <?php }?>/><label for="isCAH1">Yes</label></div>
	 <div><input class="canDisable" type="radio" name="isCriticalAccessHospital" id="isCAH2" value="no" <?php if(isset($isCriticalAccessHospital) && $isCriticalAccessHospital=='no'){?> checked="checked" <?php }?>  /><label for="isCAH2">No</label></div>
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
    <input class="tinyfield canDisable" type="text" name="totalFacilityBeds"
	value="<?php echo $totalFacilityBeds; ?>" />
	<label class="grayText">* Must be not be less than the sum of all Intensive Care Beds (below)</label>
 </li>
 <li> 
    <label>Total Staffed Medical/Surgical Intensive Care Beds:</label><br/>
    <input class="tinyfield canDisable" type="text" name="medicalSurgicalIntensiveCareBeds"
	value="<?php echo $medicalSurgicalIntensiveCareBeds; ?>" />
 </li>
 <li> 
  <label>Total Staffed Pediatric Intensive Care Beds:</label><br />
  <input class="tinyfield canDisable" type="text" name="pediatricIntensiveCareBeds"
	value="<?php echo $pediatricIntensiveCareBeds; ?>" />
 </li>
 <li> 
 <label>Total Staffed Neonatal Intensive Care Beds:</label><br />
 <input class="tinyfield canDisable" type="text" name="neoNatalIntensiveCareBeds"
	value="<?php echo $neoNatalIntensiveCareBeds; ?>" />
 </li>
	
<li>
  <label>Total Staffed Other Intensive Care Beds :</label><br />
  <input class="tinyfield canDisable" type="text" name="otherIntensiveCareBeds"
	value="<?php echo $otherIntensiveCareBeds; ?>" />
</li>
</ul>
</fieldset>

</div>
<div> 
  <input type="submit" name="createSubmit"
	  value="Create and add to my list" />
</div>
</div>
</form>

</body>
</html>
<?php 
}catch(Exception $e){
	goErrorPage($e);
}
?>