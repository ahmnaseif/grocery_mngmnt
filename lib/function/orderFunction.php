<?php
include_once('main.php');
include_once('auto_id.php');

class Order extends Main {

    // ── Customer places order ────────────────────────────────
    public function placeOrder($customerId, $cart, $paymentMethod, $deliveryAddress) {
        $autonumber = new AutoNumber();
        $orderId = $autonumber->NumberGeneration("orderId", "orders_tbl", "ORD");

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $stmt = $this->dbResult->prepare(
            "INSERT INTO orders_tbl (orderId, customerId, orderTotal, orderStatus, paymentMethod, deliveryAddress)
             VALUES (?, ?, ?, 'Pending', ?, ?)"
        );
        $stmt->bind_param("ssdss", $orderId, $customerId, $total, $paymentMethod, $deliveryAddress);

        if (!$stmt->execute()) {
            return json_encode(['status' => false, 'message' => 'Failed to create order']);
        }

        foreach ($cart as $productId => $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $itemStmt = $this->dbResult->prepare(
                "INSERT INTO order_items_tbl (orderId, productId, productName, productPrice, quantity, itemTotal)
                 VALUES (?, ?, ?, ?, ?, ?)"
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

    // ── Get orders by customer ───────────────────────────────
    public function getOrdersByCustomer($customerId) {
        $stmt = $this->dbResult->prepare(
            "SELECT * FROM orders_tbl WHERE customerId = ? AND d_status = 0 ORDER BY orderDate DESC"
        );
        $stmt->bind_param("s", $customerId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ── Get order items ──────────────────────────────────────
    public function getOrderItems($orderId) {
        $stmt = $this->dbResult->prepare(
            "SELECT * FROM order_items_tbl WHERE orderId = ?"
        );
        $stmt->bind_param("s", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ── EMPLOYEE: Get all pending orders ─────────────────────
    public function getPendingOrders() {
        $stmt = $this->dbResult->prepare(
            "SELECT o.*, c.customerName, c.customerPhone
             FROM orders_tbl o
             LEFT JOIN customer_tbl c ON o.customerId = c.customerID
             WHERE o.orderStatus = 'Pending' AND o.d_status = 0
             ORDER BY o.orderDate ASC"
        );
        $stmt->execute();
        return $stmt->get_result();
    }

    // ── EMPLOYEE: Get all orders (for management view) ────────
    public function getAllOrders() {
        $stmt = $this->dbResult->prepare(
            "SELECT o.*, c.customerName, c.customerPhone
             FROM orders_tbl o
             LEFT JOIN customer_tbl c ON o.customerId = c.customerID
             WHERE o.d_status = 0
             ORDER BY o.orderDate DESC"
        );
        $stmt->execute();
        return $stmt->get_result();
    }

    // ── EMPLOYEE: Approve order ───────────────────────────────
    public function approveOrder($orderId, $employeeId) {
        $stmt = $this->dbResult->prepare(
            "UPDATE orders_tbl SET orderStatus = 'Approved', employeeId = ?
             WHERE orderId = ? AND orderStatus = 'Pending'"
        );
        $stmt->bind_param("ss", $employeeId, $orderId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return json_encode(['status' => true, 'message' => 'Order approved successfully']);
        }
        return json_encode(['status' => false, 'message' => 'Could not approve order']);
    }

    // ── EMPLOYEE: Reject order ────────────────────────────────
    public function rejectOrder($orderId, $employeeId) {
        $stmt = $this->dbResult->prepare(
            "UPDATE orders_tbl SET orderStatus = 'Rejected', employeeId = ?
             WHERE orderId = ? AND orderStatus = 'Pending'"
        );
        $stmt->bind_param("ss", $employeeId, $orderId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return json_encode(['status' => true, 'message' => 'Order rejected']);
        }
        return json_encode(['status' => false, 'message' => 'Could not reject order']);
    }

    // ── DELIVERY: Get approved orders (ready for pickup) ──────
    public function getApprovedOrders() {
        $stmt = $this->dbResult->prepare(
            "SELECT o.*, c.customerName, c.customerPhone
             FROM orders_tbl o
             LEFT JOIN customer_tbl c ON o.customerId = c.customerID
             WHERE o.orderStatus = 'Approved' AND o.d_status = 0
             ORDER BY o.orderDate ASC"
        );
        $stmt->execute();
        return $stmt->get_result();
    }

    // ── DELIVERY: Get my assigned orders ──────────────────────
    public function getMyDeliveries($deliveryPersonId) {
        $stmt = $this->dbResult->prepare(
            "SELECT o.*, c.customerName, c.customerPhone
             FROM orders_tbl o
             LEFT JOIN customer_tbl c ON o.customerId = c.customerID
             WHERE o.deliveryPersonId = ? AND o.d_status = 0
             ORDER BY o.orderDate DESC"
        );
        $stmt->bind_param("s", $deliveryPersonId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // ── DELIVERY: Accept order (Out for Delivery) ─────────────
    public function acceptDelivery($orderId, $deliveryPersonId) {
        $stmt = $this->dbResult->prepare(
            "UPDATE orders_tbl SET orderStatus = 'Out for Delivery', deliveryPersonId = ?
             WHERE orderId = ? AND orderStatus = 'Approved'"
        );
        $stmt->bind_param("ss", $deliveryPersonId, $orderId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return json_encode(['status' => true, 'message' => 'Order accepted for delivery']);
        }
        return json_encode(['status' => false, 'message' => 'Could not accept order']);
    }

    // ── DELIVERY: Mark as Delivered ───────────────────────────
    public function markDelivered($orderId, $deliveryPersonId) {
        $stmt = $this->dbResult->prepare(
            "UPDATE orders_tbl SET orderStatus = 'Delivered'
             WHERE orderId = ? AND deliveryPersonId = ? AND orderStatus = 'Out for Delivery'"
        );
        $stmt->bind_param("ss", $orderId, $deliveryPersonId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return json_encode(['status' => true, 'message' => 'Order marked as delivered']);
        }
        return json_encode(['status' => false, 'message' => 'Could not update order']);
    }

    // ── DELIVERY: Mark as Failed ──────────────────────────────
    public function markFailed($orderId, $deliveryPersonId) {
        $stmt = $this->dbResult->prepare(
            "UPDATE orders_tbl SET orderStatus = 'Cancelled'
             WHERE orderId = ? AND deliveryPersonId = ? AND orderStatus = 'Out for Delivery'"
        );
        $stmt->bind_param("ss", $orderId, $deliveryPersonId);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return json_encode(['status' => true, 'message' => 'Order marked as failed']);
        }
        return json_encode(['status' => false, 'message' => 'Could not update order']);
    }
}
?>
