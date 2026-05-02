<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login-user.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM tbl_custom_requests WHERE user_id='$user_id' ORDER BY request_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Custom Cake Requests</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body { background: #FFF287; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
.main-content { margin-left: 270px; padding: 20px; }
.requests-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; }
h2 { color: #C83F12; margin-bottom: 20px; }
table th { background: #C83F12; color: white; }
.status-badge { padding: 6px 12px; border-radius: 8px; font-weight: 600; color: white; text-transform: capitalize; }
.pending { background: #f0ad4e; }
.accepted { background: green; }
.modified { background: #5cb85c; }
.rejected { background: red; }
.completed { background: #6f42c1; }
.reviewed { background: #0275d8; }
.empty-box { text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
</style>
</head>

<body>
<div class="main-content">
  <div class="requests-card">
    <h2><i class="fa fa-birthday-cake me-2"></i>My Custom Cake Requests & Admin Feedback</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>
      <table class="table table-bordered align-middle text-center">
        <thead class="table-danger">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Flavor</th>
            <th>Occasion</th>
            <th>Delivery Date</th>
            <th>Status</th>
            <th>Admin Feedback</th>
            <th>Design</th>
            <th>Submitted On</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) { 
            $status = strtolower($row['status']);
          ?>
            <tr>
              <td><?php echo $row['request_id']; ?></td>
              <td><?php echo htmlspecialchars($row['cake_title']); ?></td>
              <td><?php echo htmlspecialchars($row['flavor']); ?></td>
              <td><?php echo htmlspecialchars($row['occasion']); ?></td>
              <td><?php echo $row['delivery_date']; ?></td>
              <td><span class="status-badge <?php echo $status; ?>"><?php echo ucfirst($row['status']); ?></span></td>
              <td>
                <?php 
                  echo (!empty($row['admin_feedback'])) 
                    ? nl2br(htmlspecialchars($row['admin_feedback'])) 
                    : "<i>Awaiting review...</i>"; 
                ?>
              </td>
              <td>
                <?php if ($row['image']) { ?>
                  <a href="../uploads/custom_designs/<?php echo $row['image']; ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-image me-1"></i> View
                  </a>
                <?php } else { echo "<i>None</i>"; } ?>
              </td>
              <td><?php echo date('d M, Y h:i A', strtotime($row['request_date'])); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <div class="empty-box">
        <h5 class="text-muted"><i class="fa fa-inbox fa-2x mb-2 text-danger"></i><br>No custom requests submitted yet.</h5>
        <a href="custom-cake-request.php" class="btn btn-danger mt-3"><i class="fa fa-plus-circle me-1"></i> Submit New Request</a>
      </div>
    <?php } ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
