<?php
session_start();
include("../dbconnection.php");
include 'sidebar.php'; // ✅ Include external sidebar

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


$message = "";

// ---- Delete Cake ----
if (isset($_POST['delete'])) {
    $cakeid = $_POST['cakeid'];
    $cakepicture = $_POST['cakepicture'];

    $stmt = $conn->prepare("DELETE FROM tbl_cakes WHERE cakeId=?");
    $stmt->bind_param("i", $cakeid);
    if ($stmt->execute()) {
        $path = "../uploads/" . $cakepicture;
        if (file_exists($path)) unlink($path);
        $message = "✅ Cake deleted successfully!";
    } else {
        $message = "❌ Failed to delete cake.";
    }
    $stmt->close();
}

// ---- Fetch all cakes or search ----
$cakes = [];
if (isset($_POST['search'])) {
    $search_id = trim($_POST['cakeid']);
    $stmt = $conn->prepare("SELECT c.*, cat.category_name 
                            FROM tbl_cakes c 
                            LEFT JOIN tbl_categories cat 
                            ON c.category_id = cat.id
                            WHERE c.cakeId=?");
    $stmt->bind_param("i", $search_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cakes[] = $row;
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT c.*, cat.category_name 
                            FROM tbl_cakes c 
                            LEFT JOIN tbl_categories cat 
                            ON c.category_id = cat.id
                            ORDER BY c.cakeId DESC");
    while ($row = $result->fetch_assoc()) {
        $cakes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Cakes - Cake Heaven Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        background-color: #FFF287;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        display: flex;
    }
    .main-content {
        margin-left: 260px;
        padding: 30px;
        flex: 1;
        background-color: #FFF287;
        min-height: 100vh;
    }
    .container-box {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 30px;
    }
    h3 {
        color: #8A0000;
        font-weight: bold;
        text-align: center;
    }
    .btn-primary-custom {
      background: linear-gradient(135deg, #C83F12, #8A0000);
      border: none;
      color: white;
      font-weight: 600;
    }
    .btn-primary-custom:hover {
      background: linear-gradient(135deg, #8A0000, #C83F12);
      transform: translateY(-2px);
    }
    .btn-primary { background-color: #8A0000; border: none; }
    .btn-primary:hover { background-color: #b30000; }
    .btn-danger { background-color: #b30000; border: none; }
    .btn-danger:hover { background-color: #ff0000; }
    .table img { border-radius: 10px; }
</style>
</head>
<body>

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-box">
        <h3 class="mb-4"><i class="fas fa-birthday-cake me-2"></i> Manage Cakes</h3>

        <form method="POST" class="mb-4 d-flex justify-content-center">
            <input type="number" name="cakeid" class="form-control w-50 me-2" placeholder="Search by Cake ID" required>
            <button type="submit" name="search" class="btn btn-primary px-4">
                <i class="fas fa-search me-1"></i> Search
            </button>
             <b><a style="margin-left: 20px;" class="btn btn-primary-custom btn-lg w-30 text-light" href="add-cake.php"><i class="fas fa-plus-circle me-1"></i>Add New Cake</a></b>

        </form>

        <?php if ($message): ?>
            <div class="alert alert-info text-center py-2"><?= $message ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-danger">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price (Rs)</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Picture</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($cakes) > 0): ?>
                        <?php foreach ($cakes as $cake): ?>
                            <tr>
                                <td><?= htmlspecialchars($cake['cakeId']) ?></td>
                                <td><?= htmlspecialchars($cake['cakeName']) ?></td>
                                <td><?= htmlspecialchars($cake['cakePrice']) ?></td>
                                <td><?= htmlspecialchars($cake['category_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($cake['description'] ?? '-') ?></td>
                                <td><img src="../uploads/<?= htmlspecialchars($cake['cakePicture']) ?>" width="80"></td>
                                <td>
                                    <a href="update-cake.php?id=<?= $cake['cakeId'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="cakeid" value="<?= $cake['cakeId'] ?>">
                                        <input type="hidden" name="cakepicture" value="<?= $cake['cakePicture'] ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm"
                                            onclick="return confirm('⚠ Delete this cake?');">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted">No cakes found!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
