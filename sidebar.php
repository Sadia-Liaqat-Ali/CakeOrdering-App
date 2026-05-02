<!-- user_sidebar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Online Cake Ordering Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #FFF5E4;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sidebar {
      height: 100vh;
      width: 260px;
background: linear-gradient(135deg, #8A0000, #C83F12);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      padding: 25px 15px;
      box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    }
    .sidebar h3 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 30px;
      font-size: 1.5rem;
      letter-spacing: 1px;
      color: #FFFACD;
    }
    .menu-item {
      list-style: none;
      margin-bottom: 12px;
      border-radius: 10px;
      transition: 0.3s;
    }
    .menu-item a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 15px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .menu-item a i {
      font-size: 18px;
      min-width: 20px;
    }
    .menu-item:hover a {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(5px);
    }
    .logout {
      margin-top: auto;
      border-top: 1px solid rgba(255,255,255,0.3);
      padding-top: 15px;
    }
    .logout a {
      font-weight: bold;
      transition: all 0.3s ease;
    }
    .logout a:hover {
      color: #FF6F61;
      background: #fff;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h3><i class="fas fa-user me-2"></i>User Panel</h3>
    <ul class="p-0">
      <li class="menu-item"><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
      <li class="menu-item"><a href="view-cakes.php"><i class="fas fa-birthday-cake"></i> View Cakes</a></li>
      <li class="menu-item"><a href="my-cart.php"><i class="fas fa-shopping-cart"></i> My Cart</a></li>
      <li class="menu-item"><a href="my-orders.php"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
      <li class="menu-item"><a href="update-profile.php"><i class="fas fa-user-edit"></i> Update Profile</a></li>
      <li class="menu-item"><a href="custom-cake-request.php"><i class="fas fa-paint-brush"></i> Request Custom Design</a></li>
      <li class="menu-item"><a href="custom-requests-status.php"><i class="fas fa-comments"></i> Feedback</a></li>
      <li class="menu-item logout"><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>
</body>
</html>
