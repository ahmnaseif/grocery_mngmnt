<?php
session_start();
if (!isset($_SESSION['user']) || !in_array($_SESSION['usertype'], ['admin', 'employee'])) {
    header('Location: ../../login.php');
    exit;
}
$isAdmin = $_SESSION['usertype'] === 'admin';

$currentpage = basename($_SERVER['PHP_SELF']);
$sidebar_html = $isAdmin ? '' : '
<aside id="sidebar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <div class="row">
            <div class="col"><span class="brand-text fw-light">Employee Panel</span></div>
            <div class="col"><button id="toggleSidebar" class="sidebar-toggle-btn"><i class="bi bi-list"></i></button></div>
        </div>
    </div>
    <div class="sidebar-wrapper"><nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
            <li class="nav-item menu-open"><ul class="nav nav-treeview">
                <li class="nav-item"><a href="/grocery_mngmnt/index.php" class="nav-link"><i class="nav-icon bi bi-shop"></i><p>Go to Shop</p></a></li>
                <li class="nav-item"><a href="dshbdemployee.php" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p></a></li>
                <li class="nav-item"><a href="emp_ordermngmnt.php" class="nav-link active"><i class="nav-icon bi bi-bag-check"></i><p>Order Management</p></a></li>
                <li class="nav-item"><a href="productmngmnt.php" class="nav-link"><i class="nav-icon bi bi-box-seam"></i><p>Products</p></a></li>
            </ul></li>
        </ul>
    </nav></div>
</aside>';
?>
<!doctype html>
<html lang="en">
<head>
<title>Order Management — Employee</title>
<style>
.status-Pending         { background:#fff3cd; color:#856404; }
.status-Approved        { background:#cff4fc; color:#055160; }
.status-Out-for-Delivery{ background:#d1ecf1; color:#0c5460; }
.status-Delivered       { background:#d4edda; color:#155724; }
.status-Rejected        { background:#f8d7da; color:#721c24; }
.status-Cancelled       { background:#f8d7da; color:#721c24; }
.status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.app-main { background: #f4f6f9; min-height: 100vh; }
.filter-tab { cursor:pointer; padding: 8px 18px; border-radius: 20px; font-size:14px; font-weight:500; border:1px solid #dee2e6; background:white; transition:0.2s; }
.filter-tab.active, .filter-tab:hover { background:#198754; color:white; border-color:#198754; }
</style>
</head>

<?php if (!$isAdmin && $sidebar_html) { /* non-empty means employee */ } ?>
<?php include_once('common.php'); ?>

<main class="app-main">
  <div class="app-content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0"><i class="bi bi-bag-check text-success me-2"></i>Order Management</h4>
      <span class="text-muted small">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></strong></span>
    </div>

    <!-- Filter Tabs -->
    <div class="d-flex gap-2 flex-wrap mb-4">
      <button class="filter-tab active" onclick="loadOrders('all', this)">All Orders</button>
      <button class="filter-tab" onclick="loadOrders('Pending', this)">⏳ Pending</button>
      <button class="filter-tab" onclick="loadOrders('Approved', this)">✅ Approved</button>
      <button class="filter-tab" onclick="loadOrders('Out for Delivery', this)">🚚 Out for Delivery</button>
      <button class="filter-tab" onclick="loadOrders('Delivered', this)">📦 Delivered</button>
      <button class="filter-tab" onclick="loadOrders('Rejected', this)">❌ Rejected</button>
    </div>

    <!-- Orders Table -->
    <div class="card shadow border-0 p-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Phone</th>
              <th>Amount</th>
              <th>Payment</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="ordersList">
            <tr><td colspan="8" class="text-center py-4 text-muted">Loading orders...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>

<!-- Order Items Modal -->
<div class="modal fade" id="orderItemsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Details — <span id="modalOrderId"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalBody">Loading...</div>
      <div class="modal-footer" id="modalFooter"></div>
    </div>
  </div>
</div>

<script src="../../js/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sweetalert2.js"></script>
<script>
$('#toggleSidebar').click(function () {
  $('#sidebar').toggleClass('collapsed');
  $('main.app-main').toggleClass('expanded');
});

function loadOrders(status, btn) {
  // Update active tab
  $('.filter-tab').removeClass('active');
  $(btn).addClass('active');

  $.getJSON('../routes/order/getOrders.php', { status: status }, function(data) {
    if (!data || data.length === 0) {
      $('#ordersList').html('<tr><td colspan="8" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-4 d-block mb-2"></i>No orders found</td></tr>');
      return;
    }
    let html = '';
    data.forEach(function(o) {
      let statusClass = 'status-' + o.orderStatus.replace(/ /g, '-');
      let actions = '';
      if (o.orderStatus === 'Pending') {
        actions = `
          <button class="btn btn-sm btn-success me-1" onclick="approveOrder('${o.orderId}')"><i class="bi bi-check-lg"></i> Approve</button>
          <button class="btn btn-sm btn-danger" onclick="rejectOrder('${o.orderId}')"><i class="bi bi-x-lg"></i> Reject</button>`;
      } else {
        actions = `<button class="btn btn-sm btn-outline-secondary" onclick="viewItems('${o.orderId}')"><i class="bi bi-eye"></i> View</button>`;
      }
      html += `<tr>
        <td><a href="#" onclick="viewItems('${o.orderId}'); return false;" class="text-decoration-none fw-semibold">${o.orderId}</a></td>
        <td>${o.customerName || '—'}</td>
        <td>${o.customerPhone || '—'}</td>
        <td class="text-success fw-semibold">Rs. ${parseFloat(o.orderTotal).toFixed(2)}</td>
        <td>${o.paymentMethod === 'cash_on_delivery' ? 'Cash on Delivery' : 'Card'}</td>
        <td>${new Date(o.orderDate).toLocaleDateString('en-GB')}</td>
        <td><span class="status-badge ${statusClass}">${o.orderStatus}</span></td>
        <td>${actions}</td>
      </tr>`;
    });
    $('#ordersList').html(html);
  }).fail(function() {
    $('#ordersList').html('<tr><td colspan="8" class="text-center text-danger py-4">Failed to load orders</td></tr>');
  });
}

function approveOrder(orderId) {
  Swal.fire({
    title: 'Approve this order?',
    text: `Order ${orderId} will be sent to delivery.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    confirmButtonText: 'Yes, Approve'
  }).then(result => {
    if (result.isConfirmed) {
      $.post('../routes/order/approveOrder.php', { orderId }, function(res) {
        let r = JSON.parse(res);
        if (r.status) {
          Swal.fire({ icon: 'success', title: 'Order Approved!', timer: 1500, showConfirmButton: false });
          loadOrders('all', $('.filter-tab.active')[0]);
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: r.message });
        }
      });
    }
  });
}

function rejectOrder(orderId) {
  Swal.fire({
    title: 'Reject this order?',
    text: `Order ${orderId} will be marked as rejected.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Yes, Reject'
  }).then(result => {
    if (result.isConfirmed) {
      $.post('../routes/order/rejectOrder.php', { orderId }, function(res) {
        let r = JSON.parse(res);
        if (r.status) {
          Swal.fire({ icon: 'success', title: 'Order Rejected', timer: 1500, showConfirmButton: false });
          loadOrders('all', $('.filter-tab.active')[0]);
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: r.message });
        }
      });
    }
  });
}

function viewItems(orderId) {
  $('#modalOrderId').text(orderId);
  $('#modalBody').html('<div class="text-center py-3"><div class="spinner-border text-success"></div></div>');
  $('#modalFooter').html('');
  new bootstrap.Modal(document.getElementById('orderItemsModal')).show();
  $.getJSON('../routes/order/getOrderItems.php', { orderId }, function(data) {
    if (!data || data.length === 0) {
      $('#modalBody').html('<p class="text-muted">No items found.</p>');
      return;
    }
    let html = '<table class="table table-sm table-bordered"><thead class="table-success"><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>';
    let total = 0;
    data.forEach(item => {
      total += parseFloat(item.itemTotal);
      html += `<tr><td>${item.productName}</td><td>Rs. ${parseFloat(item.productPrice).toFixed(2)}</td><td>${item.quantity}</td><td>Rs. ${parseFloat(item.itemTotal).toFixed(2)}</td></tr>`;
    });
    html += `</tbody><tfoot><tr><td colspan="3" class="text-end fw-bold">Total</td><td class="fw-bold text-success">Rs. ${total.toFixed(2)}</td></tr></tfoot></table>`;
    $('#modalBody').html(html);
  });
}

// Load all orders on page load
loadOrders('all', $('.filter-tab.active')[0]);
</script>
</body>
</html>
