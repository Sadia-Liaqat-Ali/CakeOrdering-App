<?php
session_start();
include("../dbconnection.php");
include("sidebar.php");

// ---- Check Login ----
if (!isset($_SESSION['user_id'])) {
    header("Location: user-login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ---- Fetch User Data ----
$res = $conn->query("SELECT * FROM tbl_users WHERE User_id='$user_id'");
$user = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

// ---- Handle Update ----
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];
    $password = $_POST['password'];

    // ✅ Update password only if user entered new one
    if (!empty($password)) {
        $update = $conn->query("UPDATE tbl_users 
                                SET UserName='$username', Email='$email', Contact='$contact', Address='$address', Password='$password' 
                                WHERE User_id='$user_id'");
    } else {
        $update = $conn->query("UPDATE tbl_users 
                                SET UserName='$username', Email='$email', Contact='$contact', Address='$address' 
                                WHERE User_id='$user_id'");
    }

    if ($update) {
        echo "<script>alert('✅ Profile updated successfully!'); window.location='update-profile.php';</script>";
    } else {
        echo "<script>alert('❌ Update failed! Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Profile | Sweet Delights</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background: #FFF287; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .main-content { margin-left: 270px; padding: 20px; }
    .profile-card {
        background: white; border-radius: 10px; padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 800px; margin: auto;
    }
    .form-label { font-weight: 600; color: #5a1d0e; }
    .btn-custom {
        background-color: #C83F12; color: white; border-radius: 8px;
    }
    .btn-custom:hover { background-color: #a12f0d; }
    h3 { color: #C83F12; font-weight: 700; }
</style>
</head>
<body>

<div class="main-content">
  <div class="profile-card">
    <h3 class="text-center mb-4"><i class="fa fa-user-circle me-2"></i>Update Profile</h3>

    <?php if ($user): ?>
    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Full Name</label>
        <input type="text" name="username" class="form-control"
               value="<?= htmlspecialchars($user['UserName']) ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($user['Email']) ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Contact Number</label>
        <input type="text" name="contact" class="form-control"
               value="<?= htmlspecialchars($user['Contact'] ?? '') ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control"
               value="<?= htmlspecialchars($user['Address'] ?? '') ?>">
      </div>

      <div class="col-md-12">
        <label class="form-label">New Password (Leave blank to keep current)</label>
        <input type="password" name="password" class="form-control" placeholder="Enter new password if you want to change">
      </div>

      <div class="col-12 text-center mt-4">
        <button type="submit" class="btn btn-custom px-4"><i class="fa fa-save me-2"></i>Update Profile</button>
      </div>
    </form>
    <?php else: ?>
      <div class="alert alert-danger text-center mt-3">❌ User not found!</div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
