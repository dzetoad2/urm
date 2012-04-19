<?phptry{require_once 'urm_secure/functions.php';require_once 'urm_secure/facilityFunctions.php';require_once 'urm_secure/facilityTypeFunctions.php';require_once 'urm_secure/myFacilitiesFunctions.php';require_once 'urm_secure/customFacilityFunctions.php';require_once 'urm_secure/sessionStateFunctions.php';if(!loggedin()){	//echo "userarea but not loggedin!<br/>\n";	header("Location: login.php");	exit();}if(!isset($_SESSION['username'])){	$errorMsg= "Error: username not set.";	exit();}$statusLabel='';$errorLabel='';$un = $_SESSION['username'];$userId = $_SESSION['userid'];if(!isset($_SESSION['username']) || !isset($_SESSION['userid'])){	$errorMsg= "error: un or userid not set";	throwMyExc($errorMsg);}if(isset($_POST['clearFacilitiesForm'])){		$recordsDeleted = clearFacilities($userId);		if($recordsDeleted === 0)		  $errorLabel .= 'No facility records deleted';		else	   	  $statusLabel .= $recordsDeleted . ' facility record(s) deleted<br/>';}if(isset($_POST['clearCustomFacilitiesForm'])){		$recordsDeletedCustom = clearCustomFacilities($userId);		if($recordsDeletedCustom === 0)		  $errorLabel .= 'No user created facility records available to delete';		else		  $statusLabel .= $recordsDeletedCustom . ' user created facility record(s) deleted<br/>';}if(isset($_POST['facilityId'])){	$facilityId = $_POST['facilityId'];	if(defined('debug')){	  echo "<br/>facilityId (post) is: ".$facilityId;	}  //try to add (if not already there) this facility to this user's list.  	$errorLabel .= setUserFacility($userId,$facilityId);//		exit();		}//-----------------  if userfacilityid is set, this is a mod to the userFacility table. ------if(isset($_SESSION['userFacilityId']) && isset($_POST['facilityTypeId'])){	$userFacilityId = $_SESSION['userFacilityId']; //stored in session just before the choosefacilitytype page loaded.	$facilityTypeId = $_POST['facilityTypeId'];  //directly from the 'choosefacilityType post.	if(defined('DEBUG')){	 echo "<br/>userId (session) is:".$userId;	 echo "<br/>userFacilityId (session) is: ".$userFacilityId;	 echo "<br/>facilityTypeId (post) is: ".$facilityTypeId. "<br/>";	}	if(!setUserFacilityType($userId,$userFacilityId,$facilityTypeId)){  //try to set facilityType for this userFacility entry.		$errorMsg= "<br/>Error:  setuserfacilitytype() failed.";		throwMyExc($errorMsg);	}} //-------------------- else if its customfacilityid set, its a mod to the customFacility table.  else if(isset($_SESSION['customFacilityId']) && isset($_POST['facilityTypeId'])){ 	$customFacilityId = $_SESSION['customFacilityId']; //stored in session just before the choosefacilitytype page loaded.	$facilityTypeId = $_POST['facilityTypeId'];  //directly from the 'choosefacilityType post.   if(defined('DEBUG')){	echo "<br/>userId (session) is:".$userId;	echo "<br/>customFacilityId (session) is: ".$customFacilityId;	echo "<br/>facilityTypeId (post) is: ".$facilityTypeId. "<br/>";   }		if(!setCustomFacilityType($userId,$customFacilityId,$facilityTypeId)){  //try to set facilityType for this userFacility entry.		$errorMsg= "<br/>Error:  setcustomfacilitytype() failed.";		throwMyExc($errorMsg);	} 	 }//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------if(!$myFacilitiesRows = getMyFacilitiesRowsHtml($userId)){	$errorMsg= "Error in getmyfacilitiesrowshtml(un)";	throwMyExc($errorMsg);}if(!$myCustomFacilitiesRows = getMyCustomFacilitiesRowsHtml($userId)){	$errorMsg="Error in getmycustomfacilitiesrowshtml(un)";	throwMyExc($errorMsg);}  savePostAndSessionVars($userId,$_POST,$_SESSION,"myFacilities.php");if(isset($_SESSION['addFacilitySuccess']) && $_SESSION['addFacilitySuccess']=='true'){  $statusLabel .= 'Success adding facility';  unset($_SESSION['addFacilitySuccess']);}elseif(isset($_SESSION['addedCustomFacilitySuccess']) && $_SESSION['addedCustomFacilitySuccess']=='true'){  $statusLabel .= 'Success adding user created facility';  unset($_SESSION['addedCustomFacilitySuccess']);}elseif(isset($_SESSION['editFacilitySuccess']) && $_SESSION['editFacilitySuccess']=='true'){  $statusLabel .= 'Success updating facility';  unset($_SESSION['editFacilitySuccess']);}elseif(isset($_SESSION['updatedCustomFacilitySuccess']) && $_SESSION['updatedCustomFacilitySuccess']=='true'){  $statusLabel .= 'Success updating user created facility';  unset($_SESSION['updatedCustomFacilitySuccess']);}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>My Facilities</title><link rel="stylesheet" type="text/css" href="css/reset-min.css"/><link rel="stylesheet" type="text/css" href="css/styles.css" /><link rel="stylesheet" type="text/css" href="css/tables.css" /><link rel="stylesheet" type="text/css" href="css/formStyles.css" /><link rel="stylesheet" href="js/jquery.alerts-1.1/jquery.alerts.css" type="text/css" /><script type="text/javascript" src="js/jquery-1.6.2.min.js"></script><script src="js/jquery.alerts-1.1/jquery.alerts.js"></script><script type="text/javascript" src="js/tables.js"></script><script type="text/javascript">                                         $(document).ready(function() {	   // THIS SETS THE UNDEFINED BOXES TO YELLOW. they have a id value of 0, which is from the facilityType table 0th row.	   $('td[id$="8"]').css("background-color","yellow");	   //$('td[id$="1"]').css("background-color","red");	   	   $("a.unclickable").click(function(e) {		  e.preventDefault();	   });	   $("a#clearFacilitiesLink").click(function(e) {		   	e.preventDefault();		 	jConfirm('Delete all facilities?', 'Confirm', function(r) {				if(r == true){					 document.forms["clearFacilitiesForm"].submit();				}				else{									}			});			 //		   	var answer = confirm("Delete all facilities?")//		    if (answer){//		    	document.forms["clearFacilitiesForm"].submit();//		    }	   });	   $("a#clearCustomFacilitiesLink").click(function(e) {		   	 e.preventDefault();		   	jConfirm('Delete all user created facilities?', 'Confirm', function(r) {				if(r == true){					 document.forms["clearCustomFacilitiesForm"].submit();				}				else{									}			});//			 var answer = confirm("Delete all user created facilities?")//		     if (answer){//		         document.forms["clearCustomFacilitiesForm"].submit();//		     }	   });	  	   $(".editFacility").click(function() {		     var rowId = $(this).closest(".userFacilityRow").attr("id");		     //alert("userfacilityrow id is:"+rowId);		     //now add to the form 'editFacilityAction' and submit it.		     $("#editFacilityAction").children("input#userFacilityId").val(rowId);		     document.forms["editFacilityAction"].submit();	   });	   $(".editCustomFacility").click(function() {		     var rowId = $(this).closest(".customFacilityRow").attr("id");		     //alert("customFacilityRow id is:"+rowId);		     //now add to the form 'editFacilityAction' and submit it.		     $("#editCustomFacilityAction").children("input#customFacilityId").val(rowId);		     document.forms["editCustomFacilityAction"].submit();	   });	   /*--------------chooseactions:  choosing [custom or normal]facility type.-----------*/	   $(".facilityTypeId").click(function() {		     var rowId = $(this).closest(".userFacilityRow").attr("id");		     var name = $(this).closest(".userFacilityRow").children(".nameCell:first").attr("id");		     //now add to the form 'chooseAction' and submit it.		     $("#chooseAction").children("input#userFacilityId").val(rowId);		     $("#chooseAction").children("input#facilityName").val(name);		     		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );		     document.forms["chooseAction"].submit();	   }); 	   $(".customFacilityTypeId").click(function() {		     var rowId = $(this).closest(".customFacilityRow").attr("id");		     var name = $(this).closest(".customFacilityRow").children(".nameCell:first").attr("id");		     //alert("Facility: "+name +" was chosen.");		     //now add to the form 'chooseAction' and submit it.		     $("#chooseAction2").children("input#customFacilityId").val(rowId);		     $("#chooseAction2").children("input#facilityName").val(name);		     		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );		     document.forms["chooseAction2"].submit();	    });});</script></head><body><?php// include the top header barinclude("includes/header.php");?><?php if(isset($errorLabel) && $errorLabel!=''){?><h4 class="errorLabel"><?php echo $errorLabel;?></h4><?php }if(isset($statusLabel) && $statusLabel!=''){?><h4 class="statusLabel"><?php echo $statusLabel;?></h4><?php }?><h3>My Facilities:</h3> <p>    To add a facility, click on <i>Add a facility from the database</i>. <br/>   Enter either the full or partial zip code of your facility, and select the Type from the drop-down.<br/>   Click on <i>show facilities with this zip code</i>.    If your facility appears, click on it. On the next screen click on the name that best categorizes your facility.    After identifying the facility type click on Add a facility from the database. </p><div><p>Click on a facility's "Facility Type" cell to set its type*.  <br/><br/>  <label class="grayText font-georgia">   *  If gray,it is locked because demographic information has already entered byanother user. To view surveys in this facility that have not beencompleted, click on URM home — select "access survey" and click on thisfacility. Select the survey you wish to complete from those that remainavailable.</label><br/><br/> </p><!--emdash:--><!--—-->   <table>	<thead>	  <tr> 		<?php if(defined('DEBUG')){?>		<th>Id</th>		<?php }?>		<th>Edit</th>		<th>Name</th>		<th>Street Address</th>		<th>City</th>		<th>State</th>		<th>Zip Code</th>		<th>Facility Type</th>		<?php if(defined('debug')){?>		<th>facilityType Id</th>		<?php }?>	  </tr>	</thead>	<tbody>	<?php	echo $myFacilitiesRows;  // identified by "userFacilityRow" in a cell.	?>	</tbody> </table> <div>    <a href="chooseFacility.php">Add a facility from the database</a> |  <a id="clearFacilitiesLink" href=" ">Clear my facilities</a> </div></div>          <h3>My User Created Facilities:</h3><p>   If your facility does not appear, you must enter it as a <b>user created</b> facility by clicking on <i>Add a user created Facility</i>.     Complete all required fields, then click on <i>Create and add to my list</i>.    You will receive a message that you have successfully created a user created facility.   Then, at the top of the page,  click on <i>Return to My Facilities page</i>.</p><div><p>Click on a user created facility to set its type.</p><table>	<thead>	  <tr> 		<?php if(defined('DEBUG')){?>		<th>Id</th>		<?php }?>		<th>Edit</th>		<th>Name</th>		<th>Street Address</th>		<th>City</th>		<th>State</th>		<th>Zip Code</th>		<th>Facility Type</th>		<?php if(defined('debug')){?>		<th>facilityType Id</th>		<?php }?>	  </tr>	</thead>	<tbody>	<?php	echo $myCustomFacilitiesRows;  // identified by "customFacilityRow" in a cell.	?>	</tbody></table><div> <a href="createCustomFacility.php">Add a user created facility</a> |  <a id="clearCustomFacilitiesLink" href=" ">Clear my user created facilities</a></div></div><hr/><div> <div> <h4>Instructions:</h4> <ol>  <li>   Click the row of any facility to modify its type.  If the type reads "Undefined" (in yellow) please click on that row to modify it to the correct type.  </li>  <li>   After all facilties have been identified, <a href="viewSurveyInstructions.php">click here</a> to access <i>Survey Instructions</i>.  </li> </ol> </div></div><p class="footerNav"><a href="home.php">URM Home</a></p><form id="chooseAction" name="chooseAction"	action="chooseFacilityType.php" method="post">	<input type="hidden" id="userFacilityId" name="userFacilityId" value="nothing" /> 	<input type="hidden" id="facilityName" name="facilityName" value="nothing" /></form><form id="editFacilityAction" name="editFacilityAction"	action="editFacility.php" method="post">	<input type="hidden" id="userFacilityId" name="userFacilityId" value="nothing" /> 	</form><form id="editCustomFacilityAction" name="editCustomFacilityAction"	action="editCustomFacility.php" method="post">	<input type="hidden" id="customFacilityId" name="customFacilityId" value="nothing" /> 	</form>			<form id="chooseAction2" name="chooseAction2"	action="chooseFacilityType.php" method="post">	<input type="hidden" id="customFacilityId" name="customFacilityId" value="nothing" /> 	<input type="hidden" id="facilityName" name="facilityName" value="nothing" /></form><form id="clearFacilitiesForm" name="clearFacilitiesForm"	action="myFacilities.php" method="post"><input type="hidden"	id="clearFacilitiesFormInput" name="clearFacilitiesForm" value="1" /></form><form id="clearCustomFacilitiesForm" name="clearCustomFacilitiesForm"	action="myFacilities.php" method="post"><input type="hidden"	id="clearCustomFacilitiesFormInput" name="clearCustomFacilitiesForm" value="1" /></form></body></html><?php }catch(Exception $e){  goErrorPage($e);}?>