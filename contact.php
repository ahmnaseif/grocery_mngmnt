<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us — AMI Grocery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f4f6f9; padding-top: 56px; }
    .hero-contact {
      background: linear-gradient(135deg, #1a2a3a 0%, #2d7a45 100%);
      color: white; padding: 60px 0 50px; text-align: center;
    }
    .hero-contact h1 { font-weight: 800; font-size: 2.2rem; }
    .hero-contact p { opacity: 0.88; max-width: 500px; margin: 12px auto 0; }
    .contact-card {
      background: white; border-radius: 16px;
      box-shadow: 0 4px 18px rgba(0,0,0,0.07);
      padding: 32px 28px;
    }
    .info-item { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 20px; }
    .info-icon {
      width: 44px; height: 44px; border-radius: 12px;
      background: #e8f5e9; color: #28a745; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .info-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; margin-bottom: 2px; }
    .info-value { font-weight: 500; color: #1a2a3a; }
    .form-control, .form-select { border-radius: 10px; border: 1.5px solid #e0e0e0; padding: 10px 14px; }
    .form-control:focus, .form-select:focus { border-color: #28a745; box-shadow: 0 0 0 3px rgba(40,167,69,0.1); }
    .btn-send { background: #28a745; color: white; border: none; border-radius: 10px; padding: 12px 28px; font-weight: 600; }
    .btn-send:hover { background: #1e7e34; color: white; }
  </style>
</head>
<body>
<?php include_once('navbar.php'); ?>

<!-- Hero -->
<div class="hero-contact">
  <div class="container">
    <h1><i class="fas fa-headset me-2"></i>Contact Us</h1>
    <p>We're here to help! Reach out by phone, email, or use the form below.</p>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">

    <!-- Contact Info -->
    <div class="col-lg-5">
      <div class="contact-card h-100">
        <h5 class="fw-bold mb-4" style="color:#1a2a3a;">Get in Touch</h5>

        <div class="info-item">
          <div class="info-icon"><i class="fas fa-phone"></i></div>
          <div>
            <div class="info-label">Phone / Delivery Orders</div>
            <div class="info-value">+94 77 123 4567</div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <div class="info-label">Email</div>
            <div class="info-value">support@amigrocery.lk</div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div class="info-label">Location</div>
            <div class="info-value">123 Main Street, Colombo 03, Sri Lanka</div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="fas fa-clock"></i></div>
          <div>
            <div class="info-label">Opening Hours</div>
            <div class="info-value">Mon–Sat: 7 AM – 9 PM<br>Sunday: 8 AM – 7 PM</div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="fas fa-truck"></i></div>
          <div>
            <div class="info-label">Doorstep Delivery</div>
            <div class="info-value">Call <strong>+94 77 123 4567</strong> to place a delivery order. We deliver within the city.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Form -->
    <div class="col-lg-7">
      <div class="contact-card">
        <h5 class="fw-bold mb-4" style="color:#1a2a3a;">Send Us a Message</h5>
        <div id="formSuccess" class="alert alert-success d-none">
          <i class="fas fa-check-circle me-2"></i>Thank you! We'll get back to you shortly.
        </div>
        <form id="contactForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Full Name</label>
              <input type="text" class="form-control" id="cName" placeholder="Your name">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold small">Email</label>
              <input type="email" class="form-control" id="cEmail" placeholder="you@example.com">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Subject</label>
              <select class="form-select" id="cSubject">
                <option value="">Select a topic</option>
                <option>Delivery Enquiry</option>
                <option>Return / Refund</option>
                <option>Product Enquiry</option>
                <option>Payment Issue</option>
                <option>Other</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold small">Message</label>
              <textarea class="form-control" id="cMessage" rows="4" placeholder="How can we help you?"></textarea>
            </div>
            <div class="col-12">
              <button type="button" class="btn btn-send" id="sendMsgBtn">
                <i class="fas fa-paper-plane me-2"></i>Send Message
              </button>
            </div>
          </div>
        </form>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#sendMsgBtn').click(function() {
    const name    = $('#cName').val().trim();
    const email   = $('#cEmail').val().trim();
    const subject = $('#cSubject').val();
    const message = $('#cMessage').val().trim();
    let ok = true;
    if (!name)    { $('#cName').addClass('is-invalid');    ok = false; } else { $('#cName').removeClass('is-invalid'); }
    if (!email)   { $('#cEmail').addClass('is-invalid');   ok = false; } else { $('#cEmail').removeClass('is-invalid'); }
    if (!subject) { $('#cSubject').addClass('is-invalid'); ok = false; } else { $('#cSubject').removeClass('is-invalid'); }
    if (!message) { $('#cMessage').addClass('is-invalid'); ok = false; } else { $('#cMessage').removeClass('is-invalid'); }
    if (ok) {
        $('#contactForm').hide();
        $('#formSuccess').removeClass('d-none');
    }
});
</script>
<?php include_once __DIR__ . '/chatbot.php'; ?>
</body>
</html>
