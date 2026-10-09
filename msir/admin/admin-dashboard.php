<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "administrator") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
//dashboard statistics
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) AS total FROM users"))['total'];
$totalIncidents = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) AS total FROM incidents"))['total'];
$openIncidents = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM incidents WHERE Status = 'Open'"))['total'];
$resolvedIncidents = mysqli_fetch_assoc(mysqli_query($conn, 
"SELECT COUNT(*) AS total FROM incidents WHERE Status='Resolved'"))['total'];
//Recent Incidents
$recent = mysqli_query($conn, 
"SELECT incidents.*, users.FullName
FROM incidents
INNER JOIN users
ON incidents.UserID = users.UserID
ORDER BY DateReported DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrator Dashboard | MSIR</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <li class="active"><a href="admin-dashboard.php">Dashboard</a></li>
        <li><a href="admin-manageUsers.php">Manage Users</a></li>
        <li><a href="admin-departments.php">Departments</a></li>
        <li><a href="admin-incidentTypes.php">Incident Types</a></li>
        <li><a href="admin-auditLogs.php">Audit Logs</a></li>
        <li><a href="admin-reports.php">Reports</a></li>
        <li><a href="../login.php">Logout</a></li>
    </ul>
</aside>

<!-- Main Content -->
<main class="main-content"> 
    <h2 class="mb-4">Administrator</h2>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow p-3">
                <h5>Total Users</h5>
                <h2><?php echo $totalUsers; ?></h2>
            </div>
        </div>
        <div class="col md-3">
            <div class="card text-center shadow p-3">
                <h5>Total Incidents</h5>
                <h2><?php echo $totalIncidents; ?></h2>
            </div>
            <div class="card text-center shadow p-3">
                <h5>Open Cases</h5>
                <h2><?php echo $openIncidents; ?></h2>
            </div>
            <div class="card text-center shadow p-3">
                <h5>Resolved</h5>
                <h2><?php echo $resolvedIncidents; ?></h2>
            </div>
        </div>
    </div>
    <div class="card p-3 shadow">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Worker</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($recent)) { ?>
                <tr>
                    <td>INC00<?php echo $row['IncidentID'] ?></td>
                    <td><?php echo $row['FullName'] ?></td>
                    <td><?php echo $row['Title'] ?></td>
                    <td>
                        <?php if($row['Status']=="Open"){ ?>
                        <span class="badge bg-success">Open</span>
                        <?php } elseif($row['Status']=="Under Investigation"){ ?>
                        <span class="badge bg-warning text-danger text-dark">Under Investigation</span>
                        <?php } elseif($row['Status']=="Resolved"){ ?>
                        <span class="badge bg-primary">Resolved</span>
                        <?php } else { ?>
                        <span class="badge bg-dark">Closed</span>
                        <?php } ?>
                    </td>
                    <td><?php echo date("d M Y", strtotime($row['DateReported'])); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>