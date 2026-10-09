<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "supervisor") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$sql = "SELECT incidents.*, users.FullName, incidenttypes.TypeName
FROM incidents
INNER JOIN users ON incidents.UserID = users.userID
INNER JOIN incidenttypes ON incidents.TypeID = incidenttypes.TypeID
ORDER BY DateReported DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Supervisor Dashboard | MSIR</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Custom CSS -->
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
            <li class="active"><a href="supervisor-dashboard.php">Dashboard</a></li>
            <li><a href="supervisor-manageReports.php">Manage Reports</a></li>
            <li><a href="supervisor-riskAssessment.php">Risk Assessment</a></li>
            <li><a href="supervisor-profile.php">Profile</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h2 class="mb-4">Reported Incidents</h2>
            <p>Monitor and manage all reported mining safety incidents.</p>
        </div>

        <div class="card p-3 shadow">
            <table class="table table-bordered table-hover">
                <thead class="table-success">
                    <tr>
                        <th>Worker</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?php echo $row['FullName']; ?></td>
                        <td><?php echo $row['Title']; ?></td>
                        <td><?php echo $row['TypeName']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                        <td><?php echo date('d M Y', strtotime($row['DateReported'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>

</html>