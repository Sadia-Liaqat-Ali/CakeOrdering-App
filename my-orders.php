<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login-user.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Auto Update: Change "Dispatched" → "Delivered" if time exceeded
$update_auto = "
    UPDATE tbl_orders
    SET status = 'Delivered'
    WHERE status = 'Dispatched'
      AND dispatch_time IS NOT NULL
      AND TIMESTAMPDIFF(MINUTE, dispatch_time, NOW()) >= estimated_minutes
";
mysqli_query($conn, $update_auto);

// ✅ Handle Confirm Order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_order_id'])) {
    $confirm_order_id = $_POST['confirm_order_id'];
    mysqli_query($conn, "UPDATE tbl_orders SET status='Confirmed' WHERE order_id='$confirm_order_id'");
}

// ✅ Handle Voucher Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['voucher_file'])) {
    $order_id = $_POST['order_id'];

    if ($_FILES['voucher_file']['error'] == 0) {
        $file_name = time() . '_' . basename($_FILES['voucher_file']['name']);
        $target = "../uploads/" . $file_name;

        if (!is_dir("../uploads")) mkdir("../uploads", 0777, true);

        move_uploaded_file($_FILES['voucher_file']['tmp_name'], $target);
        $update = "UPDATE tbl_orders SET voucher='$file_name', status='Processing' WHERE order_id='$order_id'";
        mysqli_query($conn, $update);
        $success = "Voucher uploaded successfully!";
    }
}

// ✅ Fetch Orders
$query = "SELECT * FROM tbl_orders WHERE user_id='$user_id' ORDER BY order_date DESC";
$orders = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background: #FFF287; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
.main-content { margin-left: 270px; padding: 20px; }
.orders-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; }
table th { background: #C83F12; color: white; }
.status-badge { padding: 6px 12px; border-radius: 8px; font-weight: 600; color: white; text-transform: capitalize; }
.pending { background: #f0ad4e; }
.processing { background: #0275d8; }
.confirmed { background: #5cb85c; }
.dispatched { background: #5bc0de; }
.rejected { background: #d9534f; }
.delivered { background: #6f42c1; }
.empty-box { text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
</style>
</head>
<body>
<div class="main-content">
  <div class="orders-card">
    <h2 class="text-danger mb-3"><i class="fa fa-box me-2"></i>My Orders</h2>

    <?php if (!empty($success)) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle me-2"></i><?php echo $success; ?></div>
    <?php } ?>

    <?php if (mysqli_num_rows($orders) > 0) { ?>
      <table class="table table-bordered align-middle text-center">
        <thead class="table-danger">
          <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Total Amount (Rs.)</th>
            <th>Status</th>
            <th>Dispatch Time</th>
            <th>Reject Reason</th>
            <th>Download / View</th>
            <th>Upload Voucher</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($o = mysqli_fetch_assoc($orders)) { 
            $show_confirm = ($o['status'] == 'Delivered');
          ?>
            <tr>
              <td><?php echo $o['order_id']; ?></td>
              <td><?php echo date('d M, Y h:i A', strtotime($o['order_date'])); ?></td>
              <td><?php echo number_format($o['total_price'], 2); ?></td>
              <td><span class="status-badge <?php echo strtolower($o['status']); ?>"><?php echo $o['status']; ?></span></td>
              <td><?php echo $o['dispatch_time'] ? date('d M, Y h:i A', strtotime($o['dispatch_time'])) : '-'; ?></td>
              <td><?php echo ($o['status']=='Rejected') ? $o['reject_reason'] : '-'; ?></td>
              <td>
                <a href="track-order.php?id=<?php echo $o['order_id']; ?>" class="btn btn-sm btn-outline-danger">
                  <i class="fa fa-file-pdf me-1"></i> View / Download
                </a>
              </td>
              <td>
                <?php if ($o['status']=='Pending' || empty($o['voucher'])) { ?>
                  <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#uploadModal" 
                    onclick="setOrderId('<?php echo $o['order_id']; ?>')">
                    <i class="fa fa-upload me-1"></i> Upload Voucher
                  </button>
                <?php } else { ?>
                  <a href="../uploads/<?php echo $o['voucher']; ?>" target="_blank" class="text-success fw-bold text-decoration-none">
                    <i class="fa fa-eye me-1"></i> View Voucher
                  </a>
                <?php } ?>
              </td>
              <td>
                <?php if ($show_confirm) { ?>
                  <form method="POST" style="margin:0;">
                    <input type="hidden" name="confirm_order_id" value="<?php echo $o['order_id']; ?>">
                    <button class="btn btn-sm btn-success"><i class="fa fa-check me-1"></i> Confirm Order</button>
                  </form>
                <?php } else { ?>
                  -
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <div class="empty-box">
        <h5 class="text-muted"><i class="fa fa-inbox fa-2x mb-2 text-danger"></i><br>No orders found yet.</h5>
        <a href="view-cakes.php" class="btn btn-danger mt-3"><i class="fa fa-shopping-basket me-1"></i> Continue Shopping</a>
      </div>
    <?php } ?>
  </div>
</div>

<!-- Upload Voucher Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="fa fa-upload me-2"></i>Upload Payment Voucher</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="modal_order_id">
          <div class="mb-3">
            <label class="form-label">Select Voucher (PNG / JPG)</label>
            <input type="file" name="voucher_file" accept=".png,.jpg,.jpeg" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger"><i class="fa fa-paper-plane me-1"></i> Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setOrderId(id) {
    document.getElementById('modal_order_id').value = id;
}
</script>
</body>
</html>
