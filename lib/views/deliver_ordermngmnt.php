<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'delivery_Person') {
    header('Location: ../../login.php');
    exit;
}

$currentpage = basename($_SERVER['PHP_SELF']);
$sidebar_html = '
<aside id="sidebar" class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <div class="row">
            <div class="col"><span class="brand-text fw-light">Delivery Panel</span></div>
            <div class="col"><button id="toggleSidebar" class="sidebar-toggle-btn"><i class="bi bi-list"></i></button></div>
        </div>
    </div>
    <div class="sidebar-wrapper"><nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
            <li class="nav-item menu-open"><ul class="nav nav-treeview">
                <li class="nav-item"><a href="/grocery_mngmnt/index.php" class="nav-link"><i class="nav-icon bi bi-shop"></i><p>Go to Shop</p></a></li>
                <li class="nav-item"><a href="dshbddeliver.php" class="nav-link"><i class="nav-icon bi bi-speedometer2"></i><p>Dashboard</p></a></li>
                <li class="nav-item"><a href="deliver_ordermngmnt.php" class="nav-link active"><i class="nav-icon bi bi-truck"></i><p>My Deliveries</p></a></li>
            </ul></li>
        </ul>
    </nav></div>
</aside>';
?>
<!doctype html>
<html lang="en">
<head>
<title>Delivery Management</title>
<style>
.status-Approved         { background:#cff4fc; color:#055160; }
.status-Out-for-Delivery { background:#fff3cd; color:#856404; }
.status-Delivered        { background:#d4edda; color:#155724; }
.status-Cancelled        { background:#f8d7da; color:#721c24; }
.status-badge { padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
.app-main { background:#f4f6f9; min-height:100vh; }
.filter-tab { cursor:pointer; padding:8px 18px; border-radius:20px; font-size:14px; font-weight:500; border:1px solid #dee2e6; background:white; transition:0.2s; }
.filter-tab.active, .filter-tab:hover { background:#ffc107; color:#212529; border-color:#ffc107; }
</style>
</head>

<?php include_once('common.php'); ?>

<main class="app-main">
  <div class="app-content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0"><i class="bi bi-truck text-warning me-2"></i>My Deliveries</h4>
      <span class="text-muted small">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></strong></span>
    </div>

    <!-- Two sections: Available + My Assigned -->
    <div class="row g-4">

      <!-- Available Orders (Approved by employee, waiting for pickup) -->
      <div class="col-12">
        <div class="card shadow border-0 p-3 mb-2">
          <h6 class="fw-bold mb-3"><span class="badge bg-info me-2">NEW</span>Available Orders — Ready for Pickup</h6>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr><th>Order ID</th><th>Customer</th><th>Phone</th><th>Address</th><th>Amount</th><th>Date</th><th>Action</th></tr>
              </thead>
              <tbody id="availableOrders">
                <tr><td colspan="7" class="text-center py-3 text-muted">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- My Assigned Deliveries -->
      <div class="col-12">
        <div class="card shadow border-0 p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-task text-warning me-2"></i>My Assigned Deliveries</h6>
            <div class="d-flex gap-2">
              <button class="filter-tab active" onclick="loadMyOrders('all', this)">All</button>
              <button class="filter-tab" onclick="loadMyOrders('Out for Delivery', this)">🚚 Active</button>
              <button class="filter-tab" onclick="loadMyOrders('Delivered', this)">✅ Delivered</button>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr><th>Order ID</th><th>Customer</th><th>Phone</th><th>Address</th><th>Amount</th><th>Date</th><th>Status</th><th>Action</th></tr>
              </thead>
              <tbody id="myOrders">
                <tr><td colspan="8" class="text-center py-3 text-muted">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<script src="../../js/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../js/sweetalert2.js"></script>
<script>
$('#toggleSidebar').click(function () {
  $('#sidebar').toggleClass('collapsed');
  $('main.app-main').toggleClass('expanded');
});

// Load available (approved) orders
function loadAvailable() {
  $.getJSON('../routes/order/getApprovedOrders.php', function(data) {
    if (!data || data.length === 0) {
      $('#availableOrders').html('<tr><td colspan="7" class="text-center py-3 text-muted"><i class="bi bi-inbox d-block fs-4 mb-1"></i>No orders waiting for pickup</td></tr>');
      return;
    }
    let html = '';
    data.forEach(o => {
      html += `<tr>
        <td class="fw-semibold">${o.orderId}</td>
        <td>${o.customerName || '—'}</td>
        <td>${o.customerPhone || '—'}</td>
        <td>${o.deliveryAddress || '—'}</td>
        <td class="text-success fw-semibold">Rs. ${parseFloat(o.orderTotal).toFixed(2)}</td>
        <td>${new Date(o.orderDate).toLocaleDateString('en-GB')}</td>
        <td><button class="btn btn-sm btn-warning fw-semibold" onclick="acceptDelivery('${o.orderId}')"><i class="bi bi-truck me-1"></i>Accept</button></td>
      </tr>`;
    });
    $('#availableOrders').html(html);
  }).fail(function() {
    $('#availableOrders').html('<tr><td colspan="7" class="text-center text-danger">Failed to load</td></tr>');
  });
}

// Load my deliveries
function loadMyOrders(status, btn) {
  $('.filter-tab').removeClass('active');
  $(btn).addClass('active');
  $.getJSON('../routes/order/getMyDeliveries.php', { status }, function(data) {
    if (!data || data.length === 0) {
      $('#myOrders').html('<tr><td colspan="8" class="text-center py-3 text-muted"><i class="bi bi-inbox d-block fs-4 mb-1"></i>No deliveries found</td></tr>');
      return;
    }
    let html = '';
    data.forEach(o => {
      let statusClass = 'status-' + o.orderStatus.replace(/ /g, '-');
      let actions = '';
      if (o.orderStatus === 'Out for Delivery') {
        actions = `
          <button class="btn btn-sm btn-success me-1" onclick="markDelivered('${o.orderId}')"><i class="bi bi-check-circle"></i> Delivered</button>
          <button class="btn btn-sm btn-danger" onclick="markFailed('${o.orderId}')"><i class="bi bi-x-circle"></i> Failed</button>`;
      } else {
        actions = `<span class="text-muted small">${o.orderStatus}</span>`;
      }
      html += `<tr>
        <td class="fw-semibold">${o.orderId}</td>
        <td>${o.customerName || '—'}</td>
        <td>${o.customerPhone || '—'}</td>
        <td>${o.deliveryAddress || '—'}</td>
        <td class="text-success fw-semibold">Rs. ${parseFloat(o.orderTotal).toFixed(2)}</td>
        <td>${new Date(o.orderDate).toLocaleDateString('en-GB')}</td>
        <td><span class="status-badge ${statusClass}">${o.orderStatus}</span></td>
        <td>${actions}</td>
      </tr>`;
    });
    $('#myOrders').html(html);
  }).fail(function() {
    $('#myOrders').html('<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>');
  });
}

function acceptDelivery(orderId) {
  Swal.fire({
    title: 'Accept this delivery?',
    text: `You will be assigned to order ${orderId}.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#ffc107',
    confirmButtonText: 'Yes, Accept'
  }).then(result => {
    if (result.isConfirmed) {
      $.post('../routes/order/acceptDelivery.php', { orderId }, function(res) {
        let r = JSON.parse(res);
        if (r.status) {
          Swal.fire({ icon: 'success', title: 'Accepted!', text: 'Order added to your deliveries.', timer: 1500, showConfirmButton: false });
          loadAvailable();
          loadMyOrders('all', $('.filter-tab.active')[0]);
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: r.message });
        }
      });
    }
  });
}

function markDelivered(orderId) {
  Swal.fire({
    title: 'Mark as Delivered?',
    icon: 'success',
    showCancelButton: true,
    confirmButtonColor: '#198754',
    confirmButtonText: 'Yes, Delivered!'
  }).then(result => {
    if (result.isConfirmed) {
      $.post('../routes/order/markDelivered.php', { orderId }, function(res) {
        let r = JSON.parse(res);
        if (r.status) {
          Swal.fire({ icon: 'success', title: 'Great job!', text: 'Order marked as delivered.', timer: 1500, showConfirmButton: false });
          loadAvailable(); loadMyOrders('all', $('.filter-tab.active')[0]);
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: r.message });
        }
      });
    }
  });
}

function markFailed(orderId) {
  Swal.fire({
    title: 'Mark as Failed?',
    text: 'This will mark the delivery as cancelled.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    confirmButtonText: 'Yes, Mark Failed'
  }).then(result => {
    if (result.isConfirmed) {
      $.post('../routes/order/markFailed.php', { orderId }, function(res) {
        let r = JSON.parse(res);
        if (r.status) {
          Swal.fire({ icon: 'info', title: 'Marked as Failed', timer: 1500, showConfirmButton: false });
          loadAvailable(); loadMyOrders('all', $('.filter-tab.active')[0]);
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: r.message });
        }
      });
    }
  });
}

// Init
loadAvailable();
loadMyOrders('all', $('.filter-tab.active')[0]);
</script>
</body>
</html>
