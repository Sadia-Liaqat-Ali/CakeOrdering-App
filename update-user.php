<!-- update-user.php -->
<?php
session_start();
include("../dbconnection.php");
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";
$userid = $_GET['id'] ?? null;

if ($userid) {
    $res = mysqli_query($conn, "SELECT * FROM tbl_users WHERE User_id='$userid'");
    if (mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
    } else {
        echo "<script>alert('❌ User not found!'); window.location='manage-users.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('❌ No User selected!'); window.location='manage-users.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['UserName']);
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $contact = mysqli_real_escape_string($conn, $_POST['Contact']);
    $address = mysqli_real_escape_string($conn, $_POST['Address']);

    $update_q = "UPDATE tbl_users SET 
        UserName='$name', 
        Email='$email', 
        Contact='$contact', 
        Address='$address' 
        WHERE User_id='$userid'";

    if (mysqli_query($conn, $update_q)) {
        echo "<script>alert('✅ User updated successfully!'); window.location='manage-users.php';</script>";
    } else {
        $message = "❌ Update failed: " . mysqli_error($conn);
    }
}
?>

<div class="main-content" style="margin-left:260px; padding:40px; background: #FFF287; min-height:100vh;">
  <div class="container bg-white p-4 rounded shadow" style="max-width:950px;">
    <h2 class="text-center mb-4" style="color:#4B1D06; font-weight:700;">✏ Update User</h2>

    <?php if ($message): ?>
      <div class="alert alert-danger text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-bold">User ID</label>
        <input type="number" class="form-control" value="<?php echo $user['User_id']; ?>" readonly>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">User Name</label>
        <input type="text" name="UserName" class="form-control" value="<?php echo htmlspecialchars($user['UserName']); ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">Email</label>
        <input type="email" name="Email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-bold">Contact</label>
        <input type="text" name="Contact" class="form-control" value="<?php echo htmlspecialchars($user['Contact']); ?>">
      </div>

      <div class="col-md-12">
        <label class="form-label fw-bold">Address</label>
        <textarea name="Address" class="form-control" rows="3"><?php echo htmlspecialchars($user['Address']); ?></textarea>
      </div>

      <div class="col-12 text-center mt-4">
        <button type="submit" name="update" class="btn btn-warning text-white px-4 fw-bold">
          <i class="fas fa-save me-1"></i> Update User
        </button>
        <a href="manage-users.php" class="btn btn-secondary px-4 ms-2">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
      </div>
    </form>
  </div>
</div>
