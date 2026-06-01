<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMI Grocery — Fresh Delivered Daily</title>
    <link rel="stylesheet" href="css/fontawesome-free-7.1.0-web/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .hero-section {
            background: linear-gradient(135deg, #1a2a3a 0%, #2d7a45 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
        }
        .hero-section h1 { font-size: 3rem; font-weight: 700; }
        .hero-section p { font-size: 1.2rem; opacity: 0.85; }
        .search-bar { max-width: 500px; margin: 24px auto 0; }
        .category-card {
            border-radius: 14px;
            transition: 0.3s;
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        }
        .category-card:hover { transform: translateY(-6px); box-shadow: 0 8px 24px rgba(0,0,0,0.13); }
        .category-card img { height: 120px; object-fit: contain; padding: 16px; background: #f8f9fa; }
        .category-card .card-body { text-align: center; padding: 10px; }
        .section-title { font-size: 1.6rem; font-weight: 700; color: #1a2a3a; margin-bottom: 24px; }
        .feature-icon { font-size: 2rem; color: #28a745; }
        .feature-box { text-align: center; padding: 24px 16px; }
        .product-card { border-radius: 14px; overflow: hidden; transition: 0.3s; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .product-card img { height: 160px; object-fit: cover; width: 100%; }
        .badge-price { background: #28a745; color: white; font-size: 1rem; padding: 6px 12px; border-radius: 20px; }
    </style>
</head>
<body>

<?php include_once('navbar.php'); ?>

<!-- Hero -->
<div class="hero-section">
    <div class="container">
        <h1><i class="fas fa-shopping-basket me-3"></i>Fresh Groceries Delivered</h1>
        <p>Quality products from trusted suppliers — right to your door</p>
        <div class="search-bar input-group">
            <input type="text" class="form-control form-control-lg" id="heroSearch" placeholder="Search for products...">
            <button class="btn btn-success btn-lg" id="heroSearchBtn"><i class="fas fa-search"></i></button>
        </div>
        <!-- <?php if (!isset($_SESSION['user'])): ?>
        <div class="mt-4">
            <a href="/grocery_mngmnt/register.php" class="btn btn-success btn-lg me-2">
                <i class="fas fa-user-plus me-1"></i> Create Account
            </a>
            <a href="/grocery_mngmnt/login.php" class="btn btn-outline-light btn-lg">
                <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
        </div>
        <?php endif; ?> -->
    </div>
</div>

<!-- Why shop with us -->
<div class="bg-light py-4">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-truck"></i></div>
                    <h6 class="mt-2 fw-bold">Fast Delivery</h6>
                    <small class="text-muted">Same day delivery available</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                    <h6 class="mt-2 fw-bold">Fresh Products</h6>
                    <small class="text-muted">Sourced from trusted suppliers</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h6 class="mt-2 fw-bold">Secure Payments</h6>
                    <small class="text-muted">Card & cash on delivery</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <h6 class="mt-2 fw-bold">24/7 Support</h6>
                    <small class="text-muted">Always here to help</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories -->
<div class="container py-5">
    <h2 class="section-title">Shop by Category</h2>
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3">
        <?php
        $categories = [
            ['name' => 'Vegetables',  'img' => 'veg.png',          'url' => 'Vegetables.php'],
            ['name' => 'Fruits',      'img' => 'fruit.png',         'url' => 'Fruits.php'],
            ['name' => 'Dairy',       'img' => 'milk-products.png', 'url' => 'Dairy_Products.php'],
            ['name' => 'Baking',      'img' => 'pantry.png',        'url' => 'Baking_Pantry.php'],
            ['name' => 'Pasta & Rice','img' => 'pasta.png',         'url' => 'Pasta_Rice.php'],
            ['name' => 'Spices',      'img' => 'spices.png',        'url' => 'Spices.php'],
            ['name' => 'Snacks',      'img' => 'snackks.png',       'url' => 'Snacks.php'],
            ['name' => 'Desserts',    'img' => 'vegetable.png',     'url' => 'Desserts_Mix.php'],
            ['name' => 'Coffee & Tea','img' => 'sack.png',          'url' => 'Coffee_Tea_Malts.php'],
            ['name' => 'Beverages',   'img' => 'beverages.png',     'url' => 'Beverages.php'],
            ['name' => 'Baby',        'img' => 'baby-bottle.png',   'url' => 'Baby_Products.php'],
            ['name' => 'Health',      'img' => 'health.png',        'url' => 'Health_Beauty.php'],
            ['name' => 'Household',   'img' => 'household.png',     'url' => 'Household.php'],
            ['name' => 'Stationery',  'img' => 'stationery.png',    'url' => 'Stationery.php'],
        ];
        foreach ($categories as $cat): ?>
        <div class="col">
            <a href="/grocery_mngmnt/<?php echo $cat['url']; ?>" class="text-decoration-none">
                <div class="category-card card h-100">
                    <img src="/grocery_mngmnt/assets/image/<?php echo $cat['img']; ?>" class="card-img-top" alt="<?php echo $cat['name']; ?>">
                    <div class="card-body">
                        <span class="fw-semibold text-dark small"><?php echo $cat['name']; ?></span>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Featured Products -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div id="featuredProducts" class="row g-3">
            <div class="col-12 text-center text-muted py-4">
                <div class="spinner-border text-success" role="status"></div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <p class="mb-1"><i class="fas fa-shopping-basket me-2"></i><strong>AMI Grocery</strong></p>
        <small class="text-muted">Your Trusted Grocery Partner</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Load a sample of products from the first few categories
    const catIds = ['CAT001', 'CAT002', 'CAT003', 'CAT005'];
    let loaded = 0;
    let allHtml = '';

    catIds.forEach(function(catId) {
        $.get('/grocery_mngmnt/lib/routes/product/loadProductbyCategory.php', { catId: catId }, function(res) {
            // Extract just product cards from res and limit to 2 per category
            const cards = $(res).filter('.product-card').slice(0, 2);
            cards.each(function() {
                allHtml += '<div class="col-6 col-md-3">' + $(this).prop('outerHTML') + '</div>';
            });
            loaded++;
            if (loaded === catIds.length) {
                if (allHtml) {
                    $('#featuredProducts').html(allHtml);
                } else {
                    $('#featuredProducts').html('<div class="col-12 text-center text-muted">No products available yet.</div>');
                }
            }
        });
    });

    // Hero search — look up product in DB and redirect to its category page
    function doHeroSearch() {
        const q = $('#heroSearch').val().trim();
        if (!q) return;
        $.get('/grocery_mngmnt/lib/routes/category/searchredirect.php', { q: q }, function(res) {
            if (res && res.url) {
                window.location.href = res.url;
            } else {
                // Fallback: show "not found" message briefly
                $('#heroSearch').addClass('is-invalid');
                setTimeout(function() { $('#heroSearch').removeClass('is-invalid'); }, 2000);
            }
        }, 'json').fail(function() {
            window.location.href = '/grocery_mngmnt/Vegetables.php';
        });
    }

    $('#heroSearchBtn').click(doHeroSearch);
    $('#heroSearch').keypress(function(e) {
        if (e.which === 13) doHeroSearch();
    });
});
</script>
<?php include_once __DIR__ . '/chatbot.php'; ?>
<script>
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
        success: function(response) {
            if (response && response.success) {
                alert(name + ' added to cart!');
            } else if (response && response.require_login) {
                window.location.href = '/grocery_mngmnt/login.php';
            }
        }
    });
}
</script>
</body>
</html>
