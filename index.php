<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cake Heaven - Premium Online Cake Ordering</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --light-cream: #fff9b4;
      --dusty-pink: #d79f90;
      --deep-red: #8e1913;
      --dark-brown: #3d351b;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body { 
      font-family: 'Poppins', sans-serif;
      background: var(--light-cream);
      overflow-x: hidden;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: var(--light-cream);
    }
    ::-webkit-scrollbar-thumb {
      background: var(--dusty-pink);
      border-radius: 10px;
    }

    /* Navigation */
    .navbar {
      background: linear-gradient(135deg, var(--dark-brown) 0%, var(--deep-red) 100%) !important;
      padding: 15px 0;
      box-shadow: 0 4px 20px rgba(61, 53, 27, 0.3);
      transition: all 0.3s ease;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 28px;
      color: var(--light-cream) !important;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .nav-link {
      color: #fff !important;
      font-weight: 500;
      margin: 0 8px;
      padding: 10px 20px !important;
      border-radius: 25px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover {
      background: var(--dusty-pink);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(215, 159, 144, 0.4);
    }

    /* Buttons */
    .btn-primary { 
      background: linear-gradient(45deg, var(--dusty-pink), var(--deep-red));
      border: none;
      padding: 12px 35px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(142, 25, 19, 0.3);
      position: relative;
      overflow: hidden;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-primary:hover { 
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(142, 25, 19, 0.5);
    }

    .btn-outline-primary { 
      color: var(--deep-red); 
      border: 2px solid var(--deep-red);
      padding: 12px 35px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      background: transparent;
    }

    .btn-outline-primary:hover { 
      background: var(--deep-red); 
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(142, 25, 19, 0.3);
    }

    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, var(--light-cream) 0%, #f5e98c 50%, var(--light-cream) 100%);
      padding: 150px 0 100px;
      position: relative;
      overflow: hidden;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
      background-size: cover;
    }

    .hero-title {
      font-size: 4rem;
      font-weight: 800;
      background: linear-gradient(45deg, var(--dark-brown), var(--deep-red));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 25px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .hero-subtitle {
      font-size: 1.3rem;
      color: var(--dark-brown);
      margin-bottom: 35px;
      opacity: 0.9;
      line-height: 1.6;
    }

    .cake-animation { 
      animation: float 4s ease-in-out infinite;
      filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
      transition: all 0.3s ease;
    }

    .cake-animation:hover {
      transform: scale(1.05);
    }

    @keyframes float { 
      0%,100% { transform: translateY(0) rotate(0deg); } 
      50% { transform: translateY(-20px) rotate(2deg); } 
    }

    /* Values Section */
    .values-section {
      background: linear-gradient(135deg, var(--light-cream) 0%, #f5e98c 100%);
      padding: 100px 0;
      position: relative;
    }

    .section-title {
      font-size: 3rem;
      font-weight: 700;
      color: var(--dark-brown);
      margin-bottom: 15px;
      text-align: center;
    }

    .section-subtitle {
      font-size: 1.2rem;
      color: var(--deep-red);
      text-align: center;
      margin-bottom: 60px;
      opacity: 0.8;
    }

    .values-card {
      border: none;
      border-radius: 20px;
      transition: all 0.4s ease;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      padding: 40px 25px;
      text-align: center;
      height: 100%;
      position: relative;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .values-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      transition: left 0.5s;
    }

    .values-card:hover::before {
      left: 100%;
    }

    .values-card:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 20px 40px rgba(142, 25, 19, 0.2);
    }

    .values-card i {
      font-size: 3rem;
      background: linear-gradient(45deg, var(--dusty-pink), var(--deep-red));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    .values-card:hover i {
      transform: scale(1.2);
    }
.cake-img {
    width: 400px;
    height: 400px;
    object-fit: cover;
}

    .values-card h5 {
      color: var(--dark-brown);
      font-weight: 600;
      margin-bottom: 15px;
    }

    .values-card p {
      color: #666;
      line-height: 1.6;
    }

    /* Contact Section */
    .contact-section {
      background: linear-gradient(135deg, var(--light-cream) 0%, #f5e98c 100%);
      padding: 100px 0;
    }

    .contact-form {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .contact-info {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      height: 100%;
    }

    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 12px 15px;
      transition: all 0.3s ease;
      margin-bottom: 20px;
    }

    .form-control:focus {
      border-color: var(--dusty-pink);
      box-shadow: 0 0 0 0.3rem rgba(215, 159, 144, 0.15);
      transform: translateY(-2px);
    }

    /* Footer */
    .footer {
      background: linear-gradient(135deg, var(--dark-brown) 0%, #2a1f0f 100%);
      color: white;
      padding: 60px 0 20px;
      position: relative;
    }

    .footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--dusty-pink), var(--light-cream), var(--dusty-pink));
    }

    .social-icon {
      color: var(--light-cream);
      font-size: 1.5rem;
      margin-right: 20px;
      transition: all 0.3s ease;
      display: inline-block;
    }

    .social-icon:hover {
      color: white;
      transform: translateY(-5px) scale(1.2);
    }

    .footer-links a {
      color: #ccc;
      text-decoration: none;
      transition: all 0.3s ease;
      display: block;
      margin-bottom: 8px;
    }

    .footer-links a:hover {
      color: var(--light-cream);
      transform: translateX(5px);
    }

    .newsletter-input {
      border-radius: 25px 0 0 25px;
      border: 2px solid var(--dusty-pink);
      border-right: none;
    }

    .newsletter-btn {
      border-radius: 0 25px 25px 0;
      background: var(--dusty-pink);
      border: 2px solid var(--dusty-pink);
      color: white;
      transition: all 0.3s ease;
    }

    .newsletter-btn:hover {
      background: var(--deep-red);
      border-color: var(--deep-red);
    }

    /* Floating Elements */
    .floating-element {
      position: absolute;
      animation: float 6s ease-in-out infinite;
      opacity: 0.7;
    }

    .floating-1 { top: 20%; left: 5%; animation-delay: 0s; }
    .floating-2 { top: 60%; right: 8%; animation-delay: 2s; }
    .floating-3 { bottom: 20%; left: 10%; animation-delay: 4s; }

    /* Responsive */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.5rem;
      }
      
      .section-title {
        font-size: 2.2rem;
      }
      
      .nav-link {
        margin: 5px 0;
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-birthday-cake me-2"></i>Cake Heaven
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#cakes">Our Cakes</a></li>
          <li class="nav-item"><a class="nav-link" href="auth/user-login.php">User Login</a></li>
          <li class="nav-item"><a class="nav-link" href="auth/admin-login.php">Admin Login</a></li>
          <li class="nav-item"><a class="btn btn-outline-light ms-3" href="auth/user-register.php">
            <i class="fas fa-user-plus me-2"></i>Register
          </a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <!-- Floating Elements -->
    <div class="floating-element floating-1">
      <i class="fas fa-cake-candles fa-3x" style="color: var(--dusty-pink);"></i>
    </div>
    <div class="floating-element floating-2">
      <i class="fas fa-cookie-bite fa-3x" style="color: var(--deep-red);"></i>
    </div>
    <div class="floating-element floating-3">
      <i class="fas fa-ice-cream fa-3x" style="color: var(--dark-brown);"></i>
    </div>

    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h1 class="hero-title">Sweet Dreams Delivered Fresh</h1>
          <p class="hero-subtitle">
            Celebrate life's sweetest moments with our artisan cakes. Customized to perfection, 
            baked with love, and delivered right to your doorstep. Your happiness is our recipe!
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="auth/user-register.php" class="btn btn-primary">
              <i class="fas fa-rocket me-2"></i>Get Started
            </a>
            <a href="auth/user-login.php" class="btn btn-outline-primary">
              <i class="fas fa-sign-in-alt me-2"></i>User Login
            </a>
            <a href="cakes.php" class="btn btn-outline-primary">
              <i class="fas fa-eye me-2"></i>View Cakes
            </a>
          </div>
        </div>
        <div class="col-lg-6 text-center">
          <img width="500px" src="img/bg.jpg" 
               alt="Delicious Cake" class="img-fluid cake-animation rounded-3 shadow-lg" style="max-height: 600px;">
        </div>
      </div>
    </div>
  </section>

  <!-- Values Section -->
  <section class="values-section">
    <div class="container">
      <h2 class="section-title">Why Choose Cake Heaven?</h2>
      <p class="section-subtitle">Experience the difference in every bite</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="values-card">
            <i class="fas fa-star"></i>
            <h5>Premium Quality</h5>
            <p>Handcrafted with the finest ingredients, ensuring exceptional taste and freshness in every slice.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="values-card">
            <i class="fas fa-bolt"></i>
            <h5>Fast Delivery</h5>
            <p>Fresh cakes delivered within 2-4 hours. We prioritize speed without compromising quality.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="values-card">
            <i class="fas fa-heart"></i>
            <h5>Made with Love</h5>
            <p>Every cake is crafted with passion and attention to detail by our skilled master bakers.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="values-card">
            <i class="fas fa-palette"></i>
            <h5>Custom Designs</h5>
            <p>Personalize your cake with unique designs, messages, and themes for special occasions.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
<!-- Cakes Section -->
<section id="cakes" class="values-section" style=" padding: 80px 0;">
  <div class="container">
    <h2 class="section-title">Our Cakes</h2>
    <p class="section-subtitle">Delightful cakes for every occasion</p>

    <?php
    include 'dbconnection.php';

    // Fetch categories
    $catResult = mysqli_query($conn, "SELECT * FROM tbl_categories ORDER BY category_name ASC");
    echo '<div class="mb-4">';
    echo '<button class="btn btn-outline-primary me-2 filter-btn" data-cat="all">All</button>';
    while($cat = mysqli_fetch_assoc($catResult)) {
        echo '<button class="btn btn-outline-primary me-2 filter-btn" data-cat="'.$cat['id'].'">'.$cat['category_name'].'</button>';
    }
    echo '</div>';

    // Fetch cakes
    $cakeResult = mysqli_query($conn, "SELECT * FROM tbl_cakes ORDER BY cakeName ASC");
    echo '<div class="row g-4" id="cakesContainer">';
    while($cake = mysqli_fetch_assoc($cakeResult)) {
        echo '<div class="col-md-6 col-lg-4 cake-item" data-cat="'.$cake['category_id'].'">';
        echo '<div class="values-card">';
      echo '<img src="uploads/'.$cake['cakePicture'].'" 
          class="img-fluid rounded mb-3 cake-img" 
          alt="'.$cake['cakeName'].'">';

        echo '<h5>'.$cake['cakeName'].'</h5>';
        echo '<p>Price: Rs. '.$cake['cakePrice'].'</p>';
        echo '<p style="font-size: 0.9rem; color: #555;">'.$cake['description'].'</p>';
        echo '</div></div>';
    }
    echo '</div>';
    ?>

  </div>
</section>
  <!-- Contact Section -->
  <section class="contact-section">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6">
          <div class="contact-form">
            <h3 class="mb-4" style="color: var(--dark-brown);">Get In Touch</h3>
            <form id="contactForm">
              <div class="row">
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Last Name" required>
                </div>
              </div>
              <input type="email" class="form-control" placeholder="Email Address" required>
              <input type="tel" class="form-control" placeholder="Phone Number">
              <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i>Send Message
              </button>
            </form>
            <div id="contactMsg" class="mt-3 text-center fw-bold" style="color: var(--deep-red);"></div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="contact-info">
            <h3 class="mb-4" style="color: var(--dark-brown);">Contact Information</h3>
            <div class="mb-4">
              <p><i class="fas fa-map-marker-alt me-3" style="color: var(--dusty-pink);"></i>123 Sweet Street, Cakeville, PK 54000</p>
              <p><i class="fas fa-phone me-3" style="color: var(--dusty-pink);"></i>+92 300 123-CAKE (2253)</p>
              <p><i class="fas fa-envelope me-3" style="color: var(--dusty-pink);"></i>info@cakeheaven.com</p>
              <p><i class="fas fa-clock me-3" style="color: var(--dusty-pink);"></i>24/7 Online Orders</p>
            </div>
            
            <h5 class="mb-3" style="color: var(--dark-brown);">Store Hours</h5>
            <div class="store-hours">
              <p>Monday - Friday: <span class="float-end">9:00 AM - 9:00 PM</span></p>
              <p>Saturday: <span class="float-end">10:00 AM - 8:00 PM</span></p>
              <p>Sunday: <span class="float-end">11:00 AM - 6:00 PM</span></p>
            </div>
            
            <div class="mt-4">
              <h5 class="mb-3" style="color: var(--dark-brown);">Follow Us</h5>
              <div>
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <h3 class="text-warning mb-3">Cake Heaven</h3>
          <p class="mb-4">Creating unforgettable moments with every slice. Your trusted partner for premium quality cakes and exceptional service.</p>
          <div class="social-icons">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="col-lg-4">
          <h5 class="text-warning mb-3">Quick Links</h5>
          <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="cakes.php">Our Cakes</a>
            <a href="custom.php">Custom Orders</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="auth/user-login.php">User Login</a>
            <a href="auth/admin-login.php">Admin Login</a>
          </div>
        </div>
        <div class="col-lg-4">
          <h5 class="text-warning mb-3">Newsletter</h5>
          <p class="mb-3">Subscribe to get updates on new flavors and special offers!</p>
          <div class="input-group mb-3">
            <input type="email" class="form-control newsletter-input" placeholder="Enter your email">
            <button class="btn newsletter-btn">Subscribe</button>
          </div>
          <div class="payment-methods mt-4">
            <h6 class="text-warning mb-2">We Accept:</h6>
            <i class="fab fa-cc-visa fa-2x me-2 text-muted"></i>
            <i class="fab fa-cc-mastercard fa-2x me-2 text-muted"></i>
            <i class="fab fa-cc-paypal fa-2x me-2 text-muted"></i>
            <i class="fas fa-money-bill-wave fa-2x text-muted"></i>
          </div>
        </div>
      </div>
      <hr class="my-4 bg-light opacity-25">
      <div class="row align-items-center">
        <div class="col-md-6">
          <p class="mb-0">&copy; 2025 Cake Heaven. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <a href="#" class="text-light text-decoration-none me-3">Privacy Policy</a>
          <a href="#" class="text-light text-decoration-none">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Contact Form Handling
    document.getElementById('contactForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const msgDiv = document.getElementById('contactMsg');
      msgDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.';
      msgDiv.style.color = 'var(--deep-red)';
      this.reset();
      
      setTimeout(() => {
        msgDiv.innerHTML = '';
      }, 5000);
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 100) {
        navbar.style.padding = '10px 0';
        navbar.style.boxShadow = '0 4px 20px rgba(61, 53, 27, 0.4)';
      } else {
        navbar.style.padding = '15px 0';
        navbar.style.boxShadow = '0 4px 20px rgba(61, 53, 27, 0.3)';
      }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  const filterBtns = document.querySelectorAll('.filter-btn');
  const cakes = document.querySelectorAll('.cake-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.getAttribute('data-cat');
      cakes.forEach(cake => {
        if(cat === 'all' || cake.getAttribute('data-cat') === cat) {
          cake.style.display = 'block';
        } else {
          cake.style.display = 'none';
        }
      });
    });
  });
  </script>
</body>
</html>