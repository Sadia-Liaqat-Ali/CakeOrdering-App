<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php'; // ✅ External Sidebar Included

if (!isset($_SESSION['user_id'])) {
  header("Location: login-user.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Add to Cart (Multiple or Single)
if (isset($_POST['add_to_cart'])) {
  $selected_cakes = $_POST['selected_cakes'] ?? [];
  foreach ($selected_cakes as $cake_id) {
    $check = mysqli_query($conn, "SELECT * FROM tbl_cart WHERE user_id='$user_id' AND cake_id='$cake_id'");
    if (mysqli_num_rows($check) == 0) {
      mysqli_query($conn, "INSERT INTO tbl_cart (user_id, cake_id, quantity) VALUES ('$user_id', '$cake_id', 1)");
    }
  }
  echo "<script>alert('Selected cakes added to cart successfully!');
  window.location.href='my-cart.php';</script>";
}

// ✅ Search + Filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT * FROM tbl_cakes WHERE 1";
if ($search != '') {
  $query .= " AND (cakeName LIKE '%$search%' OR cakeId='$search')";
}
if ($category_filter != '') {
  $query .= " AND category_id='$category_filter'";
}
$cakes = mysqli_query($conn, $query);

// ✅ Fetch Categories for Dropdown
$cat_res = mysqli_query($conn, "SELECT * FROM tbl_categories");

// ✅ Make an array of category_id => category_name for easy lookup
$categories = [];
$cat_data = mysqli_query($conn, "SELECT * FROM tbl_categories");
while ($c = mysqli_fetch_assoc($cat_data)) {
  $categories[$c['id']] = $c['category_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Cakes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #FFF287;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-content {
      margin-left: 270px; /* ✅ Sidebar width */
      padding: 20px;
    }
    .filter-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      padding: 10px 20px;
      position: sticky;
      top: 10px;
      z-index: 10;
    }
    .filter-bar form {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .cake-card {
      border-radius: 10px;
      overflow: hidden;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: 0.3s;
      position: relative;
    }
    .cake-card:hover {
      transform: translateY(-5px);
    }
    .cake-card img {
      height: 250px;
      width: 100%;
      object-fit: cover;
    }
    .cake-info {
      padding: 12px;
    }
    .cake-info p {
      margin: 0;
      font-size: 14px;
    }
    .cake-name {
      font-weight: bold;
      color: #8A0000;
      text-transform: capitalize;
    }
    .cake-price {
      font-weight: bold;
      color: #C83F12;
    }
    .cake-details {
      padding: 10px;
      display: flex;
      justify-content: space-between;
      font-size: 20px;
    }
    .select-checkbox {
      position: absolute;
      top: 10px;
      right: 10px;
      transform: scale(1.3);
      display: none;
    }
    .multi-select-active .select-checkbox {
      display: block;
    }
    .multi-select-btn, .add-selected-btn {
      background-color: #C83F12;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 8px 15px;
      font-weight: 600;
      cursor: pointer;
    }
    .multi-select-btn:hover, .add-selected-btn:hover {
      background-color: #8A0000;
    }
  </style>
</head>
<body>
  <!-- ✅ Sidebar included externally -->
  <div class="main-content">
    <div class="filter-bar">
      <form method="get">
        <input type="text" name="search" class="form-control" style="width:200px;" placeholder="Search by Cake ID or Name" value="<?php echo htmlspecialchars($search); ?>">
        <select name="category" class="form-select" style="width:200px;">
          <option value="">All Categories</option>
          <?php while ($cat = mysqli_fetch_assoc($cat_res)) { ?>
            <option value="<?php echo $cat['id']; ?>" <?php if ($category_filter == $cat['id']) echo 'selected'; ?>>
              <?php echo $cat['category_name']; ?>
            </option>
          <?php } ?>
        </select>
        <button class="btn btn-danger" type="submit"><i class="fa fa-filter me-1"></i>Filter</button>
      </form>

      <div class="d-flex gap-2">
        <button id="multiSelectBtn" class="multi-select-btn"><i class="fa fa-check-square me-1"></i>Select Multiple</button>
        <button type="submit" form="cakeForm" name="add_to_cart" class="add-selected-btn"><i class="fa fa-cart-plus me-1"></i>Add Selected Cakes</button>
      </div>
    </div>

    <form method="post" id="cakeForm" class="mt-4">
      <div class="row g-4">
        <?php while ($cake = mysqli_fetch_assoc($cakes)) { 
          $cat_name = isset($categories[$cake['category_id']]) ? $categories[$cake['category_id']] : 'Uncategorized';
        ?>
          <div class="col-md-4">
            <div class="cake-card">
              <input type="checkbox" name="selected_cakes[]" value="<?php echo $cake['cakeId']; ?>" class="select-checkbox form-check-input">
              <img src="../uploads/<?php echo $cake['cakePicture']; ?>" alt="<?php echo $cake['cakeName']; ?>">
              <div class="cake-info">
                <div class="cake-details">
                  <span class="cake-name"><?php echo $cake['cakeName']; ?></span>
                  <span class="cake-price">Rs. <?php echo $cake['cakePrice']; ?></span>
                </div>
                <p><strong>Cake ID:</strong> <?php echo $cake['cakeId']; ?></p>
                <p><strong>Category:</strong> <?php echo $cat_name; ?></p>
                <p><strong>Description:</strong> <?php echo $cake['description']; ?></p>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </form>
  </div>

  <script>
    const multiBtn = document.getElementById('multiSelectBtn');
    let active = false;
    multiBtn.addEventListener('click', () => {
      active = !active;
      document.body.classList.toggle('multi-select-active', active);
      multiBtn.innerHTML = active
        ? '<i class="fa fa-times me-1"></i> Cancel'
        : '<i class="fa fa-check-square me-1"></i> Select Multiple';
    });
  </script>
</body>
</html>
