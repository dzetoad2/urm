<?phptry{require_once 'urm_secure/functions.php';require_once 'urm_secure/facilityFunctions.php';require_once 'urm_secure/facilityTypeFunctions.php';require_once 'urm_secure/myFacilitiesFunctions.php';require_once 'urm_secure/customFacilityFunctions.php';require_once 'urm_secure/surveyHomeFunctions.php';require_once 'urm_secure/sessionStateFunctions.php';if(!loggedin()){	//echo "userarea but not loggedin!<br/>\n";	header("Location: login.php");	exit();}if(!isset($_SESSION['username']) || !isset($_SESSION['userid'])){	$errorMsg= "error: un or userid not set";	$_SESSION['errorMsg'] = $errorMsg;	header('Location: errorPage.php');  	exit();}$un = $_SESSION['username'];$userId = $_SESSION['userid'];savePostAndSessionVars($userId,$_POST,$_SESSION,"surveyHome.php");if(isset($_SESSION['userFacilityId'])) unset($_SESSION['userFacilityId']);if(isset($_SESSION['customFacilityId'])) unset($_SESSION['customFacilityId']);//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------     $myFacilitiesRows = getMyFacilitiesRowsHtml_WithStatus($userId);if(!$myCustomFacilitiesRows = getMyCustomFacilitiesRowsHtml_WithStatus($userId)){	$errorMsg="Error in getmycustomfacilitiesrowshtml_withstatus(un)";	$_SESSION['errorMsg'] = $errorMsg;	header('Location: errorPage.php');	exit();}?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Survey Home</title><link rel="stylesheet" type="text/css" href="css/reset-min.css"/><link rel="stylesheet" type="text/css" href="css/styles.css" /><link rel="stylesheet" type="text/css" href="css/tables.css" /><script type="text/javascript" src="js/jquery-1.6.2.min.js"></script><script type="text/javascript" src="js/tables.js"></script><script type="text/javascript">                                         $(document).ready(function() {	   // THIS SETS THE UNDEFINED BOXES TO YELLOW.	   $('td[id$="0"]').css("background-color","yellow");	   	   $("a#clearFacilitiesLink").click(function(e) {		   	 e.preventDefault();       		 alert("Clearing all of your user's facility entries");		     document.forms["clearFacilitiesForm"].submit();	   });	   $("a#clearCustomFacilitiesLink").click(function(e) {		   	 e.preventDefault();     		 alert("Clearing all of your user created facility entries");		     document.forms["clearCustomFacilitiesForm"].submit();	   });	   $(".userFacilityRow").click(function() {		     var rowId = $(this).closest(".userFacilityRow").attr("id");		     var name = $(this).closest(".userFacilityRow").children(".nameCell:first").attr("id");//		     alert("Facility: "+name +" was chosen.");		     //now add to the form 'chooseAction' and submit it.		     $("#chooseAction").children("input#userFacilityId").val(rowId);//		     $("#chooseAction").children("input#facilityName").val(name);		     		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );		     document.forms["chooseAction"].submit();		   });	   $(".customFacilityRow").click(function() {		     var rowId = $(this).closest(".customFacilityRow").attr("id");		     var name = $(this).closest(".customFacilityRow").children(".nameCell:first").attr("id");//		     alert("Facility: "+name +" was chosen.");		     //now add to the form 'chooseAction' and submit it.		     $("#chooseAction2").children("input#customFacilityId").val(rowId);//		     $("#chooseAction2").children("input#facilityName").val(name);		     		     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );		     document.forms["chooseAction2"].submit();		   });});</script>  </head><body><?php// include the top header barinclude("includes/header.php");?><h3>Survey Home </h3><div><h4>My Facilities:</h4><p>Click on a facility to see its survey categories.</p><table>	<thead>	  <?php if(defined('DEBUG')){?>		<th>Id</th>	  <?php }?>		<th>Name</th>		<th>Street Address</th>		<th>City</th>		<th>State</th>		<th>Zip Code</th>		<th>Facility Type</th>		<th>Survey<br/>Completion<br/>Status</th>	</thead>	<tbody>	<?php	echo $myFacilitiesRows;  // identified by "userFacilityRow" in a cell.	?>	</tbody></table></div><div><h4>My User Created Facilities:</h4><p>Click on a user created facility to see its survey categories.</p><table><col class="1"><col class="2"/><col class="3"/><col class="4"/><col class="5"/><col class="6"/><col class="7"/>	<thead>	   <?php if(defined('DEBUG')){?>		<th>Id</th>	   <?php }?>		<th>Name</th>		<th>Street Address</th>		<th>City</th>		<th>State</th>		<th>Zip Code</th>		<th>Facility Type</th>		<th>Survey<br/>Completion<br/>Status</th>	</thead>	<tbody>	<?php	echo $myCustomFacilitiesRows;  // identified by "customFacilityRow" in a cell.	?>	</tbody></table></div><hr/><form id="chooseAction" name="chooseAction"	action="surveyCategories.php" method="post"><input type="hidden"	id="userFacilityId" name="userFacilityId" value="nothing" /> 	</form><form id="chooseAction2" name="chooseAction2"	action="surveyCategories.php" method="post"><input type="hidden"	id="customFacilityId" name="customFacilityId" value="nothing" /> 	</form></body></html><?php }catch(Exception $e){  goErrorPage($e);}?>