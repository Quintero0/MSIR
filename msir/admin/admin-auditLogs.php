<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "administrator") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$sql = "SELECT auditlogs.*, users.FullName
FROM auditlogs
LEFT JOIN users
ON auditlogs.UserID = users.UserID
ORDER BY DateCreated DESC";
$logs = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs | MSIR</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
<div class="wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <img src="../assets/images/lets-go-zero.png" alt="Logo" class="img-fluid">
        </div>
            <ul>
                <li><a href="admin-dashboard.php">Dashboard</a></li>
                <li><a href="admin-manageUsers.php">Manage Users</a></li>
                <li><a href="admin-departments.php">Departments</a></li>
                <li><a href="admin-incidentTypes.php">Incident Types</a></li>
                <li class="active"><a href="admin-auditLogs.php">Audit Logs</a></li>
                <li><a href="admin-reports.php">Reports</a></li>
                <li><a href="../login.php">Logout</a></li>
            </ul>
    </aside>

    <!-- Main Content -->
     <main class="main-content">
        <h2 class="mb-4">Audit Logs</h2>
        <div class="card p-3 shadow">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>LOG ID</th>
                        <th>User</th>
                        <th>Activity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row=mysqli_fetch_assoc($logs)){ ?>
                    <tr>
                        <td><?php echo $row['LogID']; ?></td>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['Activity']; ?></td>
                        <td><?php echo date("d M Y H:i", strtotime($row['DateCreated'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
     </main>
</div>
</body>
</html>