<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role']!= "supervisor"){
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$sql = "SELECT incidents.IncidentID, users.FullName, department.DepartmentName, incidents.Severity, incidents.Status
FROM incidents
INNER JOIN users ON incidents.UserID = users.UserID
INNER JOIN department ON users.DepartmentID = department.DepartmentID
ORDER BY incidents.IncidentID DESC";
$reports = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Manage Reports | MSIR</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

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
            <li><a href="supervisor-dashboard.php">Dashboard</a></li>
            <li class="active"><a href="supervisor-manageReports.php">Manage Reports</a></li>
            <li><a href="supervisor-riskAssessment.php">Risk Assessment</a></li>
            <li><a href="supervisor-profile.php">Profile</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>

    </aside>

    <!-- Main Content -->

    <main class="main-content">
         <div class="card p-3 shadow">
            <table class="table table-bordered table-hover">
                <thead class="table-success">
                    <tr>
                        <th>Incident ID</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($reports)){ ?>
                    <tr>
                        <td><?php echo $row['IncidentID']; ?></td>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['DepartmentName']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td>
                            <a href="view-incident.php?id=<?php echo $row['IncidentID']; ?>"
                            class="auth-link">Review</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</div>

</body>

</html>