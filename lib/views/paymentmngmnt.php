<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['usertype'] !== 'customer') {
    header('Location: ../../login.php');
    exit;
}

$page_title = 'Payment Methods';
ob_start();
?>

<div id="savedCards" class="row mb-4"></div>

<div class="card shadow-sm p-4" style="max-width:520px;">
  <h5 class="mb-3 fw-bold"><i class="fas fa-plus-circle me-2 text-success"></i>Add New Card</h5>
  <div class="mb-3">
    <label class="form-label fw-semibold">Cardholder Name</label>
    <input type="text" class="form-control" id="newCardHolder" placeholder="Name on card">
  </div>
  <div class="mb-3">
    <label class="form-label fw-semibold">Card Number</label>
    <input type="text" class="form-control" id="newCardNumber" maxlength="19" placeholder="1234 5678 9012 3456">
  </div>
  <div class="row mb-3">
    <div class="col">
      <label class="form-label fw-semibold">Expiry (MM/YY)</label>
      <input type="text" class="form-control" id="newCardExpiry" maxlength="5" placeholder="MM/YY">
    </div>
    <div class="col">
      <label class="form-label fw-semibold">CVV</label>
      <input type="text" class="form-control" id="newCardCvv" maxlength="4" placeholder="123">
    </div>
  </div>
  <div class="mb-3">
    <label class="form-label fw-semibold">Card Type</label>
    <select class="form-select" id="newCardType">
      <option value="Visa">Visa</option>
      <option value="Mastercard">Mastercard</option>
      <option value="Amex">American Express</option>
    </select>
  </div>
  <button class="btn btn-success w-100" id="addCardBtn">
    <i class="fas fa-save me-1"></i> Save Card
  </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function loadCards() {
  $.get('/grocery_mngmnt/lib/routes/payment/loadPaymentMethods.php', function(cards) {
    const container = $('#savedCards');
    if (!cards || cards.length === 0) {
      container.html('<div class="col-12"><p class="text-muted">No saved cards yet.</p></div>');
      return;
    }
    const typeIcons = { Visa: 'fa-cc-visa', Mastercard: 'fa-cc-mastercard', Amex: 'fa-cc-amex' };
    let html = '';
    cards.forEach(function(c) {
      const icon = typeIcons[c.cardType] || 'fa-credit-card';
      html += `
        <div class="col-md-4 mb-3">
          <div class="card border-0 shadow-sm h-100" style="background:linear-gradient(135deg,#1a2a3a,#0f1a24);color:white;border-radius:16px;">
            <div class="card-body p-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <i class="fab ${icon} fa-2x"></i>
                <button class="btn btn-sm btn-outline-light delete-card" data-id="${c.methodId}">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
              <div class="fs-5 letter-spacing mb-2">•••• •••• •••• ${c.cardLastFour}</div>
              <div class="d-flex justify-content-between small opacity-75">
                <span>${c.cardHolderName}</span>
                <span>Exp: ${c.cardExpiry}</span>
              </div>
            </div>
          </div>
        </div>`;
    });
    container.html(html);
  });
}

$(document).ready(function() {
  loadCards();

  $('#newCardNumber').on('input', function() {
    let val = $(this).val().replace(/\D/g, '').substring(0, 16);
    $(this).val(val.replace(/(.{4})/g, '$1 ').trim());
  });
  $('#newCardExpiry').on('input', function() {
    let val = $(this).val().replace(/\D/g, '').substring(0, 4);
    if (val.length > 2) val = val.substring(0, 2) + '/' + val.substring(2);
    $(this).val(val);
  });

  $('#addCardBtn').click(function() {
    const cardHolder = $('#newCardHolder').val().trim();
    const cardNumber = $('#newCardNumber').val().trim();
    const cardExpiry = $('#newCardExpiry').val().trim();
    const cardType   = $('#newCardType').val();
    if (!cardHolder || !cardNumber || !cardExpiry) {
      Swal.fire({ icon: 'error', title: 'Please fill all card fields' });
      return;
    }
    $.ajax({
      url: '/grocery_mngmnt/lib/routes/payment/addPaymentMethod.php',
      method: 'POST', dataType: 'json',
      data: { cardHolder, cardNumber, cardExpiry, cardType },
      success: function(res) {
        if (res.status) {
          Swal.fire({ icon: 'success', title: 'Card saved!' });
          $('#newCardHolder, #newCardNumber, #newCardExpiry, #newCardCvv').val('');
          loadCards();
        } else {
          Swal.fire({ icon: 'error', title: res.message || 'Failed to save card' });
        }
      }
    });
  });

  $(document).on('click', '.delete-card', function() {
    const methodId = $(this).data('id');
    Swal.fire({
      title: 'Remove this card?', icon: 'warning',
      showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Remove'
    }).then(function(result) {
      if (result.isConfirmed) {
        $.ajax({
          url: '/grocery_mngmnt/lib/routes/payment/deletePaymentMethod.php',
          method: 'POST', dataType: 'json', data: { methodId },
          success: function(res) { if (res.status) loadCards(); }
        });
      }
    });
  });
});
</script>

<?php
$page_content = ob_get_clean();
include_once('commoncstmr.php');
?>
