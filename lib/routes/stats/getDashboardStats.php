<?php
include_once('../../function/main.php');

header('Content-Type: application/json');

$main = new Main();
$conn = $main->dbResult;

function getCount($conn, $sql) {
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['cnt'];
    }
    return 0;
}

$customers  = getCount($conn, "SELECT COUNT(*) as cnt FROM customer_tbl WHERE d_status = 0");
$employees  = getCount($conn, "SELECT COUNT(*) as cnt FROM employee_tbl WHERE d_status = 0");
$products   = getCount($conn, "SELECT COUNT(*) as cnt FROM product_tbl WHERE d_status = 0");

echo json_encode([
    'customers' => $customers,
    'employees' => $employees,
    'products'  => $products,
    'orders'    => 0
]);
?>
