<?php
session_start();
include("../dbconnection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check admin by email
    $query = "SELECT * FROM tbl_admin WHERE Emailid = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password (MD5)
        if ($admin['Password'] == md5($password)) {
            $_SESSION['admin_id'] = $admin['Id'];
            $_SESSION['admin_name'] = $admin['Name'];
            echo "<script>alert('✅ Login Successful!'); window.location='../admin/dashboard.php';</script>";
        } else {
            echo "<script>alert('❌ Incorrect Password!');</script>";
        }
    } else {
        echo "<script>alert('❌ Email not found!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #FFF287, #C83F12);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
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
        <div class="card login-card">
          <div class="card-header">
            <h2 class="mb-0"><i class="fas fa-user-shield me-2"></i>Admin Login</h2>
            <p class="mb-0 mt-2 opacity-75">Welcome back to Cake Admin Panel!</p>
          </div>
          <div class="card-body p-4">
            <form method="POST">
              
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required>
              </div>
              
              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              
              <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
              </div>
              
              <div class="text-center mb-3">
                <p class="mb-0">
                  <a href="../index.php" class="link-primary">🏠 Back to Home</a>
                </p>
              </div>
            </form>
          </div>
        </div>
        
        <div class="text-center mt-4 text-white">
          <p class="mb-0">&copy; 2025 Cake Heaven Admin Panel</p>
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
