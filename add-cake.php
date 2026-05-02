<?php
session_start();
include("../dbconnection.php");
include 'sidebar.php'; // ✅ External sidebar added

// ---- Fetch Categories for Dropdown ----
$categories = $conn->query("SELECT * FROM tbl_categories ORDER BY category_name ASC");

// ---- Add New Cake ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cakeid       = $_POST['cakeid'];
    $cakename     = $_POST['cakename'];
    $cakeprice    = $_POST['cakeprice'];
    $category_id  = $_POST['category_id'];
    $description  = $_POST['description'];

    if (isset($_FILES['cakepicture']) && $_FILES['cakepicture']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['cakepicture']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            $newFile = $cakeid . '_' . time() . '.' . $ext;
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $dest = $uploadDir . $newFile;

            if (move_uploaded_file($_FILES['cakepicture']['tmp_name'], $dest)) {
                $save = $conn->query("INSERT INTO tbl_cakes (cakeId, cakeName, cakePrice, cakePicture, category_id, description)
                                      VALUES ('$cakeid','$cakename','$cakeprice','$newFile','$category_id','$description')");
                if ($save) {
                    echo "<script>alert('✅ Cake added successfully!');</script>";
                } else {
                    echo "<script>alert('❌ Database error!');</script>";
                }
            } else {
                echo "<script>alert('❌ Failed to upload file!');</script>";
            }
        } else {
            echo "<script>alert('❌ Invalid file type! Only jpg, jpeg, png, gif allowed.');</script>";
        }
    } else {
        echo "<script>alert('❌ Please upload a cake picture!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Cake</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #FFF5E4;
      display: flex;
      min-height: 100vh;
      overflow-x: hidden;
    }
    .main-content {
      margin-left: 260px; /* space for sidebar */
      flex: 1;
      padding: 30px;
      background: #FFF287;
    }
    .content-box {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      max-width: 1000px;
      margin: auto;
    }
    .btn-warning {
      background: linear-gradient(135deg, #C83F12, #8A0000);
      border: none;
      font-weight: 600;
    }
    .btn-warning:hover {
      background: linear-gradient(135deg, #8A0000, #C83F12);
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="content-box">
    <h2 class="text-center mb-4" style="color:#3B060A; font-weight:bold;">
      🎂 Add New Cake
    </h2>

    <form method="POST" enctype="multipart/form-data" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Cake ID</label>
        <input type="number" name="cakeid" class="form-control" placeholder="Enter Cake ID" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Cake Name</label>
        <input type="text" name="cakename" class="form-control" placeholder="Enter Cake Name" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Cake Price (Rs.)</label>
        <input type="number" step="0.01" name="cakeprice" class="form-control" placeholder="Enter Price" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Select Category</label>
        <select name="category_id" class="form-control" required>
          <option value="">-- Select Category --</option>
          <?php while ($cat = $categories->fetch_assoc()) { ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-12">
        <label class="form-label">Cake Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Enter short description..." required></textarea>
      </div>

      <div class="col-md-12">
        <label class="form-label">Cake Picture</label>
        <input type="file" name="cakepicture" class="form-control" accept=".jpg,.jpeg,.png,.gif" required>
      </div>

      <div class="col-md-12 text-center mt-3">
        <button type="submit" class="btn btn-warning text-white px-4">
          <i class="fas fa-plus-circle me-1"></i> Add Cake
        </button>
        <a href="dashboard.php" class="btn btn-secondary px-4 ms-2">
          <i class="fas fa-home me-1"></i> Back to Dashboard
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
