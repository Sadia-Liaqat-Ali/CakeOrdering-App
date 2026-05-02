<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php'; // ✅ sidebar for consistent layout

if (!isset($_SESSION['user_id'])) {
  header("Location: login-user.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'] ?? 0;

// ✅ Fetch order details
$order_q = "SELECT * FROM tbl_orders WHERE order_id='$order_id' AND user_id='$user_id'";
$order = mysqli_fetch_assoc(mysqli_query($conn, $order_q));

if (!$order) {
  echo "<script>alert('Order not found!');window.location.href='my-orders.php';</script>";
  exit;
}

// ✅ Fetch ordered items
$items_q = "SELECT c.cakeName, i.quantity, i.price 
            FROM tbl_order_items i 
            JOIN tbl_cakes c ON i.cake_id = c.cakeId 
            WHERE i.order_id='$order_id'";
$items = mysqli_query($conn, $items_q);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Track Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #FFF287; font-family: 'Segoe UI'; }
.main-content { margin-left: 270px; padding: 20px; }
.voucher-box {
  background: white; border-radius: 10px; padding: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.status-bar {
  display: flex; justify-content: space-between; margin: 30px 0;
  position: relative; counter-reset: step;
}
.status-step {
  text-align: center; flex: 1; position: relative;
}
.status-step::before {
  counter-increment: step; content: counter(step);
  width: 40px; height: 40px; line-height: 40px; display: block;
  background: #ddd; color: white; border-radius: 50%;
  margin: 0 auto 10px; font-weight: bold;
}
.status-step.active::before { background: #C83F12; }
.status-step::after {
  content: ''; position: absolute; width: 100%; height: 4px;
  background: #ddd; top: 18px; left: -50%; z-index: -1;
}
.status-step:first-child::after { content: none; }
.status-step.active + .status-step::after { background: #C83F12; }
.table th { background: #C83F12; color: white; text-align: center; }
.table td { text-align: center; vertical-align: middle; }
.info-section h5 { color: #C83F12; border-bottom: 2px solid #C83F12; padding-bottom: 5px; margin-top: 20px; }
.payment-box {
  background: #fff3e6; border-left: 5px solid #C83F12;
  padding: 15px; border-radius: 8px; font-size: 15px;
}
</style>
</head>
<body>
<div class="main-content">
  <div class="voucher-box">
    <h2 class="text-danger mb-4"><i class="fa fa-receipt me-2"></i>Order Voucher & Tracking</h2>

    <!-- ✅ Customer Information -->
    <div class="info-section">
      <h5><i class="fa fa-user me-2"></i>Customer Information</h5>
      <p><strong>Name:</strong> <?php echo $order['customer_name']; ?></p>
      <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
    </div>

    <!-- ✅ Order Information -->
    <div class="info-section">
      <h5><i class="fa fa-box me-2"></i>Order Information</h5>
      <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
      <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
      <p><strong>Current Status:</strong> <span class="badge bg-danger"><?php echo ucfirst($order['status']); ?></span></p>
    </div>

    <!-- ✅ Delivery Address -->
    <div class="info-section">
      <h5><i class="fa fa-map-marker-alt me-2"></i>Delivery Address</h5>
      <p><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
    </div>

    <!-- ✅ Ordered Items -->
    <table class="table table-bordered mt-3">
      <thead>
        <tr><th>Cake</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        <?php 
        $total = 0;
        while ($i = mysqli_fetch_assoc($items)) { 
          $sub = $i['price'] * $i['quantity']; 
          $total += $sub; ?>
          <tr>
            <td><?php echo htmlspecialchars($i['cakeName']); ?></td>
            <td><?php echo $i['quantity']; ?></td>
            <td>Rs. <?php echo number_format($i['price'], 2); ?></td>
            <td>Rs. <?php echo number_format($sub, 2); ?></td>
          </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr><th colspan="3" class="text-end">Delivery Charges</th><th>Rs. 300.00</th></tr>
        <tr><th colspan="3" class="text-end">Grand Total</th><th>Rs. <?php echo number_format($total + 300, 2); ?></th></tr>
      </tfoot>
    </table>

    <!-- ✅ Status Tracking Bar -->
    <div class="status-bar">
      <?php
      $statuses = ["pending", "processing", "confirmed", "dispatched", "delivered"];
      $current = array_search($order['status'], $statuses);
      foreach ($statuses as $i => $status) {
        $active = ($i <= $current) ? 'active' : '';
        echo "<div class='status-step $active'><span>".ucfirst($status)."</span></div>";
      }
      ?>
    </div>

    <!-- ✅ Payment Instructions -->
    <div class="info-section">
      <h5><i class="fa fa-credit-card me-2"></i>Payment Instructions</h5>
      <div class="payment-box">
        <p>💳 Please complete your payment using any of the following methods:</p>
        <ul>
          <li><strong>Easypaisa Account:</strong> 0345-1234567 (Cake Ordering Pvt. Ltd.)</li>
          <li><strong>Bank Transfer:</strong> Meezan Bank - A/C # 0123-456789-01</li>
          <li><strong>JazzCash:</strong> 0301-9876543</li>
        </ul>
        <p>📩 After payment, please upload your voucher below to confirm your order.</p>
        <p>⏱ Orders will remain <strong>PENDING</strong> until payment proof is uploaded and verified by admin.</p>
        <p>📞 For assistance, email us at <strong>support@cakeorder.com</strong></p>
      </div>
    </div>

    <!-- ✅ Download / Print Option -->
    <div class="mt-4 text-end">
      <a href='my-orders.php' class="btn btn-primary">Track All Orders</a>
      <button onclick="window.print()" class="btn btn-secondary"><i class="fa fa-print me-1"></i> Download / Print Voucher</button>
    </div>
  </div>
</div>
</body>
</html>
