<?php

//include the function page
include_once('../../function/AuthFunction.php');


$email1 = $_POST['loginEmail'];
$passwd1= $_POST['loginPasswd'];


$customerObj = new Auth();

$result = $customerObj->authentication2($email1, $passwd1);

echo($result);





?>