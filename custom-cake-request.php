<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login-user.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// ---- Handle Form Submission ----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $cake_title = $_POST['cake_title'];
  $description = $_POST['description'];
  $design_preference = $_POST['design_preference'];
  $ingredients = $_POST['ingredients'];
  $flavor = $_POST['flavor'];
  $quantity = $_POST['quantity'];
  $budget = $_POST['budget'];
  $occasion = $_POST['occasion'];
  $delivery_date = $_POST['delivery_date'];

  $image = '';

if (isset($_FILES['design_image']) && $_FILES['design_image']['name'] != '') {

    $image_name = time().'_'.$_FILES['design_image']['name'];
    $upload_path = '../uploads/custom_designs/'.$image_name;

    if (move_uploaded_file($_FILES['design_image']['tmp_name'], $upload_path)) {
        $image = $image_name;
    }
}
  $sql = "INSERT INTO tbl_custom_requests (user_id, cake_title, description, design_preference, ingredients, flavor, quantity, budget, occasion, delivery_date, image)
          VALUES ('$user_id', '$cake_title', '$description', '$design_preference', '$ingredients', '$flavor', '$quantity', '$budget', '$occasion', '$delivery_date', '$image')";
  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('🎂 Custom cake request submitted successfully!');window.location='custom-requests-status.php';</script>";
  } else {
    echo "<script>alert('❌ Something went wrong. Try again.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Custom Cake Request</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#FFF287; font-family:'Segoe UI'; }
.main-content { margin-left:270px; padding:20px; }
.form-box { background:white; border-radius:10px; padding:30px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
h3 { color:#C83F12; text-align:center; margin-bottom:20px; }
label { font-weight:500; color:#6c2c0c; }
</style>
</head>
<body>
<div class="main-content">
  <div class="form-box">
    <h3>🎂 Submit Custom Cake Request</h3>
    <form method="POST" enctype="multipart/form-data" class="row g-3">
      <div class="col-md-6">
        <label>Cake Title</label>
        <input type="text" name="cake_title" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label>Flavor</label>
        <input type="text" name="flavor" class="form-control" placeholder="Chocolate, Vanilla, etc.">
      </div>
      <div class="col-md-12">
        <label>Design Preference</label>
        <input type="text" name="design_preference" class="form-control" placeholder="Heart shape, 3-tier, theme-based...">
      </div>
      <div class="col-md-12">
        <label>Ingredients</label>
        <textarea name="ingredients" rows="2" class="form-control" placeholder="List of ingredients you prefer"></textarea>
      </div>
      <div class="col-md-12">
        <label>Additional Description</label>
        <textarea name="description" rows="3" class="form-control" placeholder="Describe your design idea or message on cake"></textarea>
      </div>
      <div class="col-md-4">
        <label>Quantity (in lbs)</label>
        <input type="number" name="quantity" class="form-control" value="1" required>
      </div>
      <div class="col-md-4">
        <label>Estimated Budget (Rs.)</label>
        <input type="number" name="budget" class="form-control" placeholder="e.g. 2500">
      </div>
      <div class="col-md-4">
        <label>Occasion</label>
        <input type="text" name="occasion" class="form-control" placeholder="Birthday, Wedding, etc.">
      </div>
      <div class="col-md-6">
        <label>Preferred Delivery Date</label>
        <input type="date" name="delivery_date" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label>Upload Design Sample (optional)</label>
        <input type="file" name="design_image" class="form-control" accept=".jpg,.png,.jpeg">
      </div>
      <div class="col-12 text-center mt-3">
        <button type="submit" class="btn btn-danger px-4">Submit Request</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>