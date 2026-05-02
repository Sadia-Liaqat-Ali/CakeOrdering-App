<!-- manage-users.php -->
<?php
session_start();
include("../dbconnection.php");
include 'sidebar.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// ===== Delete User =====
if (isset($_POST['delete'])) {
    $userid = intval($_POST['userid']); // ensure integer
    if ($userid > 0) {
        $del_q = "DELETE FROM tbl_users WHERE User_id='$userid'";
        if (mysqli_query($conn, $del_q)) {
            $message = "✅ User deleted successfully!";
        } else {
            $message = "❌ Failed to delete user: " . mysqli_error($conn);
        }
    } else {
        $message = "❌ Invalid User ID.";
    }
}

// ===== Fetch all users or search =====
$users = [];
if (isset($_POST['search'])) {
    $search_id = intval($_POST['userid']); // ensure integer
    $result = mysqli_query($conn, "SELECT * FROM tbl_users WHERE User_id='$search_id'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM tbl_users ORDER BY User_id DESC");
}

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users - Cake Heaven Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background-color: #FFF5E4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; display: flex; }
    .main-content { margin-left: 260px; padding: 30px; flex: 1; background-color: #FFF287; min-height: 100vh; }
    .container-box { background: #fff; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 30px; }
    h3 { color: #8A0000; font-weight: bold; text-align: center; }
    .btn-primary { background-color: #8A0000; border: none; }
    .btn-primary:hover { background-color: #b30000; }
    .btn-danger { background-color: #b30000; border: none; }
    .btn-danger:hover { background-color: #ff0000; }
</style>
</head>
<body>

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-box">
        <h3 class="mb-4"><i class="fas fa-users me-2"></i> Manage Users</h3>

        <form method="POST" class="mb-4 d-flex justify-content-center">
            <input type="number" name="userid" class="form-control w-50 me-2" placeholder="Search by User ID" required>
            <button type="submit" name="search" class="btn btn-primary px-4">
                <i class="fas fa-search me-1"></i> Search
            </button>
        </form>

        <?php if ($message): ?>
            <div class="alert alert-info text-center py-2"><?= $message ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-danger">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['User_id']) ?></td>
                                <td><?= htmlspecialchars($user['UserName']) ?></td>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td><?= htmlspecialchars($user['Contact'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($user['Address'] ?? '-') ?></td>
                                <td>
                                    <a href="update-user.php?id=<?= $user['User_id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="userid" value="<?= $user['User_id'] ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm"
                                            onclick="return confirm('⚠ Delete this user?');">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted">No users found!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
