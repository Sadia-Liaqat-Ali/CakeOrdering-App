<?php
session_start();
include '../dbconnection.php';
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// ---------------- CALCULATE REPORTS ----------------
$total_income_q = mysqli_query($conn, "SELECT SUM(total_price) AS total_income FROM tbl_orders WHERE status='Dispatched' OR status='confirmed' OR status='Delivered' ");
$total_income = 0;
if ($total_income_q && $row = mysqli_fetch_assoc($total_income_q)) {
    $total_income = $row['total_income'] ?? 0;
}

$total_expense_q = mysqli_query($conn, "SELECT SUM(amount) AS total_expense FROM tbl_expenses");
$total_expense = 0;
if ($total_expense_q && $row = mysqli_fetch_assoc($total_expense_q)) {
    $total_expense = $row['total_expense'] ?? 0;
}

$profit = $total_income - $total_expense;

$total_orders_q = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM tbl_orders");
$total_orders = 0;
if ($total_orders_q && $row = mysqli_fetch_assoc($total_orders_q)) {
    $total_orders = $row['total_orders'] ?? 0;
}

// ---------------- FETCH EXPENSE LIST ----------------
$expenses = mysqli_query($conn, "SELECT * FROM tbl_expenses ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📊 Financial Report - Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
  background: #FFF287;
  font-family: 'Segoe UI', sans-serif;
  margin: 0;
  padding: 0;
}
.main-content {
  margin-left: 270px;
  padding: 40px;
  min-height: 100vh;
}
h3 {
  color: #C83F12;
  font-weight: bold;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: transform 0.2s ease-in-out;
}
.card:hover {
  transform: translateY(-5px);
}
.summary-card h5 {
  color: #C83F12;
  font-weight: 600;
}
.table thead {
  background-color: #C83F12;
  color: white;
}
.chart-container {
  background: #fff;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.btn-primary {
  background-color: #C83F12;
  border: none;
}
.btn-primary:hover {
  background-color: #a32f0f;
}
</style>
</head>
<body>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-5">
    <h3>📈 Financial Report Overview</h3>
    <div>
      <a href="dashboard.php" class="btn btn-primary me-2">➕ Add Expense</a>
      <button onclick="window.print()" class="btn btn-secondary">🖨️ Print / Download</button>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row text-center mb-5">
    <div class="col-md-3">
      <div class="card p-4 summary-card bg-white">
        <h5>Total Orders</h5>
        <h2 class="fw-bold"><?php echo $total_orders; ?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4 summary-card bg-white">
        <h5>Total Income</h5>
        <h2 class="text-success fw-bold">Rs. <?php echo number_format($total_income, 2); ?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4 summary-card bg-white">
        <h5>Total Expenses</h5>
        <h2 class="text-danger fw-bold">Rs. <?php echo number_format($total_expense, 2); ?></h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-4 summary-card bg-white">
        <h5>Profit / Loss</h5>
        <h2 class="fw-bold" style="color:<?php echo ($profit >= 0 ? 'green' : 'red'); ?>">
          Rs. <?php echo number_format($profit, 2); ?>
        </h2>
      </div>
    </div>
  </div>

  <!-- Line Chart -->
  <div class="chart-container mb-5">
    <h5 class="text-danger mb-3 text-center">📊 Income, Expenses & Profit Trend</h5>
    <canvas id="lineChart" height="100"></canvas>
  </div>

  <!-- Expense Details Table -->
  <div class="card p-4">
    <h5 class="text-danger mb-3 text-center">💰 Expense Details</h5>
    <?php if (mysqli_num_rows($expenses) > 0): ?>
      <table class="table table-bordered text-center align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Amount (Rs.)</th>
            <th>Date</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($expenses)) { ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['expense_title']); ?></td>
            <td><?php echo number_format($row['amount'], 2); ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-center text-muted">No expenses recorded yet.</p>
    <?php endif; ?>
  </div>
</div>

<script>
const ctx = document.getElementById('lineChart').getContext('2d');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Income', 'Expenses', 'Profit'],
    datasets: [{
      label: 'Financial Report',
      data: [<?php echo $total_income; ?>, <?php echo $total_expense; ?>, <?php echo $profit; ?>],
      backgroundColor: 'rgba(200,63,18,0.1)',
      borderColor: '#C83F12',
      borderWidth: 2,
      tension: 0.3,
      pointBackgroundColor: '#C83F12',
      fill: true
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: { mode: 'index', intersect: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { color: '#333' }
      },
      x: {
        ticks: { color: '#333' }
      }
    }
  }
});
</script>

</body>
</html>
