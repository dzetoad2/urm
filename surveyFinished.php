<?phptry{require_once('urm_secure/functions.php');require_once('urm_secure/logout_noRedirect.php');//------------------- NOW GET ALL THE ROW DATA , THIS IS AFTER ALL CHANGES HAVE BEEN UPDATED.---------------  ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Survey Home</title><link rel="stylesheet" type="text/css" href="css/reset-min.css"/><link rel="stylesheet" type="text/css" href="css/styles.css" /><link rel="stylesheet" type="text/css" href="css/tables.css" /><script type="text/javascript" src="js/jquery-1.6.2.min.js"></script><script type="text/javascript" src="js/tables.js"></script><script type="text/javascript">                                         $(document).ready(function() {	  });</script>  </head><body><?php// include the top header barinclude("includes/header_notLoggedIn.php");?><h3>Survey Home </h3> <div>  <h4>Thank you for participating in the 2012 AARC Uniform Reporting Manual Survey. Your data has been submitted and you have been automatically logged out.</h4></div> <hr/> </body></html><?php }catch(Exception $e){  goErrorPage($e);}?>