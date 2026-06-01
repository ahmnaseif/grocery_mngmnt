<?php
include_once('main.php');
include_once('auto_id.php');

class Order extends Main {

    public function placeOrder($customerId, $cart, $paymentMethod, $deliveryAddress) {
        $autonumber = new AutoNumber();
        $orderId = $autonumber->NumberGeneration("orderId", "orders_tbl", "ORD");

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $stmt = $this->dbResult->prepare(
            "INSERT INTO orders_tbl (orderId, customerId, orderTotal, orderStatus, paymentMethod, deliveryAddress) VALUES (?, ?, ?, 'Pending', ?, ?)"
        );
        $stmt->bind_param("ssdss", $orderId, $customerId, $total, $paymentMethod, $deliveryAddress);

        if (!$stmt->execute()) {
            return json_encode(['status' => false, 'message' => 'Failed to create order']);
        }

        foreach ($cart as $productId => $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $itemStmt = $this->dbResult->prepare(
                "INSERT INTO order_items_tbl (orderId, productId, productName, productPrice, quantity, itemTotal) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $itemStmt->bind_param("sssdid", $orderId, $productId, $item['name'], $item['price'], $item['quantity'], $itemTotal);
            $itemStmt->execute();

            $stockStmt = $this->dbResult->prepare(
                "UPDATE product_tbl SET productStock = productStock - ? WHERE productId = ? AND productStock >= ?"
            );
            $stockStmt->bind_param("isi", $item['quantity'], $productId, $item['quantity']);
            $stockStmt->execute();
        }

        return json_encode(['status' => true, 'orderId' => $orderId, 'total' => $total]);
    }

    public function getOrdersByCustomer($customerId) {
        $stmt = $this->dbResult->prepare(
            "SELECT * FROM orders_tbl WHERE customerId = ? AND d_status = 0 ORDER BY orderDate DESC"
        );
        $stmt->bind_param("s", $customerId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getOrderItems($orderId) {
        $stmt = $this->dbResult->prepare(
            "SELECT * FROM order_items_tbl WHERE orderId = ?"
        );
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
