<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: user_login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// ----- TOTAL AVAILABLE CAKES -----
$cakes_q = mysqli_query($conn, "SELECT COUNT(*) AS total_cakes FROM tbl_cakes");
$total_cakes = mysqli_fetch_assoc($cakes_q)['total_cakes'] ?? 0;

// ----- TOTAL ITEMS IN CART -----
$cart_q = mysqli_query($conn, "SELECT COUNT(*) AS total_cart FROM tbl_cart WHERE user_id='$user_id'");
$total_cart = mysqli_fetch_assoc($cart_q)['total_cart'] ?? 0;

// ----- TOTAL ORDERS -----
$orders_q = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM tbl_orders WHERE user_id='$user_id'");
$total_orders = mysqli_fetch_assoc($orders_q)['total_orders'] ?? 0;

// ----- TOTAL CUSTOM REQUESTS -----
$custom_q = mysqli_query($conn, "SELECT COUNT(*) AS total_requests FROM tbl_custom_requests WHERE user_id='$user_id'");
$total_requests = mysqli_fetch_assoc($custom_q)['total_requests'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { background:#FFF287; font-family:'Segoe UI'; }
.main-content { margin-left:270px; padding:25px; }
.card {
  border-radius:15px;
  box-shadow:0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s ease-in-out;
}
.card:hover { transform: scale(1.03); }
h3 { color:#C83F12; font-weight:bold; margin-bottom:30px; }

/* Dashboard Card Colors */
.bg-categories { background: #C83F12; color: #fff; }  
.bg-customers { background: #FF6F61; color: #fff; }     
.bg-cakes { background: #FFD966; color: #333; }         
.bg-orders { background: #4DB6AC; color: #fff; }        
.bg-requests { background: #9B59B6; color: #fff; }      

.card h5 { font-weight:600; }
.card h3 { margin-top:10px; font-weight:bold; }
</style>
</head>
<body>

<div class="main-content">
  <h3 class="text-center">🎂 Customer Dashboard</h3>

  <!-- Summary Cards -->
  <div class="row text-center mb-5">
    <div class="col-md-3 mb-3">
      <div class="card bg-cakes p-4">
        <h5>Available Cakes</h5>
        <h3><?php echo $total_cakes; ?></h3>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-orders p-4">
        <h5>My Cart Items</h5>
        <h3><?php echo $total_cart; ?></h3>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-categories p-4">
        <h5>Total Orders</h5>
        <h3 class="text-light"><?php echo $total_orders; ?></h3>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="card bg-requests p-4">
        <h5>Custom Cake Requests</h5>
        <h3><?php echo $total_requests; ?></h3>
      </div>
    </div>
  </div>

  <!-- Column Chart Section -->
  <div class="card p-4">
    <h5 class="text-danger mb-3 text-center">📊 Your Activity Overview</h5>
    <canvas id="activityChart" height="100"></canvas>
  </div>
</div>

<script>
const ctx = document.getElementById('activityChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Available Cakes', 'Cart Items', 'Orders', 'Custom Requests'],
        datasets: [{
            label: 'Your Stats',
            data: [
              <?php echo $total_cakes; ?>,
              <?php echo $total_cart; ?>,
              <?php echo $total_orders; ?>,
              <?php echo $total_requests; ?>
            ],
            backgroundColor: ['#FFD966','#4DB6AC','#C83F12','#9B59B6']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
        },
        scales: {
            y: { beginAtZero: true },
            x: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
