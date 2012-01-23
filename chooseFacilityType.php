<?php
try{


//----------required on every secure page----------------
require_once 'urm_secure/functions.php';
if(!loggedin()){
	//echo "userarea but not loggedin!<br/>\n";
	header("Location: login.php");
	exit();
}
//----------end required on every secure page----------------
require_once('urm_secure/facilityTypeFunctions.php');

if(!isset($_POST['userFacilityId']) && !isset($_POST['customFacilityId'])){
	$errorMsg="neither  userFacilityId nor customFacilityId was set - error.";
	$_SESSION['errorMsg'] = $errorMsg;
	header('Location: errorPage.php');
	exit();
}
if(isset($_POST['userFacilityId'])){
	$userFacilityId = $_POST['userFacilityId'];
	$_SESSION['userFacilityId'] = $userFacilityId;
	unset($_SESSION['customFacilityId']);
	$fid=$userFacilityId;
	$is_cf=0;
}
if(isset($_POST['customFacilityId'])){
	$customFacilityId = $_POST['customFacilityId'];
	unset($_SESSION['userFacilityId']);
	$_SESSION['customFacilityId'] = $customFacilityId;
	$fid=$customFacilityId;
	$is_cf=1;
}
if(isset($_POST['facilityName'])){
$facilityName = $_POST['facilityName'];
}else{
	$errorMsg='choosefacilitytype.php: facilityname not set!';
	$_SESSION['errorMsg'] = $errorMsg;
	header('Location: errorPage.php');
	exit();
}
if(trim($fid)!='' && trim($is_cf)!=''){
  	$facilityTypeRows = getFacilityTypeRowsHtml($fid,$is_cf);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Choose Facility Type</title>
<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />

<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/tables.js"></script>

<script type="text/javascript">                                         
$(document).ready(function() {
// to selecd an id:   $("#orderedlist").addClass("red");
// select a class:     $(".myClass").css("border","3px solid red");<
	// append - addClass... ? no...
	   $(".facilityTypeRow").click(function() {
	     var rowId = $(this).closest(".facilityTypeRow").attr("id");
	     var name = $(this).closest(".facilityTypeRow").children(".nameCell:first").attr("id");
	     
//	     alert("Facility type of "+name +" was clicked.");
	     //now add to the form 'chooseAction' and submit it.
	     //$("#chooseAction").append('<input type="hidden" id="rowId" name="rowId" value="'+rowId+'"');
	     $("#chooseAction").children("input#facilityTypeId").val(rowId);
	     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );
	     document.forms["chooseAction"].submit();
	   });
	 });
</script>
</head>

<body>
<div class="header">  
<?php  echo "You are logged in as: ". $_SESSION['username'].'<br/>';
?>
<a href="logout.php">Log out</a> |
<a href="myFacilities.php">My Facilities</a>
</div>
<hr/>
<h3>Choose facility type for: 
<?php 
 
echo $facilityName. ', ';
 if(isset($_POST['userFacilityId']) && defined('debug')){
   echo 'userfacilityid is set, it is:'.$_POST['userFacilityId'];
 }
 if(isset($_POST['customFacilityId']) && defined('debug')){
   echo 'userfacilityid is set, it is:'.$_POST['customFacilityId'];
 }
 
 ?></h3>

<div>

<form id="chooseAction" name="chooseAction" action="myFacilities.php"
	method="post">
	<input type="hidden" id="facilityTypeId" name="facilityTypeId" value="nothing" /></form>

<table>
	<thead>
		<?php if(defined('DEBUG')){?>
		<th>Id</th>
		<?php }?>
		<th>Name</th>
		<th>Description</th>
	</thead>
	<tbody>
	<?php
	echo $facilityTypeRows;
	?>
	</tbody>
</table>


</div>



<?php 
if(defined('DEBUG')){
 echo '<hr/>DEBUG:<br/>';
 if(isset($userFacilityId)) echo " userFacilityId （this is all we require to do edits back at myFacilities.php) posted:  ". $userFacilityId.'<br/>';
 if(isset($customFacilityId)) echo " customFacilityId （this is all we require to do edits back at myFacilities.php) posted:  ". $customFacilityId.'<br/>';
 echo "facility name:  ". $_POST['facilityName'].'<br/>';
}
?>
</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>