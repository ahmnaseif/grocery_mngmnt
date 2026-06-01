<?php

//include the function page
include_once('../../function/customerFunction.php');


$name= $_POST['customerName'];
$email = $_POST['customerEmail'];
$nic= $_POST['customerNIC'];
$phoneno = $_POST['customerPhone'];
$birthday= $_POST['customerBirthday'];
$gender = $_POST['customerGender'];
$userid= $_POST['customerid'];

$customerObj = new Customer();

$result = $customerObj->editCustomer($name, $email, $nic, $phoneno, $birthday, $gender, $userid);

echo($result);
?>