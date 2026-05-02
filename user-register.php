<?php
session_start();
include("../dbconnection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];

    // hash password before saving

    // check if email already exists
    $check = $conn->query("SELECT * FROM tbl_users WHERE Email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('❌ Email already exists!');</script>";
    } else {
        $insert = $conn->query("INSERT INTO tbl_users (UserName, Password, Email, Contact, Address) 
                                VALUES ('$username','$hashed_password','$email','$contact','$address')");
        if ($insert) {
            echo "<script>alert('✅ Registration successful! Redirecting to login...'); 
                  window.location='user-login.php';</script>";
        } else {
            echo "<script>alert('❌ Something went wrong!');</script>";
        }
    }
}
?>         <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #FFF287, #C83F12);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .registration-card {
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      border: none;
      border-radius: 15px;
      overflow: hidden;
    }
    .card-header {
      background: linear-gradient(135deg, #8A0000, #C83F12);
      color: white;
      text-align: center;
      padding: 25px 20px;
    }
    .btn-primary {
      background: linear-gradient(135deg, #8A0000, #C83F12);
      border: none;
      padding: 12px;
      font-weight: 600;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #C83F12, #8A0000);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(200, 63, 18, 0.4);
    }
    .form-control:focus {
      border-color: #C83F12;
      box-shadow: 0 0 0 0.25rem rgba(200, 63, 18, 0.25);
    }
    .link-primary {
      color: #8A0000;
      text-decoration: none;
      font-weight: 500;
    }
    .link-primary:hover {
      color: #C83F12;
      text-decoration: underline;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card registration-card">
          <div class="card-header">
            <h2 class="mb-0"><i class="fas fa-user-plus me-2"></i>User Registration</h2>
            <p class="mb-0 mt-2 opacity-75">Join Cake Heaven today!</p>
          </div>
          <div class="card-body p-4">
            <form method="POST">
              
              <div class="mb-3">
                <label for="username" class="form-label">Full Name</label>
                <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="Enter your full name" required>
              </div>
              
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required>
              </div>
              
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Create a password" required>
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" id="contact" name="contact" class="form-control form-control-lg" placeholder="Enter your phone number">
              </div>

              <div class="mb-4">
                <label for="address" class="form-label">Address</label>
                <textarea id="address" name="address" class="form-control form-control-lg" placeholder="Enter your address" required></textarea>
              </div>
              
              
              
              <div class="text-center mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="terms" required>
                  <label class="form-check-label" for="terms">
                    I agree to the <a href="#" class="link-primary">Terms & Conditions</a>
                  </label>
                </div>
              </div>
              <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
              </div>
            </form>
            
            <div class="text-center pt-3 border-top">
              <p class="mb-2">
                <i class="fas fa-home me-2 text-muted"></i>
                <a href="../index.php" class="link-primary">Back to Home</a>
              </p>
              <p class="mb-0">
                <i class="fas fa-sign-in-alt me-2 text-muted"></i>
                <a href="user-login.php" class="link-primary">Already have an account? Login here</a>
              </p>
            </div>
          </div>
        </div>
        
        <div class="text-center mt-4 text-white">
          <p class="mb-0">&copy; 2025 Cake Heaven. Sweet moments made special.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
  </script>
</body>
</html>
