<?php

//include the function page
include_once('../../function/empFunction.php');
$empObj = new Employee();
$empid = $_GET['empid'];

$result = $empObj->loadDataById($empid);
echo($result);

?>