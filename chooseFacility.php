<?php 
try{

require_once 'urm_secure/functions.php';
require_once 'urm_secure/facilityFunctions.php';
require_once 'urm_secure/validationFunctions.php';

if(!loggedin()){
  //echo "userarea but not loggedin!<br/>\n";
  header("Location: login.php");
  exit();
}
$showFacilities = FALSE;
if ( isset($_POST['chooseFacility'])){
  if(!isset($_SESSION['username'])){
  	//header("Location: login.php"); 
  	
  	
  	
  	
  	//throw exception instead???
  	$_SESSION['errorMsg'] = "Error: Username not set, impossible to continue! <br/>";
  	header('Location: errorPage.php');
  	exit();
  	
  	
  	
  	
  }
  //read the zip from the box. if its not a pos int, show error message, quit.
  $errorLabel='';
  $zip = trim($_POST['zip']);
  if(!isValidPartialZip($zip)){
   $errorLabel .= 'Please enter a partial (3 digits or more) or complete zip code<br/>';
  }
  $type = trim($_POST['facilityTypeAbbrev']);
  if($type!= 'LTACH' && $type!='HOSP' & $type!='PR'){
  	$errorLabel .= 'Please choose a facility type<br/>';
  }
  if($errorLabel==''){
    $showFacilities = TRUE;
    $facilityRows = getFacilityRowsHtml($zip,$type);
    if($facilityRows==''){
      $errorLabel .= "No facilities found";
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
<link rel="stylesheet" type="text/css" href="css/tables.css" />

<title>Choose Facility</title>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript">                                         
$(document).ready(function() {
// to selecd an id:   $("#orderedlist").addClass("red");
// select a class:     $(".myClass").css("border","3px solid red");<
	   $('table tbody  tr:nth-child(even)').addClass('alt');
	   $('table tbody tr').mouseover(function(){
		 $(this).addClass('over');
	   });
	   $('table tbody tr').mouseout(function(){
			 $(this).removeClass('over');
	   });
	   $(this).addClass('mouseover');
	   
	   $(".facilityRow").click(function() {
	     var rowId = $(this).closest(".facilityRow").attr("id");
	     var name = $(this).closest(".facilityRow").children(".nameCell:first").attr("id");
 		//-------------
	     //alert("Facility: "+name +" was chosen.");
	    //-------------
	     //now add to the form 'chooseAction' and submit it.
	     //$("#chooseAction").append('<input type="hidden" id="rowId" name="rowId" value="'+rowId+'"');
	     $("#chooseAction").children("input#facilityId").val(rowId);
	     //$.post("userarea.php", { id: rowId, source: "chooseFacility" } );
	     document.forms["chooseAction"].submit();
	   });
	 });
</script>  
</head>
<body>
<div class="header"> 
  <?php  echo "You are logged in as: ". $_SESSION['username'].'<br/>';?>
  <a href="logout.php">Log out</a> | 
  <a href="myFacilities.php">Back to Choose Facilities</a>
 </div>
<hr/>
<?php if (isset($errorLabel) && $errorLabel!='') {?>
<h4 class="errorLabel"><?php echo $errorLabel;?></h4>
<?php }?>
<?php if (isset($statusLabel) && $statusLabel!='') {?>
<h4 class="statusLabel"><?php echo $statusLabel;?></h4>
<?php }?>
<form   action="chooseFacility.php" method="post">
 <div class="inputunit row">
 <h3>Choose your facility:</h3>
 <fieldset class="inputBox">
   <ul>
    <li>
     Zipcode*:<br/>
     <input type="text" name="zip" /> 
    </li>
    <li>
     Type:<br/>
     <select name="facilityTypeAbbrev">
       <option value="HOSP">HOSP</option>
       <option value="LTACH">LTACH</option>
       <option value="PR">PR</option>
     </select>
    </li>
   </ul>
 </fieldset>
 <br/>
 <div class="gray">
 *: Zipcode may be a partial match. The proper form is either xxxxx or xxxxx-yyyy.
</div>
 </div>
 <input type="submit" name="chooseFacility" value="Show Facilities" />
 
</form>

<?php if($showFacilities){ ?>
<div>

 <div>
  <label>Can't find your facility?</label> 
  <a href="createCustomFacility.php">Add a user created facility</a><br/>
 </div>
 <h4>Click on your facility in the list to continue:</h4>
 <form id="chooseAction" name="chooseAction" action="specifyFacilityInfo.php" method="post">
    <input type="hidden" id="facilityId" name="facilityId" value="nothing"/>
 </form>

 <table>
  <thead>
      <?php if(defined('DEBUG')){ ?>
      <th>Id</th> 
      <?php } ?>
      <th>Zip Code</th>
      <th>Name</th>
      <th>Type</th>
      <th>Street Address</th>
      <th>City</th><th>State</th>
      </thead>
  <tbody>
  <?php 
  echo $facilityRows;
  ?>
  </tbody>
 </table>
 
 
 <div>
  <label>Can't find your facility?</label> 
  <a href="createCustomFacility.php">Add a user created facility</a><br/>
 </div>

</div>



<?php } ?>
</body>
</html>
<?php 
}catch(Exception $e){
  goErrorPage($e);
}?>