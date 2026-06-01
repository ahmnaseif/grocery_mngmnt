<?php

//include the function page
include_once('../../function/customerFunction.php');


$name= $_POST['customerName'];
$email = $_POST['customerEmail'];
$nic= $_POST['customerNIC'];
$phoneno = $_POST['customerPhone'];
$gender = $_POST['customerGender'];
$birthday= $_POST['customerBirthday'];
$age= $_POST['customerAge'];
$passwd= $_POST['customerPasswd'];
// $confpasswd= $_POST['customerConfPasswd'];


$customerObj = new Customer();

$result = $customerObj->addCustomer($name, $email, $nic, $phoneno, $gender, $birthday, $age, $passwd);

echo($result);
?>