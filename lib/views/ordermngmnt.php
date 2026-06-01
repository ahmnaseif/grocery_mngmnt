<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    header('Location: ../../login.php');
    exit;
}

include_once('../function/orderFunction.php');
$orderObj   = new Order();
$customerId = $_SESSION['user'];
$ordersResult = $orderObj->getOrdersByCustomer($customerId);

$page_title = 'My Orders';
ob_start();
?>

<?php if ($ordersResult->num_rows === 0): ?>
  <div class="alert alert-info d-flex align-items-center gap-2">
    <i class="fas fa-info-circle"></i>
    You haven't placed any orders yet.
    <a href="/grocery_mngmnt/index.php" class="alert-link ms-1">Start shopping!</a>
  </div>
<?php else: ?>
  <div class="accordion" id="ordersAccordion">
  <?php while ($order = $ordersResult->fetch_assoc()):
      $items = $orderObj->getOrderItems($order['orderId']);
      $statusColors = [
          'Pending'    => 'warning',
          'Processing' => 'info',
          'Delivered'  => 'success',
          'Cancelled'  => 'danger',
      ];
      $badge = $statusColors[$order['orderStatus']] ?? 'secondary';
  ?>
    <div class="accordion-item mb-3 shadow-sm border-0 rounded-3 overflow-hidden">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-semibold" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#order-<?php echo $order['orderId']; ?>">
          <div class="d-flex justify-content-between w-100 me-3 align-items-center">
            <span>
              <strong><?php echo $order['orderId']; ?></strong>
              &nbsp;&mdash;&nbsp;
              <span class="text-muted fw-normal"><?php echo date('d M Y, h:i A', strtotime($order['orderDate'])); ?></span>
            </span>
            <span class="d-flex align-items-center gap-2">
              <span class="badge bg-<?php echo $badge; ?>"><?php echo $order['orderStatus']; ?></span>
              <strong class="text-success">Rs. <?php echo number_format($order['orderTotal'], 2); ?></strong>
            </span>
          </div>
        </button>
      </h2>
      <div id="order-<?php echo $order['orderId']; ?>" class="accordion-collapse collapse">
        <div class="accordion-body bg-light">
          <p class="text-muted small mb-3">
            <i class="fas fa-credit-card me-1"></i>
            <?php echo $order['paymentMethod'] === 'cash_on_delivery' ? 'Cash on Delivery' : 'Card Payment'; ?>
            <?php if ($order['deliveryAddress']): ?>
              &nbsp;&bull;&nbsp;
              <i class="fas fa-map-marker-alt me-1"></i>
              <?php echo htmlspecialchars($order['deliveryAddress']); ?>
            <?php endif; ?>
          </p>
          <table class="table table-sm table-bordered bg-white rounded">
            <thead class="table-success">
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
            <?php while ($item = $items->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($item['productName']); ?></td>
                <td>Rs. <?php echo number_format($item['productPrice'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td class="text-end">Rs. <?php echo number_format($item['itemTotal'], 2); ?></td>
              </tr>
            <?php endwhile; ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold text-success">Rs. <?php echo number_format($order['orderTotal'], 2); ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  </div>
<?php endif; ?>

<?php
$page_content = ob_get_clean();
include_once('commoncstmr.php');
?>
