<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php'; // ✅ External Sidebar Included

if (!isset($_SESSION['user_id'])) {
  header("Location: login-user.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Delete selected cakes
if (isset($_POST['delete_selected'])) {
  $selected = $_POST['selected_cakes'] ?? [];
  foreach ($selected as $cake_id) {
    mysqli_query($conn, "DELETE FROM tbl_cart WHERE user_id='$user_id' AND cake_id='$cake_id'");
  }
  echo "<script>alert('Selected cakes deleted successfully!');window.location.href='my-cart.php';</script>";
}

// ✅ Clear entire cart
if (isset($_POST['clear_cart'])) {
  mysqli_query($conn, "DELETE FROM tbl_cart WHERE user_id='$user_id'");
  echo "<script>alert('Cart cleared successfully!');window.location.href='my-cart.php';</script>";
}

// ✅ Update Quantity
if (isset($_POST['update_cart'])) {
  foreach ($_POST['quantities'] as $cake_id => $qty) {
    $qty = max(1, intval($qty));
    mysqli_query($conn, "UPDATE tbl_cart SET quantity='$qty' WHERE user_id='$user_id' AND cake_id='$cake_id'");
  }
  echo "<script>alert('Cart updated successfully!');window.location.href='my-cart.php';</script>";
}

// ✅ Fetch cart items with cake details
$query = "SELECT c.cakeId, c.cakeName, c.cakePrice, c.cakePicture, c.description, ct.category_name, t.quantity 
          FROM tbl_cart t 
          JOIN tbl_cakes c ON t.cake_id = c.cakeId 
          LEFT JOIN tbl_categories ct ON c.category_id = ct.id
          WHERE t.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

// ✅ Calculate total price
$total = 0;
$cart_items = [];
while ($row = mysqli_fetch_assoc($result)) {
  $row['subtotal'] = $row['cakePrice'] * $row['quantity'];
  $total += $row['subtotal'];
  $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #FFF287;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-content {
      margin-left: 270px;
      padding: 20px;
    }
    .cart-table {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .cart-table th {
      background-color: #C83F12;
      color: white;
      text-align: center;
    }
    .cart-table td {
      vertical-align: middle;
      text-align: center;
    }
    .cake-img {
      height: 80px;
      width: 80px;
      object-fit: cover;
      border-radius: 10px;
    }
    .cart-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
      flex-wrap: wrap;
      gap: 10px;
    }
    .btn-danger, .btn-success, .btn-secondary {
      border-radius: 8px;
      font-weight: 600;
    }
    .total-box {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: right;
      font-size: 20px;
      font-weight: bold;
      color: #8A0000;
    }
    input[type="number"] {
      width: 70px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="main-content">
    <h2 class="mb-4 text-danger"><i class="fa fa-shopping-cart me-2"></i>My Cart</h2>

    <form method="post" id="cartForm">
      <div class="table-responsive cart-table">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th>Image</th>
              <th>Cake Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($cart_items)) { ?>
              <tr><td colspan="7" class="text-center text-muted">Your cart is empty!</td></tr>
            <?php } else { foreach ($cart_items as $item) { ?>
              <tr>
                <td><input type="checkbox" name="selected_cakes[]" value="<?php echo $item['cakeId']; ?>" class="form-check-input"></td>
                <td><img src="../uploads/<?php echo $item['cakePicture']; ?>" class="cake-img"></td>
                <td><?php echo htmlspecialchars($item['cakeName']); ?></td>
                <td><?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?></td>
                <td>Rs. <?php echo number_format($item['cakePrice'], 2); ?></td>
                <td>
                  <input type="number" name="quantities[<?php echo $item['cakeId']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm mx-auto">
                </td>
                <td>Rs. <?php echo number_format($item['subtotal'], 2); ?></td>
              </tr>
            <?php }} ?>
          </tbody>
        </table>
      </div>

      <?php if (!empty($cart_items)) { ?>
      <div class="total-box mt-3">
        Total Price: Rs. <?php echo number_format($total, 2); ?>
      </div>

      <div class="cart-actions">
        <div>
          <button type="submit" name="update_cart" class="btn btn-success"><i class="fa fa-sync me-1"></i> Update Cart</button>
          <button type="submit" name="delete_selected" class="btn btn-danger"><i class="fa fa-trash me-1"></i> Delete Selected</button>
          <button type="submit" name="clear_cart" class="btn btn-secondary"><i class="fa fa-ban me-1"></i> Clear Cart</button>
        </div>
        <div>
          <a href="view-cakes.php" class="btn btn-warning text-white"><i class="fa fa-store me-1"></i> Continue Shopping</a>
          <a href="checkout.php" class="btn btn-primary text-white"><i class="fa fa-arrow-right me-1"></i> Proceed to Checkout</a>
        </div>
      </div>
      <?php } ?>
    </form>
  </div>

  <script>
    // ✅ Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
      document.querySelectorAll('input[name="selected_cakes[]"]').forEach(chk => chk.checked = this.checked);
    });
  </script>
</body>
</html>
