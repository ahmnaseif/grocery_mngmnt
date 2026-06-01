<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    header('Location: /grocery_mngmnt/login.php');
    exit;
}
if (empty($_SESSION['cart'])) {
    header('Location: /grocery_mngmnt/index.php');
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
$userName = htmlspecialchars($_SESSION['user_name'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout — AMI Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
    .checkout-header { background: linear-gradient(135deg, #1a2a3a, #0f1a24); color: white; padding: 16px 30px; display: flex; align-items: center; justify-content: space-between; }
    .checkout-header a { color: #28a745; text-decoration: none; font-size: 14px; }
    .card-option { border: 2px solid #dee2e6; border-radius: 12px; padding: 16px; cursor: pointer; transition: 0.2s; }
    .card-option.selected { border-color: #28a745; background: #f0fff4; }
    .method-radio { display: none; }
    .saved-card-option { border: 1px solid #dee2e6; border-radius: 10px; padding: 12px 16px; cursor: pointer; transition: 0.2s; }
    .saved-card-option.selected, .saved-card-option:hover { border-color: #28a745; background: #f0fff4; }
    .order-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee; }
    .order-item:last-child { border-bottom: none; }
    #cardFields { display: none; }
    .place-order-btn { background: #28a745; border: none; color: white; padding: 14px; font-size: 16px; font-weight: 600; border-radius: 12px; width: 100%; cursor: pointer; transition: 0.2s; }
    .place-order-btn:hover { background: #1e7e34; }
    .place-order-btn:disabled { background: #6c757d; cursor: not-allowed; }
    .spinner-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; flex-direction: column; color: white; font-size: 18px; }
    .spinner-overlay.show { display: flex; }
  </style>
</head>
<body>

<div class="checkout-header">
  <div>
    <i class="fas fa-shopping-basket me-2"></i>
    <strong>AMI Grocery — Checkout</strong>
  </div>
  <a href="/grocery_mngmnt/index.php"><i class="fas fa-arrow-left me-1"></i> Continue Shopping</a>
</div>

<div class="container py-4">
  <div class="row g-4">

    <!-- Left: Payment -->
    <div class="col-lg-7">
      <div class="card shadow-sm p-4 mb-4">
        <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2 text-success"></i>Delivery Address</h5>
        <input type="text" class="form-control" id="deliveryAddress" placeholder="Enter your delivery address">
      </div>

      <div class="card shadow-sm p-4">
        <h5 class="mb-3"><i class="fas fa-credit-card me-2 text-success"></i>Payment Method</h5>

        <!-- Payment selector -->
        <div class="row g-3 mb-4">
          <div class="col-6">
            <div class="card-option selected" id="optCod" onclick="selectPayment('cod')">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payMethod" id="radioCod" checked>
                <label class="form-check-label fw-bold" for="radioCod">
                  <i class="fas fa-money-bill-wave me-1 text-success"></i> Cash on Delivery
                </label>
              </div>
              <small class="text-muted">Pay when your order arrives</small>
            </div>
          </div>
          <div class="col-6">
            <div class="card-option" id="optCard" onclick="selectPayment('card')">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payMethod" id="radioCard">
                <label class="form-check-label fw-bold" for="radioCard">
                  <i class="fas fa-credit-card me-1 text-primary"></i> Pay by Card
                </label>
              </div>
              <small class="text-muted">Visa, Mastercard, Amex</small>
            </div>
          </div>
        </div>

        <!-- Card section -->
        <div id="cardFields">
          <div id="savedCardsSection" class="mb-3"></div>

          <div id="newCardForm">
            <h6 class="text-muted mb-3">Enter Card Details</h6>
            <div class="mb-3">
              <label class="form-label">Cardholder Name</label>
              <input type="text" class="form-control" id="cardHolder" placeholder="Name on card">
            </div>
            <div class="mb-3">
              <label class="form-label">Card Number</label>
              <input type="text" class="form-control" id="cardNumber" maxlength="19" placeholder="1234 5678 9012 3456">
            </div>
            <div class="row mb-3">
              <div class="col">
                <label class="form-label">Expiry (MM/YY)</label>
                <input type="text" class="form-control" id="cardExpiry" maxlength="5" placeholder="MM/YY">
              </div>
              <div class="col">
                <label class="form-label">CVV</label>
                <input type="password" class="form-control" id="cardCvv" maxlength="4" placeholder="•••">
              </div>
            </div>
            <div class="mb-3">
              <select class="form-select" id="cardType">
                <option value="Visa">Visa</option>
                <option value="Mastercard">Mastercard</option>
                <option value="Amex">American Express</option>
              </select>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" id="saveCard">
              <label class="form-check-label" for="saveCard">Save this card for future use</label>
            </div>
          </div>
        </div>

        <button class="place-order-btn mt-3" id="placeOrderBtn" onclick="placeOrder()">
          <i class="fas fa-lock me-2"></i> Place Order — Rs. <?php echo number_format($total, 2); ?>
        </button>
      </div>
    </div>

    <!-- Right: Order Summary -->
    <div class="col-lg-5">
      <div class="card shadow-sm p-4">
        <h5 class="mb-3"><i class="fas fa-receipt me-2 text-success"></i>Order Summary</h5>
        <?php foreach ($cart as $item): ?>
          <div class="order-item">
            <div>
              <div class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></div>
              <small class="text-muted">Rs. <?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></small>
            </div>
            <div class="fw-bold">Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
          </div>
        <?php endforeach; ?>
        <div class="d-flex justify-content-between mt-3 pt-2 border-top">
          <span class="fw-bold fs-5">Total</span>
          <span class="fw-bold fs-5 text-success">Rs. <?php echo number_format($total, 2); ?></span>
        </div>
        <div class="mt-3 text-muted small">
          <i class="fas fa-shield-alt me-1 text-success"></i> Secure checkout
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Spinner overlay -->
<div class="spinner-overlay" id="spinnerOverlay">
  <div class="spinner-border text-light mb-3" style="width:3rem;height:3rem;"></div>
  <div id="spinnerMsg">Processing your order...</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  let paymentMode = 'cod';
  let selectedSavedCard = null;

  function selectPayment(mode) {
    paymentMode = mode;
    if (mode === 'cod') {
      $('#optCod').addClass('selected');
      $('#optCard').removeClass('selected');
      $('#radioCod').prop('checked', true);
      $('#cardFields').hide();
    } else {
      $('#optCard').addClass('selected');
      $('#optCod').removeClass('selected');
      $('#radioCard').prop('checked', true);
      $('#cardFields').show();
      loadSavedCards();
    }
  }

  function loadSavedCards() {
    $.getJSON('/grocery_mngmnt/lib/routes/payment/loadPaymentMethods.php', function(cards) {
      if (cards.length === 0) {
        $('#savedCardsSection').html('');
        return;
      }
      let html = '<h6 class="text-muted mb-2">Saved Cards</h6>';
      cards.forEach(function(c) {
        html += `
          <div class="saved-card-option mb-2" data-id="${c.methodId}" data-holder="${c.cardHolderName}" data-expiry="${c.cardExpiry}" data-type="${c.cardType}" onclick="selectSavedCard(this)">
            <div class="d-flex align-items-center gap-3">
              <i class="fas fa-credit-card text-primary"></i>
              <div>
                <div class="fw-bold">${c.cardType} •••• ${c.cardLastFour}</div>
                <small class="text-muted">${c.cardHolderName} &bull; Exp: ${c.cardExpiry}</small>
              </div>
            </div>
          </div>`;
      });
      html += '<div class="mb-2"><a href="#" class="text-success" id="useNewCard" onclick="useNewCard(event)">+ Use a different card</a></div><hr>';
      $('#savedCardsSection').html(html);
      selectSavedCard($('.saved-card-option').first()[0]);
    });
  }

  function selectSavedCard(el) {
    $('.saved-card-option').removeClass('selected');
    $(el).addClass('selected');
    selectedSavedCard = {
      id: $(el).data('id'),
      holder: $(el).data('holder'),
      expiry: $(el).data('expiry'),
      type: $(el).data('type')
    };
    $('#newCardForm').hide();
  }

  function useNewCard(e) {
    e.preventDefault();
    $('.saved-card-option').removeClass('selected');
    selectedSavedCard = null;
    $('#newCardForm').show();
  }

  $('#cardNumber').on('input', function() {
    let val = $(this).val().replace(/\D/g, '').substring(0, 16);
    $(this).val(val.replace(/(.{4})/g, '$1 ').trim());
  });
  $('#cardExpiry').on('input', function() {
    let val = $(this).val().replace(/\D/g, '').substring(0, 4);
    if (val.length > 2) val = val.substring(0,2) + '/' + val.substring(2);
    $(this).val(val);
  });

  function placeOrder() {
    const address = $('#deliveryAddress').val().trim();
    if (!address) {
      Swal.fire({ icon: 'error', title: 'Please enter your delivery address' });
      return;
    }

    if (paymentMode === 'card' && !selectedSavedCard) {
      const holder  = $('#cardHolder').val().trim();
      const number  = $('#cardNumber').val().trim();
      const expiry  = $('#cardExpiry').val().trim();
      const cvv     = $('#cardCvv').val().trim();
      if (!holder || !number || !expiry || !cvv) {
        Swal.fire({ icon: 'error', title: 'Please fill in all card fields' });
        return;
      }
      if (number.replace(/\s/g, '').length !== 16) {
        Swal.fire({ icon: 'error', title: 'Card number must be 16 digits' });
        return;
      }
    }

    $('#placeOrderBtn').prop('disabled', true);
    $('#spinnerOverlay').addClass('show');

    if (paymentMode === 'card' && !selectedSavedCard) {
      setTimeout(function() {
        $('#spinnerMsg').text('Verifying card...');
        setTimeout(submitOrder, 1500);
      }, 800);
    } else {
      setTimeout(submitOrder, 1000);
    }
  }

  function submitOrder() {
    const address = $('#deliveryAddress').val().trim();
    let payMethod = paymentMode === 'cod' ? 'cash_on_delivery' : (selectedSavedCard ? 'saved_card' : 'card');

    const postData = {
      paymentMethod: payMethod,
      deliveryAddress: address,
      saveCard: $('#saveCard').is(':checked') ? '1' : '0',
      cardHolder: selectedSavedCard ? selectedSavedCard.holder : $('#cardHolder').val().trim(),
      cardNumber: $('#cardNumber').val().trim(),
      cardExpiry: selectedSavedCard ? selectedSavedCard.expiry : $('#cardExpiry').val().trim(),
      cardType: selectedSavedCard ? selectedSavedCard.type : $('#cardType').val()
    };

    $.ajax({
      url: '/grocery_mngmnt/lib/routes/order/placeOrder.php',
      method: 'POST',
      dataType: 'json',
      data: postData,
      success: function(res) {
        $('#spinnerOverlay').removeClass('show');
        if (res.status) {
          Swal.fire({
            icon: 'success',
            title: 'Order Placed!',
            html: `Your order <strong>${res.orderId}</strong> has been placed successfully.<br>Total: <strong>Rs. ${parseFloat(res.total).toFixed(2)}</strong>`,
            confirmButtonText: 'View My Orders',
            confirmButtonColor: '#28a745'
          }).then(function() {
            window.location.href = '/grocery_mngmnt/lib/views/ordermngmnt.php';
          });
        } else {
          $('#placeOrderBtn').prop('disabled', false);
          Swal.fire({ icon: 'error', title: 'Order failed', text: res.message });
        }
      },
      error: function() {
        $('#spinnerOverlay').removeClass('show');
        $('#placeOrderBtn').prop('disabled', false);
        Swal.fire({ icon: 'error', title: 'Something went wrong. Please try again.' });
      }
    });
  }
</script>
</body>
</html>
