<?php

//include the function page
include_once('../../function/empFunction.php');

$empObj = new Employee();
$empid = $_GET['empid'];


$result = $empObj->deleteById($empid);

echo($result);
?>