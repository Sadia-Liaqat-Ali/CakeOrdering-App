<?php
// ✅ checkout.php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login-user.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$delivery_charges = 300; // ✅ Delivery charges

// ✅ Fetch cart items
$cart_q = "SELECT c.cakeId, c.cakeName, c.cakePrice, t.quantity 
           FROM tbl_cart t 
           JOIN tbl_cakes c ON t.cake_id = c.cakeId 
           WHERE t.user_id='$user_id'";
$res = mysqli_query($conn, $cart_q);

$cart_items = [];
$total = 0;
while ($row = mysqli_fetch_assoc($res)) {
  $row['subtotal'] = $row['cakePrice'] * $row['quantity'];
  $total += $row['subtotal'];
  $cart_items[] = $row;
}

// ✅ Add delivery charges to total
$total_with_delivery = $total + $delivery_charges;

// ✅ Place Order
if (isset($_POST['place_order'])) {
  $name = $_POST['name'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];
  $date = date('Y-m-d H:i:s');

  mysqli_query($conn, "INSERT INTO tbl_orders (user_id, customer_name, address, phone, total_price, order_date, status) 
                       VALUES ('$user_id', '$name', '$address', '$phone', '$total_with_delivery', '$date', 'Pending')");
  $order_id = mysqli_insert_id($conn);

  foreach ($cart_items as $item) {
    mysqli_query($conn, "INSERT INTO tbl_order_items (order_id, cake_id, quantity, price) 
                         VALUES ('$order_id', '{$item['cakeId']}', '{$item['quantity']}', '{$item['cakePrice']}')");
  }

  mysqli_query($conn, "DELETE FROM tbl_cart WHERE user_id='$user_id'");
  echo "<script>alert('Order placed successfully!');window.location='track-order.php?id=$order_id';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #FFF287; font-family: 'Segoe UI'; }
    .main-content { margin-left: 270px; padding: 20px; }
    .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .btn-danger { border-radius: 8px; font-weight: 600; }
  </style>
</head>
<body>
<div class="main-content">
  <h2 class="text-danger mb-3"><i class="fa fa-credit-card me-2"></i>Checkout</h2>

  <div class="card p-4">
    <form method="post">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Phone No.</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Delivery Address</label>
        <textarea name="address" class="form-control" required></textarea>
      </div>

      <h5 class="text-danger mt-4">Order Summary</h5>
      <ul class="list-group mb-3">
        <?php foreach ($cart_items as $item) { ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?php echo $item['cakeName']; ?> (x<?php echo $item['quantity']; ?>)
            <span>Rs. <?php echo number_format($item['subtotal'], 2); ?></span>
          </li>
        <?php } ?>
        <li class="list-group-item d-flex justify-content-between">
          <span>Delivery Charges</span>
          <span>Rs. <?php echo number_format($delivery_charges, 2); ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <strong>Total</strong>
          <strong>Rs. <?php echo number_format($total_with_delivery, 2); ?></strong>
        </li>
      </ul>

      <button type="submit" name="place_order" class="btn btn-danger w-100">Place Order</button>
    </form>
  </div>
</div>
</body>
</html>
