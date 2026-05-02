<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

/* =========================
   UPDATE ORDER STATUS
========================= */
if (isset($_POST['update_order'])) {

    $order_id = $_POST['order_id'];
    $action   = $_POST['action'];

    /* ===== REJECT ORDER ===== */
    if ($action == 'Rejected') {

        $reason = trim($_POST['reject_reason']);

        if ($reason == '') {
            echo "<script>alert('Please enter a rejection reason!');</script>";
        } else {

            $query = "
                UPDATE tbl_orders 
                SET 
                    status='Rejected',
                    reject_reason='$reason',
                    action_taken='Rejected'
                WHERE order_id='$order_id'
            ";

            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Order rejected successfully!');window.location='orders.php';</script>";
                exit();
            } else {
                echo "<script>alert('DB Error');</script>";
            }
        }
    }

    /* ===== DISPATCH ORDER ===== */
    if ($action == 'Dispatched') {

        $expected_delivery_time = $_POST['expected_delivery_time'];

        if ($expected_delivery_time == '') {
            echo "<script>alert('Please select expected delivery time!');</script>";
        } else {

            $dispatch_time = date('Y-m-d H:i:s');

            $query = "
                UPDATE tbl_orders 
                SET 
                    status='Dispatched',
                    dispatch_time='$dispatch_time',
                    expected_delivery_time='$expected_delivery_time',
                    action_taken='Dispatched'
                WHERE order_id='$order_id'
            ";

            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Order dispatched successfully!');window.location='orders.php';</script>";
                exit();
            } else {
                echo "<script>alert('DB Error');</script>";
            }
        }
    }
}


/* =========================
   FETCH ORDERS
========================= */
$result = mysqli_query($conn, "SELECT * FROM tbl_orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Orders | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#FFF287; font-family:'Segoe UI'; }
.main-content { margin-left:260px; padding:20px; }
.container-box { background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
.table td { text-align:center; vertical-align:middle; }
textarea { resize:none; }
</style>

<script>
function toggleFields(id){
    var action = document.getElementById("action_"+id).value;
    document.getElementById("rejectBox_"+id).style.display   = (action=="Rejected") ? "block" : "none";
    document.getElementById("dispatchBox_"+id).style.display = (action=="Dispatched") ? "block" : "none";
}
</script>
</head>

<body>
<div class="main-content">
<div class="container-box">

<h3 class="text-danger mb-4">Manage Orders</h3>

<table class="table table-bordered">
<thead class="table-danger">
<tr>
<th>ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Date</th>
<th>Total (PKR)</th>
<th>Status</th>
<th>Voucher</th>
<th>Admin Action</th>
</tr>
</thead>

<tbody>
<?php while($o = mysqli_fetch_assoc($result)){ ?>
<tr>

<td><?= $o['order_id']; ?></td>
<td><?= $o['customer_name']; ?></td>
<td><?= $o['phone']; ?></td>
<td><?= $o['order_date']; ?></td>
<td>PKR <?= number_format($o['total_price'] + 300,2); ?></td>

<td>
<span class="badge 
<?= ($o['status']=='Rejected')?'bg-danger':
   (($o['status']=='Delivered')?'bg-success':
   (($o['status']=='Dispatched')?'bg-primary':'bg-secondary')); ?>">
<?= $o['status']; ?>
</span>
</td>

<td>
<?php
if ($o['voucher'] != '') {
    echo "<a href='../uploads/".$o['voucher']."' target='_blank'>View</a>";
} else {
    echo "Not Uploaded";
}
?>
</td>

<td>
<?php if(in_array($o['status'], ['Pending','Processing','Confirmed'])){ ?>

<form method="post" class="border p-2 rounded">
<input type="hidden" name="order_id" value="<?= $o['order_id']; ?>">

<select name="action" id="action_<?= $o['order_id']; ?>" class="form-select mb-2"
onchange="toggleFields(<?= $o['order_id']; ?>)" required>
<option value="">Select</option>
<option value="Rejected">Reject</option>
<option value="Dispatched">Dispatch</option>
</select>

<div id="rejectBox_<?= $o['order_id']; ?>" style="display:none;">
<textarea name="reject_reason" class="form-control mb-2" placeholder="Reject reason"></textarea>
</div>

<div id="dispatchBox_<?= $o['order_id']; ?>" style="display:none;">
<input type="datetime-local" name="expected_delivery_time" class="form-control mb-2">
</div>

<button name="update_order" class="btn btn-danger btn-sm w-100">Update</button>
</form>

<?php } else { ?>

<?php
if ($o['status']=='Rejected') {
    echo "Rejected<br><small>".$o['reject_reason']."</small>";
} elseif ($o['status']=='Dispatched') {
    echo "Dispatched<br><small>".$o['expected_delivery_time']."</small>";
} elseif ($o['status']=='Delivered') {
    echo "<span class='text-success'>Delivered</span>";
}
?>

<?php } ?>
</td>

</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>
</body>
</html>
