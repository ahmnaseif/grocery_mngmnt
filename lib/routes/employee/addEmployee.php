<?php

//include the function page
include_once('../../function/empFunction.php');


$employeeName = $_POST['employeeName'];
$employeeEmail = $_POST['employeeEmail'];
$employeeNIC= $_POST['employeeNIC'];
$employeePhone = $_POST['employeePhone'];
$employeeGender = $_POST['employeeGender'];
$employeePassword= $_POST['employeePassword'];

$empObj = new Employee();

$result = $empObj->addEmployee($employeeName, $employeeEmail, $employeeNIC, $employeePhone, $employeeGender, $employeePassword);

echo($result);
?>