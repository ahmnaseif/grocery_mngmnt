 <?php
//start sessions
session_start();

// //unset all session variables
$_SESSION = array();

// //destory the session 
// session_reset();
session_destroy();

header('Location:../../login.php');
exit;


?>