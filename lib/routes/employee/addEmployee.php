<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin'])) {
    http_response_code(403);
    echo json_encode(['status' => false, 'message' => 'Unauthorized']);
    exit;
}

include_once('../../function/empFunction.php');

$employeeName     = $_POST['employeeName']     ?? '';
$employeeEmail    = $_POST['employeeEmail']    ?? '';
$employeeNIC      = $_POST['employeeNIC']      ?? '';
$employeePhone    = $_POST['employeePhone']    ?? '';
$employeeGender   = $_POST['employeeGender']   ?? '';
$employeePassword = $_POST['employeePassword'] ?? '';
$employeeRole     = $_POST['employeeRole']     ?? 'employee';

$empObj = new Employee();
$result = $empObj->addEmployee($employeeName, $employeeEmail, $employeeNIC, $employeePhone, $employeeGender, $employeePassword, $employeeRole);

echo $result;
?>
