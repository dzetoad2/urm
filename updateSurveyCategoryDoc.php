 <?php
 try{
 
//-----------------------
 require_once 'urm/functions.php';
 
 
 //can be encrypted 64.  use base64...
function updateSurveyCategoryDoc($id,$doc)
{
	$id = cleanStrForDb($id);
	$doc = cleanStrForDb($doc);
	//$docE = base64_encode($doc);            //base64_decode()
	
	//insert into db.
	 mysql_query("update  surveyCategory  set doc='".$doc."' 
	                 where id = ".$id." ");
	 $r = mysql_affected_rows();
	 if($r == 0){
	   return false;
	 }
	 if($r == -1 ){
	 	false;
	 }
	 return true;
}
 
 
 
 
 
 //------------------------------------
 
 if(isset($_POST['updateSCD'])){
 	$doc = $_POST['doc'];

 	$id = $_POST['id'];
 	//update in the survey category table.
 	if(updateSurveyCategoryDoc($id,$doc)){
 		$statuslabel = 'Success updating survey category doc';
 	}
 	
 	$o = $doc;
 	echo '<label>Output:</label><br/>';
 	echo '<textarea class="content" >'. $o.'</textarea>';
 }
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Activity</title>
<link rel="stylesheet" type="text/css" href="css/tables.css" />
<link rel="stylesheet" type="text/css" href="css/styles.css" />
<link rel="stylesheet" type="text/css" href="css/textcontent.css" />
<link rel="stylesheet" type="text/css" href="css/formStyles.css" />
</head>
 
<body>
<?php if(isset($statusLabel) && $statusLabel!=''){ ?>
<h3 id="statusLabel" class="statusLabel"><?php echo $statusLabel; ?></h3>
<?php }?>
<?php if(isset($errorLabel) && $errorLabel!=''){?>
<h3 class="errorLabel"><?php echo $errorLabel;?></h3>
<?php }?>

 <form id="submitSurveyForm" name="udpate_s_c_d" action="updateSurveyCategoryDoc.php" method="post">
  
Survey Category id:
<input type="text" id="id" name="id"></input>  
Doc: <br />
<textarea class="content" name="doc"  >
</textarea></br>
  <input   type="submit" id="updateSCD" name="updateSCD" value="update SCD" />
 </form>
</body>
 
 <?php 
}catch(Exception $e){
  goErrorPage($e);
}?>