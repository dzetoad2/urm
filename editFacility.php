<?php
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/editFacilityFunctions.php';
require_once 'urm_secure/sessionStateFunctions.php';
require_once 'urm_secure/validationFunctions.php';
require_once 'urm_secure/surveyCategoriesFunctions.php';


if(!isset($_SESSION['userid'])){
    $errorMsg= "error: userid not set";
    throwMyExc($em);
}
$userId = $_SESSION['userid'];
//expect facilityId. error if not there.

if(isset($_POST['userFacilityId'])){
 $userFacilityId = $_POST['userFacilityId'];
 $_SESSION['userFacilityId']=$userFacilityId;
}else if(isset($_SESSION['userFacilityId'])){
 $userFacilityId = $_SESSION['userFacilityId'];
}else{
	  $em = "Critical Error - userFacilityId not found. This must come from myfacilities page!"; 
      throwMyExc($em);
}

//get userfacil full bean.
$ufBean = getUserFacilityFullBean($userFacilityId);
//get the userfac row.
$userFacilityRow = getUserFacilityRowHtml($ufBean);

//====defaults to 'undefined' type of facility.
//$facilityTypeId = 0;

$criticalAccessHospital = "";
//$serviceCode = "";
$totalFacilityBeds = "";
$medicalSurgicalIntensiveCareBeds =  "";
$neoNatalIntensiveCareBeds = "";
$otherIntensiveCareBeds= "";
$pediatricIntensiveCareBeds= "";

$errorLabel = '';
$statusLabel = '';

//---------------------  Visiting this page, with no form submit yet. Check to see if any other users have added this facility yet.
//------------ If someone has, we set the values to what that user set them as and grey out the fields (not writeable, it is locked).

$makeInputsInactive = false; //assume this until proved wrong.
if($ufBean === '') //  there is no bean?. leave field blank. and keep editable.
{  

}else{
  $makeInputsInactive = false;
  $userId = $ufBean->userId;
  $isMoreThan26TLB = $ufBean->isMoreThan26TLB;
  $isCriticalAccessHospital = $ufBean->isCriticalAccessHospital;
  $totalFacilityBeds = $ufBean->totalFacilityBeds;
  $medicalSurgicalIntensiveCareBeds =  $ufBean->medicalSurgicalIntensiveCareBeds;
  $neoNatalIntensiveCareBeds = $ufBean->neoNatalIntensiveCareBeds;
  $otherIntensiveCareBeds = $ufBean->otherIntensiveCareBeds;
  $pediatricIntensiveCareBeds = $ufBean->pediatricIntensiveCareBeds;
}
 
//------------------THIS IS THE FORM SUBMIT TO EDIT A USERFACILITY ENTRY ---------
if ( isset($_POST['specifySubmit'])){
	
	
 (isset($_POST['isMoreThan26TLB'])) ? $isMoreThan26TLB = trim($_POST['isMoreThan26TLB']) : $isMoreThan26TLB = "na";
 (isset($_POST['isCriticalAccessHospital'])) ? $isCriticalAccessHospital = trim($_POST['isCriticalAccessHospital']) : $isCriticalAccessHospital = "na";
 $totalFacilityBeds = trim($_POST['totalFacilityBeds'])  ;
 $medicalSurgicalIntensiveCareBeds =   trim($_POST['medicalSurgicalIntensiveCareBeds']) ;
 $neoNatalIntensiveCareBeds =  trim($_POST['neoNatalIntensiveCareBeds'])  ;
 $otherIntensiveCareBeds =   trim($_POST['otherIntensiveCareBeds']) ;
 $pediatricIntensiveCareBeds =  trim($_POST['pediatricIntensiveCareBeds'])  ;
 if($isMoreThan26TLB=='na' )
  $errorLabel .='Please answer the question "Does your hospital have 26 or more total licensed beds?"<br/>';
 if($isMoreThan26TLB=='no' && $isCriticalAccessHospital == "na")
  $errorLabel .= "Please choose Yes or No for the \"Is this a critical access hospital?\" question <br/>";
 if($isCriticalAccessHospital=='yes' && $totalFacilityBeds >= 26)
  $errorLabel .= 'Total staffed beds must be less than 26 if "Critical Access Hospital" is selected';
 if($totalFacilityBeds == "")
 $errorLabel .= "Please enter the total number of facility beds<br/>";
 else if(!isNonNegInt($totalFacilityBeds))
 $errorLabel .= "Total Facility Beds must be zero or a positive integer<br/>";
 //--------
 if($medicalSurgicalIntensiveCareBeds == "")
 $errorLabel .= "Please enter the total number of medical surgical intensive care beds <br/>";
 else if(!isNonNegInt($medicalSurgicalIntensiveCareBeds))
 $errorLabel .= "Total medical surgical intensive care beds must be zero or a positive integer<br/>";
 //----------
 if($neoNatalIntensiveCareBeds == "")
 $errorLabel .= "Please enter the total number of neo natal intensive care beds <br/>";
 else if(!isNonNegInt($neoNatalIntensiveCareBeds))
 $errorLabel .= "Total neo natal intensive care beds must be zero or a positive integer<br/>";
 //---------
 if($otherIntensiveCareBeds=="")
 $errorLabel .= "Please enter the total number of other intensive care beds<br/>";
 else if(!isNonNegInt($otherIntensiveCareBeds))
 $errorLabel .= "Total number of other intensive care beds must be zero or a positive integer<br/>";
 //------
 if($pediatricIntensiveCareBeds=="")
 $errorLabel .= "Please enter the total number of pediatric intensive care beds <br/>";
 else if(!isNonNegInt($pediatricIntensiveCareBeds))
 $errorLabel .= "Total pediatric intensive care beds must be zero or a positive integer<br/>";
 if($errorLabel==""){
   //staffed facil beds must be > or = to sum of icbs.
   $sumICB = $medicalSurgicalIntensiveCareBeds + $neoNatalIntensiveCareBeds + $otherIntensiveCareBeds + $pediatricIntensiveCareBeds;
   if($totalFacilityBeds < $sumICB){
     $errorLabel .= 'Total facility beds must not be less than the sum of Intensive Care Beds';
   }
 }
 if($errorLabel ==""){ // if still no error here, then its ok to create in the db.
  $rowsAffected =
  updateUserFacility($userId,$userFacilityId,
   $isMoreThan26TLB,
   $isCriticalAccessHospital,
   $totalFacilityBeds,
   $medicalSurgicalIntensiveCareBeds,
   $neoNatalIntensiveCareBeds,
   $otherIntensiveCareBeds,
   $pediatricIntensiveCareBeds   );
 if($rowsAffected === 0){
  $errorLabel .= "No values were changed";
 }else if($rowsAffected === 1){
  //$statusLabel .= "Success adding facility to your account"; // dont use this anymore
  $_SESSION['editFacilitySuccess'] = 'true';
  header('Location: myFacilities.php');
  exit();
 }else{
  $errorLabel .= "Unknown error trying to edit facility";
 }
 
 }
} //end if post submit

savePostAndSessionVars($userId,$_POST,$_SESSION,"specifyFacilityInfo.php");


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
<link rel="stylesheet" type="text/css" href="css/tables.css" />



<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>



<script type="text/javascript">

$(document).ready(function() {
 
	    $('#CAH').hover(function() {
	        $('#CAHDefinition').stop().animate({opacity:1}, 'fast');
	        $('#CAHDefinition').css('display','inline');
	        $('#CAHDefinition').css('color','#444');
 	    },
	    function(){
	        $('#CAHDefinition').stop().animate({opacity:0}, 'slow');
	        $('#CAHDefinition').css('display','none'); //have to leave this out as it was breaking. 
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





 
<title>Edit Facility Info</title>
</head>
<body>
<?php
// include the top header bar
include("includes/header.php");
?>

<div>
<?php   
if(defined('DEBUG')){
	echo  'Facility id just passed in is: '.$facilityId.'<br/>';
}
?>
 
<a href="myFacilities.php">Return to My Facilities page</a>
</div>
<hr/>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
<h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>
<?php if(isset($statusLabel) && $statusLabel!=''){?>
<h4 class="statusLabel"><?php echo $statusLabel;?></h4>
<?php }?>
<form action="editFacility.php" method="post">
 
<h3>Edit Facility Info</h3>
 

<div> 
 <h4>Facility:</h4>
 <table>
 <thead>
  <tr>
      <?php if(defined('DEBUG')){ ?>
      <th>Id</th> 
      <?php } ?>
      <th>Zip Code</th>
      <th>Name</th>
      <th>Street Address</th>
      <th>City</th><th>State</th>
  </tr>
 </thead>
 <tbody>
  <?php echo $userFacilityRow;?>
 </tbody>
 </table>
</div>


<!--<div id="radioRow" class="inputunit row"> -->

 <div class="inputunit radio ">
   <fieldset>
   <legend><span>Licensed Beds - Does your hospital have 26 or more total licensed beds?</span></legend>
   <div><input type="radio" name="isMoreThan26TLB" id="isMT26TLB1" value="yes" <?php if(isset($isMoreThan26TLB) && $isMoreThan26TLB=='yes'){?> checked="checked" <?php }?> <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isMT26TLB1">Yes</label></div>
   <div><input type="radio" name="isMoreThan26TLB" id="isMT26TLB2" value="no"  <?php if(isset($isMoreThan26TLB) && $isMoreThan26TLB=='no'){?> checked="checked" <?php }?> <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isMT26TLB2">No</label></div>
   </fieldset>
 </div>

 <div id="CAH" class="inputunit radio <?php if(!$makeInputsInactive) echo 'startHidden';?> ">
   <fieldset>
    <legend><span id="CAHspan">Is this a Critical Access Hospital?</span></legend>
	 <div><input type="radio" name="isCriticalAccessHospital" id="isCAH1" value="yes"  <?php if(isset($isCriticalAccessHospital) && $isCriticalAccessHospital=='yes'){?> checked="checked" <?php }?> <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isCAH1">Yes</label></div>
	 <div><input type="radio" name="isCriticalAccessHospital" id="isCAH2" value="no" <?php if(isset($isCriticalAccessHospital) && $isCriticalAccessHospital=='no'){?> checked="checked" <?php }?>  <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isCAH2">No</label></div>
   </fieldset>
 </div>
 <div class="hiddenDefinition">
  <div id="CAHDefinition" class="startHidden">
    <label class="bold CAHDefinitionLabel">Critical Access Hospital (CAH)</label><br/>
    <label class="CAHDefinitionLabel">A small, generally geographically remote facility with no more than 25 beds that provides outpatient and inpatient hospital services to people in rural areas.</label>
  </div>
 </div> 

 

<div class="inputunit row"> 
<fieldset>
<legend><span>Staffed Bed Counts</span></legend>
<ul>
 <li>  
    <label>Total Staffed Facility beds:</label><br />
    <input class="tinyfield" type="text" name="totalFacilityBeds" <?php if($makeInputsInactive) echo 'disabled="disabled" ';?>
	value="<?php echo $totalFacilityBeds; ?>" />
	<label class="grayText">* Must be equal to or greater than the sum of all Intensive Care Beds (ICB) from inputs below</label>
 </li>
 <li> 
    Total Staffed Medical/Surgical Intensive Care Beds:<br />
    <input class="tinyfield" type="text" name="medicalSurgicalIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php echo $medicalSurgicalIntensiveCareBeds; ?>" />
 </li>
 <li> 
  Total Staffed Pediatric Intensive Care Beds:<br />
  <input class="tinyfield" type="text" name="pediatricIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php echo $pediatricIntensiveCareBeds; ?>" />
 </li>
 <li> 
 Total Staffed Neonatal Intensive Care Beds:<br />
 <input class="tinyfield" type="text" name="neoNatalIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php echo $neoNatalIntensiveCareBeds; ?>" />
 </li>
	
<li>
  Total Staffed Other Intensive Care Beds :<br />
  <input class="tinyfield" type="text" name="otherIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php echo $otherIntensiveCareBeds; ?>" />
</li>

</ul>
</fieldset>
</div>
<div class='row'> 
 <input  type="submit" name="specifySubmit"  
	value="Go" />
</div>
	
</form>
<hr/>
<h4>Instructions</h4>
<p>
  Please fill in data about this facility.  Click the "Go" button to edit this facility entry.
  Total staffed facility beds must be less than 26 if "Critical Access Hospital" is selected.<br/>
  Click the "Go" button to edit this facility in your account list.
</p>
</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>