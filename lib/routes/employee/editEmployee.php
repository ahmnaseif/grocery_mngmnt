<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => false, 'message' => 'Unauthorized']);
    exit;
}

include_once('../../function/empFunction.php');

$employeeName   = $_POST['employeeName']   ?? '';
$employeeEmail  = $_POST['employeeEmail']  ?? '';
$employeeNIC    = $_POST['employeeNIC']    ?? '';
$employeePhone  = $_POST['employeePhone']  ?? '';
$employeeGender = $_POST['employeeGender'] ?? '';
$empid          = $_POST['employeeid']     ?? '';

$empObj = new Employee();
$result = $empObj->editEmployee($employeeName, $employeeEmail, $employeeNIC, $employeePhone, $employeeGender, $empid);
echo $result;
