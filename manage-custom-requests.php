<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: admin_login.php");
  exit();
}

// ✅ Update feedback/status
if (isset($_POST['update_status'])) {
  $request_id = $_POST['request_id'];
  $status = $_POST['status'];
  $feedback = $_POST['admin_feedback'];
  mysqli_query($conn, "UPDATE tbl_custom_requests SET status='$status', admin_feedback='$feedback' WHERE request_id='$request_id'");
  echo "<script>alert('✅ Feedback updated successfully!');</script>";
}

// ✅ Fetch requests
$requests = mysqli_query($conn, "SELECT r.*, u.UserName FROM tbl_custom_requests r JOIN tbl_users u ON r.user_id=u.User_id ORDER BY r.request_date DESC");
$total_requests = mysqli_num_rows($requests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Custom Cake Requests</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#FFF287; font-family:'Segoe UI'; }
.main-content { margin-left:270px; padding:20px; }
.card { border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); margin-bottom:20px; }
h3 { color:#C83F12; text-align:center; margin-bottom:25px; }
.no-req {
  background:#fff; padding:40px; border-radius:10px; 
  text-align:center; color:#999; font-size:18px;
  box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
.no-req img { width:120px; opacity:0.7; margin-bottom:15px; }
</style>
</head>
<body>
<div class="main-content">
  <h3>🎂 Manage Custom Cake Requests</h3>

  <?php if ($total_requests > 0) { ?>
    <?php while ($row = mysqli_fetch_assoc($requests)) { ?>
    <div class="card p-3">
      <h5><b><?= htmlspecialchars($row['cake_title']); ?></b> (by <?= htmlspecialchars($row['UserName']); ?>)</h5>
      <p><b>Flavor:</b> <?= $row['flavor']; ?> | 
         <b>Occasion:</b> <?= $row['occasion']; ?> | 
         <b>Delivery:</b> <?= $row['delivery_date']; ?></p>

      <p><b>Description:</b> <?= nl2br(htmlspecialchars($row['description'])); ?></p>
      <p><b>Ingredients:</b> <?= htmlspecialchars($row['ingredients']); ?></p>

      <?php if ($row['image']): ?>
        <p><b>Design Sample:</b> 
          <a href="../uploads/custom_designs/<?= $row['image']; ?>" target="_blank">View Image</a>
        </p>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="request_id" value="<?= $row['request_id']; ?>">
        <div class="row">
          <div class="col-md-3">
            <select name="status" class="form-select">
              <option <?= ($row['status']=='Pending')?'selected':''; ?>>Pending</option>
              <option <?= ($row['status']=='Reviewed')?'selected':''; ?>>Reviewed</option>
              <option <?= ($row['status']=='Accepted')?'selected':''; ?>>Accepted</option>
              <option <?= ($row['status']=='Modified')?'selected':''; ?>>Modified</option>
              <option <?= ($row['status']=='Rejected')?'selected':''; ?>>Rejected</option>
            </select>
          </div>
          <div class="col-md-7">
            <textarea name="admin_feedback" class="form-control" placeholder="Write feedback or suggestions"><?= htmlspecialchars($row['admin_feedback']); ?></textarea>
          </div>
          <div class="col-md-2">
            <button type="submit" name="update_status" class="btn btn-danger w-100">Update</button>
          </div>
        </div>
      </form>
    </div>
    <?php } ?>
  <?php } else { ?>
    <div class="no-req">
      <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Requests">
      <p><b>No Custom Cake Requests Yet!</b></p>
      <p>Once customers submit their special cake designs or ideas, they will appear here for review.</p>
    </div>
  <?php } ?>

</div>
</body>
</html>
