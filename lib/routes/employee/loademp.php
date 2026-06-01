<?php

//include the function page
include_once('../../function/empFunction.php');

$empObj = new Employee();

$result = $empObj->loadData();
echo($result);

?>