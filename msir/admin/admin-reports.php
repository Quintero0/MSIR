<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "administrator") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
//statistics
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM incidents"))['total'];
$open = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM incidents WHERE Status='Open'"))['total'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM incidents WHERE Status='Resolved'"))['total'];
//Report Table
$sql = "SELECT incidents.*, users.FullName, department.DepartmentName, incidenttypes.TypeName
FROM incidents
JOIN users ON incidents.UserID = users.UserID
JOIN department ON users.DepartmentID = department.DepartmentID
JOIN incidenttypes ON incidents.TypeID = incidenttypes.TypeID
ORDER BY DateReported DESC";
$reports = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | MSIR</title>

    <!-- Bootstrap -->
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
            <li><a href="admin-auditLogs.php">Audit Logs</a></li>
            <li class="active"><a href="admin-reports.php">Reports</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>
    </aside>
    
    <!-- Main Content -->
     <main class="main-content">
        <h2 class="mb-4">Incident Reports</h2>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3 text-center shadow">
                    <h5>Total Incidents</h5>
                    <h2><?php echo $total; ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 text-center shadow">
                    <h5>Open Cases</h5>
                    <h2><?php echo $open; ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 text-center shadow">
                    <h5>Resolved</h5>
                    <h2><?php echo $resolved; ?></h2>
                </div>
            </div>
        </div>
        <div class="card p-3 shadow">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Worker</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($reports)) { ?>
                    <tr>
                        <td>INC00<?php echo $row['IncidentID']; ?></td>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['DepartmentName']; ?></td>
                        <td><?php echo $row['TypeName']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td><?php echo date("d M Y",strtotime($row['DateReported'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
     </main>
    </div>

</body>

</html>