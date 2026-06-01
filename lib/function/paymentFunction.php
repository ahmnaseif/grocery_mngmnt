<?php
include_once('main.php');

class PaymentMethod extends Main {

    public function getByCustomer($customerId) {
        $stmt = $this->dbResult->prepare(
            "SELECT * FROM payment_methods_tbl WHERE customerId = ? AND d_status = 0 ORDER BY isDefault DESC, methodId DESC"
        );
        $stmt->bind_param("s", $customerId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function addMethod($customerId, $cardHolderName, $cardLastFour, $cardExpiry, $cardType) {
        $stmt = $this->dbResult->prepare(
            "INSERT INTO payment_methods_tbl (customerId, cardHolderName, cardLastFour, cardExpiry, cardType) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $customerId, $cardHolderName, $cardLastFour, $cardExpiry, $cardType);
        if ($stmt->execute()) {
            return json_encode(['status' => true, 'methodId' => $this->dbResult->insert_id]);
        }
        return json_encode(['status' => false, 'message' => 'Failed to save card']);
    }

    public function deleteMethod($methodId, $customerId) {
        $stmt = $this->dbResult->prepare(
            "UPDATE payment_methods_tbl SET d_status = 1 WHERE methodId = ? AND customerId = ?"
        );
        $stmt->bind_param("is", $methodId, $customerId);
        if ($stmt->execute()) {
            return json_encode(['status' => true]);
        }
        return json_encode(['status' => false]);
    }
}
?>
