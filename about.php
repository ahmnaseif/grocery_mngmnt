<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us — AMI Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f4f6f9; padding-top: 56px; }
    .hero-about {
      background: linear-gradient(135deg, #1a2a3a 0%, #2d7a45 100%);
      color: white; padding: 60px 0 50px; text-align: center;
    }
    .hero-about h1 { font-weight: 800; font-size: 2.2rem; }
    .hero-about p { opacity: 0.88; max-width: 520px; margin: 12px auto 0; }
    .section-card {
      background: white; border-radius: 16px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.07);
      padding: 36px 32px; margin-bottom: 24px;
    }
    .value-icon { font-size: 2rem; color: #28a745; margin-bottom: 10px; }
    .stat-num { font-size: 2rem; font-weight: 800; color: #28a745; }
    .stat-label { color: #6c757d; font-size: 0.9rem; }
    .team-card { background: white; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 24px 20px; text-align: center; }
    .team-avatar {
      width: 72px; height: 72px; border-radius: 50%;
      background: linear-gradient(135deg, #28a745, #1a6b30);
      color: white; display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; font-weight: 700; margin: 0 auto 12px;
    }
  </style>
</head>
<body>
<?php include_once('navbar.php'); ?>

<!-- Hero -->
<div class="hero-about">
  <div class="container">
    <h1><i class="fas fa-store me-2"></i>About AMI Grocery</h1>
    <p>Your trusted grocery partner — delivering quality products and exceptional service since day one.</p>
  </div>
</div>

<div class="container py-5">

  <!-- Our Story -->
  <div class="section-card">
    <h4 class="fw-bold mb-3" style="color:#1a2a3a;"><i class="fas fa-leaf text-success me-2"></i>Our Story</h4>
    <p class="text-muted mb-0">
      AMI Grocery was founded with a simple mission — to make fresh, high-quality groceries accessible to every household.
      We partner directly with trusted local suppliers and farmers to bring you the freshest produce, dairy, pantry staples,
      and more. Whether you shop in-store or call for doorstep delivery, we're committed to making your experience seamless
      and satisfying.
    </p>
  </div>

  <!-- Stats -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="section-card text-center py-4">
        <div class="stat-num">500+</div>
        <div class="stat-label">Products</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="section-card text-center py-4">
        <div class="stat-num">10K+</div>
        <div class="stat-label">Happy Customers</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="section-card text-center py-4">
        <div class="stat-num">50+</div>
        <div class="stat-label">Local Suppliers</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="section-card text-center py-4">
        <div class="stat-num">5★</div>
        <div class="stat-label">Customer Rating</div>
      </div>
    </div>
  </div>

  <!-- Values -->
  <div class="section-card">
    <h4 class="fw-bold mb-4" style="color:#1a2a3a;"><i class="fas fa-heart text-success me-2"></i>Our Values</h4>
    <div class="row g-4">
      <div class="col-md-4 text-center">
        <div class="value-icon"><i class="fas fa-check-circle"></i></div>
        <h6 class="fw-bold">Quality First</h6>
        <p class="text-muted small mb-0">We inspect every product for freshness and quality before it reaches your hands.</p>
      </div>
      <div class="col-md-4 text-center">
        <div class="value-icon"><i class="fas fa-handshake"></i></div>
        <h6 class="fw-bold">Community</h6>
        <p class="text-muted small mb-0">We support local farmers and suppliers, keeping our community strong.</p>
      </div>
      <div class="col-md-4 text-center">
        <div class="value-icon"><i class="fas fa-truck"></i></div>
        <h6 class="fw-bold">Convenience</h6>
        <p class="text-muted small mb-0">Fast doorstep delivery so you get what you need without leaving home.</p>
      </div>
    </div>
  </div>

</div>

<footer class="bg-dark text-white py-4 mt-2">
  <div class="container text-center">
    <p class="mb-1"><i class="fas fa-shopping-basket me-2"></i><strong>AMI Grocery</strong></p>
    <small class="text-muted">Your Trusted Grocery Partner</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include_once __DIR__ . '/chatbot.php'; ?>
</body>
</html>
