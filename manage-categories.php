<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php'; // ✅ External sidebar added (consistent with other admin pages)

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// ---- For Editing (Load Data into Top Input) ----
$edit_id = "";
$edit_name = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $fetch = $conn->query("SELECT * FROM tbl_categories WHERE id='$edit_id'");
    if ($fetch->num_rows == 1) {
        $row = $fetch->fetch_assoc();
        $edit_name = $row['category_name'];
    }
}

// ---- Add New Category ----
if (isset($_POST['add_category'])) {
    $cat_name = trim($_POST['category_name']);
    if (!empty($cat_name)) {
        $check = $conn->query("SELECT * FROM tbl_categories WHERE category_name='$cat_name'");
        if ($check->num_rows > 0) {
            $msg = "❌ Category already exists!";
        } else {
            $insert = $conn->query("INSERT INTO tbl_categories (category_name) VALUES ('$cat_name')");
            $msg = $insert ? "✅ Category added successfully!" : "❌ Error adding category!";
        }
    } else {
        $msg = "⚠️ Please enter a category name!";
    }
}

// ---- Update Category ----
if (isset($_POST['update_category'])) {
    $cat_id = $_POST['cat_id'];
    $cat_name = trim($_POST['category_name']);
    if (!empty($cat_name)) {
        $update = $conn->query("UPDATE tbl_categories SET category_name='$cat_name' WHERE id='$cat_id'");
        $msg = $update ? "✅ Category updated successfully!" : "❌ Error updating category!";
    } else {
        $msg = "⚠️ Please enter a category name!";
    }
}

// ---- Delete Category ----
if (isset($_GET['delete'])) {
    $cat_id = $_GET['delete'];
    $delete = $conn->query("DELETE FROM tbl_categories WHERE id='$cat_id'");
    $msg = $delete ? "🗑️ Category deleted successfully!" : "❌ Error deleting category!";
}

// ---- Fetch All Categories ----
$categories = $conn->query("SELECT * FROM tbl_categories ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
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
      margin-left: 260px; /* Adjust for sidebar width */
      flex: 1;
      padding: 30px;
      background: #FFF287;
    }
    .content-box {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      max-width: 1100px;
      margin: auto;
    }
    .btn-primary-custom {
      background: linear-gradient(135deg, #C83F12, #8A0000);
      border: none;
      color: white;
      font-weight: 600;
    }
    .btn-primary-custom:hover {
      background: linear-gradient(135deg, #8A0000, #C83F12);
      transform: translateY(-2px);
    }
    .table thead {
      background: #8A0000;
      color: white;
    }
    .table tbody tr:hover {
      background-color: #fff8e6;
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="content-box">
    <h2 class="text-center mb-4" style="color:#3B060A; font-weight:bold;">
      🍰 Manage Cake Categories
    </h2>

    <?php if (!empty($msg)) { ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo $msg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php } ?>

    <!-- Add or Edit Category -->
    <div class="card mb-4 border-0 bg-light shadow-sm">
      <div class="card-body">
        <h5 class="card-title">
          <i class="fas <?php echo $edit_id ? 'fa-edit' : 'fa-plus-circle'; ?> me-2"></i>
          <?php echo $edit_id ? 'Edit Category' : 'Add New Category'; ?>
        </h5>
        <form method="POST" class="row g-3">
          <input type="hidden" name="cat_id" value="<?php echo $edit_id; ?>">
          <div class="col-md-8">
            <input type="text" name="category_name" 
              value="<?php echo htmlspecialchars($edit_name); ?>" 
              class="form-control form-control-lg" 
              placeholder="Enter category name" required>
          </div>
          <div class="col-md-4">
            <?php if ($edit_id) { ?>
              <button type="submit" name="update_category" class="btn btn-success w-100 btn-lg">
                <i class="fas fa-save me-1"></i> Update
              </button>
              <a href="manage-categories.php" class="btn btn-secondary w-100 btn-lg mt-2">
                <i class="fas fa-times me-1"></i> Cancel
              </a>
            <?php } else { ?>
              <button type="submit" name="add_category" class="btn btn-primary-custom w-100 btn-lg">
                <i class="fas fa-plus-circle me-1"></i> Add Category
              </button>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Categories Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0">
          <i class="fas fa-list me-2"></i> Existing Categories
        </h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead>
              <tr>
                <th width="10%" class="text-center">#</th>
                <th>Category Name</th>
                <th width="25%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              if ($categories->num_rows > 0) {
                  $i = 1;
                  while ($row = $categories->fetch_assoc()) {
              ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td class="fw-semibold"><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td class="text-center">
                  <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit
                  </a>
                  <a href="?delete=<?php echo $row['id']; ?>" 
                    onclick="return confirm('Are you sure you want to delete this category?')" 
                    class="btn btn-sm btn-danger">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                  </a>
                </td>
              </tr>
              <?php 
                  }
              } else { ?>
              <tr>
                <td colspan="3" class="text-center py-4 text-muted">
                  <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                  No categories found. Add your first category above.
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
