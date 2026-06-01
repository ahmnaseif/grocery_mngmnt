<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search — AMI Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f4f6f9; padding-top: 56px; }
    .search-hero { background: linear-gradient(135deg, #1a2a3a 0%, #2d7a45 100%); padding: 36px 0 28px; }
    .search-bar { max-width: 560px; margin: 0 auto; }
    .search-bar input { border-radius: 10px 0 0 10px; border: none; padding: 12px 18px; font-size: 1rem; }
    .search-bar button { border-radius: 0 10px 10px 0; background: #28a745; border: none; padding: 12px 20px; color: white; }
    .search-bar button:hover { background: #1e7e34; }
  </style>
</head>
<body>
<?php include_once('navbar.php'); ?>

<div class="search-hero text-white text-center">
  <div class="container">
    <h4 class="fw-bold mb-3"><i class="fas fa-search me-2"></i>Search Products</h4>
    <div class="input-group search-bar">
      <input type="text" id="searchInput" class="form-control" placeholder="Search for groceries…"
             value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
      <button id="searchBtn"><i class="fas fa-search"></i></button>
    </div>
  </div>
</div>

<div class="container py-4">
  <div id="searchResults" class="row">
    <div class="col-12 text-center text-muted py-5">
      <i class="fas fa-search fa-2x mb-3 d-block" style="opacity:0.3;"></i>
      Type something to search for products.
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function doSearch() {
    const q = $('#searchInput').val().trim();
    if (!q) return;
    $('#searchResults').html('<div class="col-12 text-center py-4"><div class="spinner-border text-success"></div></div>');
    $.get('/grocery_mngmnt/lib/routes/category/searchproduct.php', { q: q }, function(res) {
        $('#searchResults').html(res);
    });
}

$('#searchBtn').click(doSearch);
$('#searchInput').keypress(function(e) { if (e.which === 13) doSearch(); });

function addToCart(id, name, price, image) {
    <?php if (!isset($_SESSION['user'])): ?>
    window.location.href = '/grocery_mngmnt/login.php';
    return;
    <?php endif; ?>
    $.ajax({
        url: '/grocery_mngmnt/commonpr.php',
        method: 'POST',
        data: { action: 'add', product_id: id, product_name: name, product_price: price, product_image: image },
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(r) {
            if (r && r.success) alert(name + ' added to cart!');
            else if (r && r.require_login) window.location.href = '/grocery_mngmnt/login.php';
        }
    });
}

// Auto-search if q param in URL
$(document).ready(function() {
    const q = $('#searchInput').val();
    if (q.length >= 2) doSearch();
});
</script>
<?php include_once __DIR__ . '/chatbot.php'; ?>
</body>
</html>
