<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "administrator") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$message = "";

//activate user
if (isset($_GET['activate'])) {
    $id = $_GET['activate'];
    $stmt = mysqli_prepare($conn, "UPDATE users SET Status='Active' WHERE UserID=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $message = "User activated successfully!";
    addAuditLog($conn, $_SESSION['userID'], "Activated User ID ".$id);
}
//deactivate user
if (isset($_GET['deactivate'])) {
    $id = $_GET['deactivate'];
    $stmt = mysqli_prepare($conn, "UPDATE users SET Status = 'Inactive' WHERE UserID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $message = "User deactivated successfully!";
    addAuditLog($conn, $_SESSION['userID'], "Deactivated User ID ".$id);
}
//Load all users
$sql = "SELECT users.*, department.DepartmentName
FROM users LEFT JOIN department
ON users.DepartmentID = department.DepartmentID
ORDER BY UserID DESC";
$users = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Users | MSIR</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="wrapper">
        <aside class="sidebar">
            <div class="logo">
                <img src="../assets/images/lets-go-zero.png" alt="Logo" class="img-fluid">
            </div>

            <ul>
                <li><a href="admin-dashboard.php">Dashboard</a></li>
                <li class="active"><a href="admin-manageUsers.php">Manage Users</a></li>
                <li><a href="admin-departments.php">Departments</a></li>
                <li><a href="admin-incidentTypes.php">Incident Types</a></li>
                <li><a href="admin-auditLogs.php">Audit Logs</a></li>
                <li><a href="admin-reports.php">Reports</a></li>
                <li><a href="../login.php">Logout</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <h2>Manage Users</h2>
            <p>View, activate and deactivate users</p>
            <?php if($message != "") { ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
            <?php } ?>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row =mysqli_fetch_assoc($users)) { ?>
                    <tr>
                        <td><?php echo $row['UserID']; ?></td>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['DepartmentName']; ?></td>
                        <td><?php echo $row['Role']; ?></td>
                        <td>
                            <?php if($row['Status'] == "Active") { ?>
                            <span class="badge bg-success">Active</span>
                            <?php } else { ?>
                            <span class="badge bg-danger">Inactive</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($row['Status']=="Active"){ ?>
                            <a href="?deactivate=<?php echo $row['UserID']; ?>" class="btn btn-warning btn-sm">Deactivate</a>
                            <?php } else { ?>
                            <a href="?activate=<?php echo $row['UserID']; ?>" class="btn btn-success btn-sm">Activate</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>