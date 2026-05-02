<?php
session_start();
include("../dbconnection.php");
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";
$cakeId = $_GET['id'] ?? null;

// ===== Fetch all categories =====
$categories = [];
$res = mysqli_query($conn, "SELECT * FROM tbl_categories ORDER BY category_name ASC");
while ($row = mysqli_fetch_assoc($res)) {
    $categories[] = $row;
}

// ===== Fetch current cake =====
if ($cakeId) {
    $cake_q = mysqli_query($conn, "SELECT * FROM tbl_cakes WHERE cakeId='$cakeId'");
    if (mysqli_num_rows($cake_q) > 0) {
        $cake = mysqli_fetch_assoc($cake_q);
    } else {
        echo "<script>alert('❌ Cake not found!'); window.location='manage-cakes.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('❌ No Cake selected!'); window.location='manage-cakes.php';</script>";
    exit;
}

// ===== Handle update =====
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['cakeName']);
    $price = mysqli_real_escape_string($conn, $_POST['cakePrice']);
    $category = mysqli_real_escape_string($conn, $_POST['category_id']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $oldPicture = $_POST['oldPicture'];
    $newPicture = $oldPicture;

    // --- Handle new picture upload ---
    if (!empty($_FILES['cakePicture']['name'])) {
        $newPicture = $cakeId . "_" . time() . "_" . basename($_FILES['cakePicture']['name']);
        $uploadPath = "../uploads/" . $newPicture;

        if (move_uploaded_file($_FILES['cakePicture']['tmp_name'], $uploadPath)) {
            if (file_exists("../uploads/" . $oldPicture)) {
                unlink("../uploads/" . $oldPicture);
            }
        }
    }

    // --- Update record ---
    $update_q = "UPDATE tbl_cakes 
                 SET cakeName='$name', 
                     cakePrice='$price', 
                     category_id='$category', 
                     description='$desc', 
                     cakePicture='$newPicture' 
                 WHERE cakeId='$cakeId'";

    if (mysqli_query($conn, $update_q)) {
        echo "<script>alert('✅ Cake updated successfully!'); window.location='manage-cakes.php';</script>";
    } else {
        $message = "❌ Update failed: " . mysqli_error($conn);
    }
}
?>

<!-- Page Layout -->
<div class="main-content p-4" style="flex:1; background:#FFF287; min-height:100vh;">
  <div class="container bg-white p-4 rounded shadow" style="max-width:1000px;">
    <h2 class="text-center mb-4" style="color:#3B060A; font-weight:bold;">
      ✏ Update Cake
    </h2>

    <?php if ($message): ?>
      <div class="alert alert-danger text-center"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Cake ID</label>
        <input type="number" class="form-control" value="<?php echo $cake['cakeId']; ?>" readonly>
      </div>

      <div class="col-md-6">
        <label class="form-label">Cake Name</label>
        <input type="text" name="cakeName" class="form-control" value="<?php echo htmlspecialchars($cake['cakeName']); ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Cake Price (Rs.)</label>
        <input type="number" step="0.01" name="cakePrice" class="form-control" value="<?php echo htmlspecialchars($cake['cakePrice']); ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Select Category</label>
        <select name="category_id" class="form-control" required>
          <option value="">-- Select Category --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $cake['category_id']) echo 'selected'; ?>>
              <?php echo htmlspecialchars($cat['category_name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-12">
        <label class="form-label">Cake Description</label>
        <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($cake['description']); ?></textarea>
      </div>

      <div class="col-md-12">
        <label class="form-label">Current Picture</label><br>
        <img src="../uploads/<?php echo htmlspecialchars($cake['cakePicture']); ?>" style="width:150px; height:120px; border-radius:8px; border:2px solid #ddd;">
        <input type="hidden" name="oldPicture" value="<?php echo htmlspecialchars($cake['cakePicture']); ?>">
      </div>

      <div class="col-md-12">
        <label class="form-label">Change Picture (Optional)</label>
        <input type="file" name="cakePicture" class="form-control" accept=".jpg,.jpeg,.png,.gif">
      </div>

      <div class="col-md-12 text-center mt-3">
        <button type="submit" name="update" class="btn btn-warning text-white px-4">
          <i class="fas fa-save me-1"></i> Update Cake
        </button>
        <a href="manage-cakes.php" class="btn btn-secondary px-4 ms-2">
          <i class="fas fa-arrow-left me-1"></i> Back to Manage Cakes
        </a>
      </div>
    </form>
  </div>
</div>
