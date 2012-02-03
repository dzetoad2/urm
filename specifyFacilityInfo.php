<?php
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/sessionStateFunctions.php';
require_once 'urm_secure/specifyFacilityInfoFunctions.php';
require_once 'urm_secure/validationFunctions.php';
require_once 'urm_secure/surveyCategoriesFunctions.php';

if(!isset($_SESSION['userid'])){
    $errorMsg= "error: userid not set";
    throwMyExc($errorMsg);
}
$userId = $_SESSION['userid'];
//expect facilityId. error if not there.

if(isset($_POST['facilityId'])){
 $facilityId = $_POST['facilityId'];
 $_SESSION['facilityId']=$facilityId;
}else if(isset($_SESSION['facilityId'])){
 $facilityId = $_SESSION['facilityId'];
}else{
    if(defined('debug')){
	  $em = "debug on - specifyfacilityinfo page - Critical Error - facilityId not found. This must come from choosefacility page!"; 
      throwMyExc($em);
	}else{
	 header("Location: chooseFacility.php");
	}
}

//get the fac name
$facilityRows = getFacilityRowHtml($facilityId);

//====defaults to 'undefined' type of facility.
$facilityTypeId = 8;   //------- 8 is 'undefined'

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
$ufBean = getOriginalUserUserFacilityBean($facilityId); // get the row for the first user who added this facility , if there is one.
if($ufBean === '') //  there is no first owner. leave field blank. and keep editable.
{  

}else{
  //there is a first owner (me? or someone else? gonna find out below).
  $makeInputsInactive = true;
  $facilityOwnerId = $ufBean->userId;
  $isMoreThan26TLB = $ufBean->isMoreThan26TLB;
  $isCriticalAccessHospital = $ufBean->isCriticalAccessHospital;
  $totalFacilityBeds = $ufBean->totalFacilityBeds;
  $medicalSurgicalIntensiveCareBeds =  $ufBean->medicalSurgicalIntensiveCareBeds;
  $neoNatalIntensiveCareBeds = $ufBean->neoNatalIntensiveCareBeds;
  $otherIntensiveCareBeds = $ufBean->otherIntensiveCareBeds;
  $pediatricIntensiveCareBeds = $ufBean->pediatricIntensiveCareBeds;
  if($facilityOwnerId === ''){
    //this doesnt make sense!!
    //$statusLabel .= 'no one owns this facility yet';
    $em = 'getOriginalUserUserFacilityBean: userfacility bean came back but its userId was blank - probably impossible?';
    throwMyExc($em);
  }else if($facilityOwnerId == $userId){
	//i am the owner, so gray out the answers and give a 'already added' message.
	$errorLabel .= 'This facility is already added to your account.  To change 
	   the fields, please use the edit function on the appropriate facility';
  
  }else{
    //someone else owns it so gray out fields, make them uneditable, and assign them from the db using a bean.
    $originalUser = getUsername($facilityOwnerId);
    $statusLabel .= 'Note: User "'.$originalUser.'" already added this facility and provided the Staffed Bed Count data. Values are locked unless "'.$originalUser.'" 
      deletes the facility from his or her account<br/>';
    
    
    
    
    
  }


}

 
 











//------------------THIS IS THE FORM SUBMIT TO ADD A USERFACILITY ENTRY AS PER THE FORM THEY FILLED OUT.---------
if ( isset($_POST['specifySubmit'])){
$inputsActive = $_POST['inputsActive'];
if($inputsActive){
	
 (isset($_POST['isOutpatientPRC'])) ? $isOutpatientPRC = trim($_POST['isOutpatientPRC']) : $isOutpatientPRC = "na";
 if($isOutpatientPRC!='yes' && $isOutpatientPRC!='no'){
 	//force them to asnwer this radiobutton.
 	$errorLabel .= 'Please specify if this facility is a free-standing Pulmonary Rehabilitation center<br/>';
 }
 
 if($isOutpatientPRC!='yes'){
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
  if($errorLabel==""){
   //staffed facil beds must be > or = to sum of icbs.
   $sumICB = $medicalSurgicalIntensiveCareBeds + $neoNatalIntensiveCareBeds + $otherIntensiveCareBeds + $pediatricIntensiveCareBeds;
   if($totalFacilityBeds < $sumICB){
     $errorLabel .= 'Total facility beds must not be less than the sum of Intensive Care Beds';
   }
  }
 }//if isoutpatientprc=='no'
}//if inputsactive 
 if($errorLabel ==""){ // if still no error here, then its ok to create in the db.
 	
  if($inputsActive && $isOutpatientPRC=='no'){
  $rowsAffected =
  addUserFacility($userId,$facilityId,
  $facilityTypeId,
  $isMoreThan26TLB,
  $isCriticalAccessHospital,
  $totalFacilityBeds,
  $medicalSurgicalIntensiveCareBeds,
  $neoNatalIntensiveCareBeds,
  $otherIntensiveCareBeds,
  $pediatricIntensiveCareBeds   );
  }elseif($inputsActive && $isOutpatientPRC=='yes'){
  	$rowsAffected =
  	addUserFacility($userId,$facilityId,
  			9, //$facilityTypeId, 9 is from facilityTable, "outpatient pr".
  			'na',//$isMoreThan26TLB,
  			'na', //$isCriticalAccessHospital,
  			-1,//$totalFacilityBeds,
  			-1,//$medicalSurgicalIntensiveCareBeds,
  			-1,//$neoNatalIntensiveCareBeds,
  			-1,//$otherIntensiveCareBeds,
  			-1//$pediatricIntensiveCareBeds
  	);
  
 }else{
  $rowsAffected =
  addUserFacility($userId,$facilityId,
  6, //$facilityTypeId, 6 is from the facilityTable ,"defined by someone else".
  'otherUserAnswered',//$isMoreThan26TLB,
  'otherUserAnswered', //$isCriticalAccessHospital,
  -1,//$totalFacilityBeds,
  -1,//$medicalSurgicalIntensiveCareBeds,
  -1,//$neoNatalIntensiveCareBeds,
  -1,//$otherIntensiveCareBeds,
  -1//$pediatricIntensiveCareBeds   
  );
  
  }
  
  
if($rowsAffected === 0){
 $errorLabel .= "Unable to add this entry. Have you already added this facility?<br/>";
}else if($rowsAffected === 1){
	//success adding entry. redirect back to prev page.
  $_SESSION['addFacilitySuccess'] = 'true';
  header('Location: myFacilities.php');
  exit();
}else{
  $errorLabel .= "Unknown error trying to add facility";
}
 
 }
} //end if post submit

//savePostAndSessionVars($userId,$_POST,$_SESSION,"specifyFacilityInfo.php");


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
	        $('#CAHDefinition').stop().animate({opacity:0}, 'fast');
//	                         $('#CAHDefinition').css('display','none'); //have to leave this out as it was breaking. 
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
	   <?php if($makeInputsInactive){?>
	    $('input.canDisable').attr('disabled','disabled');
		$('input.canDisable').css('background-color','#EEE');
	   <?php }?>
	   
});


</script>





 
<title>Specify Facility Info</title>
</head>
<body>
<div>
<?php  echo "You are logged in as: ". $_SESSION['username'].'<br/>';
if(defined('debug')){
	echo  'Facility id just passed in is: '.$facilityId.'<br/>';
}
?>
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
<form action="specifyFacilityInfo.php" method="post">
 
<h3>Specify Facility Info</h3>
 

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
  <?php echo $facilityRows;?>
 </tbody>
 </table>
</div>


<!--<div id="radioRow" class="inputunit row"> -->

<div class="inputunit radio ">
   <fieldset>
   <legend><span>This facility is a free-standing Pulmonary Rehabilitation center. (If yes, do not provide bed information)</span></legend>
   <div><input   type="radio" name="isOutpatientPRC" id="isOutpatientPRC1" value="yes"   <?php if($makeInputsInactive) echo 'disabled="disabled"';?> <?php if(isset($isOutpatientPRC) && $isOutpatientPRC=='yes'){?> checked="checked" <?php }?>  /><label for="isOutpatientPRC1">Yes</label></div>
   <div><input   type="radio" name="isOutpatientPRC" id="isOutpatientPRC2" value="no"  <?php if($makeInputsInactive) echo 'disabled="disabled"';?> <?php if(isset($isOutpatientPRC) && $isOutpatientPRC=='no'){?> checked="checked" <?php }?> /><label for="isOutpatientPRC2">No</label></div>
   </fieldset>
 </div>

 <div class="inputunit radio ">
   <fieldset>
   <legend><span>Licensed Beds - Does your hospital have 26 or more total licensed beds?</span></legend>
   <div><input class="canDisable" type="radio" name="isMoreThan26TLB" id="isMT26TLB1" value="yes" <?php if(isset($isMoreThan26TLB) && $isMoreThan26TLB=='yes'){?> checked="checked" <?php }?> <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isMT26TLB1">Yes</label></div>
   <div><input class="canDisable" type="radio" name="isMoreThan26TLB" id="isMT26TLB2" value="no"  <?php if(isset($isMoreThan26TLB) && $isMoreThan26TLB=='no'){?> checked="checked" <?php }?> <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isMT26TLB2">No</label></div>
   </fieldset>
 </div>

 <div id="CAH" class="inputunit radio <?php if(!$makeInputsInactive) echo 'startHidden';?> ">
   <fieldset>
    <legend><span id="CAHspan">Is this a Critical Access Hospital?</span></legend>
	 <div><input class="canDisable" type="radio" name="isCriticalAccessHospital" id="isCAH1" value="yes"  <?php if(isset($isCriticalAccessHospital) && $isCriticalAccessHospital=='yes'){?> checked="checked" <?php }?> <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isCAH1">Yes</label></div>
	 <div><input class="canDisable" type="radio" name="isCriticalAccessHospital" id="isCAH2" value="no" <?php if(isset($isCriticalAccessHospital) && $isCriticalAccessHospital=='no'){?> checked="checked" <?php }?>  <?php if($makeInputsInactive) echo 'disabled="disabled"';?> /><label for="isCAH2">No</label></div>
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
    <input class="tinyfield canDisable" type="text" name="totalFacilityBeds" <?php if($makeInputsInactive) echo 'disabled="disabled" ';?>
	value="<?php if($totalFacilityBeds!='-1') echo $totalFacilityBeds; ?>" />
	<label class="grayText">* Must be equal to or greater than the sum of all Intensive Care Beds (ICB) from inputs below</label>
 </li>
 <li> 
    Total Staffed Medical/Surgical Intensive Care Beds:<br />
    <input class="tinyfield canDisable" type="text" name="medicalSurgicalIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php if($medicalSurgicalIntensiveCareBeds!='-1') echo $medicalSurgicalIntensiveCareBeds; ?>" />
 </li>
 <li> 
  Total Staffed Pediatric Intensive Care Beds:<br />
  <input class="tinyfield canDisable" type="text" name="pediatricIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php if($pediatricIntensiveCareBeds!='-1') echo $pediatricIntensiveCareBeds; ?>" />
 </li>
 <li> 
 Total Staffed Neonatal Intensive Care Beds:<br />
 <input class="tinyfield canDisable" type="text" name="neoNatalIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php if($neoNatalIntensiveCareBeds!='-1') echo $neoNatalIntensiveCareBeds; ?>" />
 </li>
	
<li>
  Total Staffed Other Intensive Care Beds:<br />
  <input class="tinyfield canDisable" type="text" name="otherIntensiveCareBeds" <?php if($makeInputsInactive) echo 'disabled="disabled"';?>
	value="<?php if($otherIntensiveCareBeds!='-1') echo $otherIntensiveCareBeds; ?>" />
</li>

</ul>
</fieldset>
</div>
<div class='row'> 
 <input  type="submit" name="specifySubmit"  
	value="Go" />
</div>
	
<input type="hidden" name="inputsActive" value="<?php if($makeInputsInactive) echo "0"; else echo "1"; /*"inactive" opposite of 'active'. */?>"/>	
</form>
<hr/>
<h4>Instructions</h4>
<p>
  Please fill in data about this facility.<br/>  
  Total staffed facility beds must be less than 26 if "Critical Access Hospital" is selected.<br/>
  Click the "Go" button to add this facility to your account list.
</p>
</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}
?>