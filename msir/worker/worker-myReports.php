<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !="worker") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$userID = $_SESSION['userID'];
$sql = "SELECT incidents.*, incidenttypes.TypeName
FROM incidents
INNER JOIN incidenttypes
ON incidents.TypeID = incidenttypes.TypeID
WHERE incidents.UserID = ?
ORDER BY DateReported DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Reports | MSIR</title>

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
            <li><a href="worker-dashboard.php">Dashboard</a></li>
            <li><a href="worker-reportIncident.php">Report Incident</a></li>
            <li class="active"><a href="worker-myReports.php">My Reports</a></li>
            <li><a href="worker-notifications.php">Notifications</a></li>
            <li><a href="worker-profile.php">Profile</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h2 class="mb-4">My Submitted Reports</h2>
            <p> View the progress and status of all incidents you have reported.</p>
        </div>

        <!-- Search -->
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Search Report...">
            </div>
        </div>

        <!-- Reports Table -->
        <div class="card p-3 shadow">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['Title']; ?></td>
                        <td><?php echo $row['TypeName']; ?></td>
                        <td><?php echo $row['Severity']; ?></td>
                        <td>
                            <?php
                            if($row['Status']=="Open"){
                                echo "<span class='badge bg-success'>Open</span>";
                            }
                            elseif($row['Status']=="Under Investigation"){
                                echo "<span class='badge bg-warning text-dark'>Investigating</span>";
                            }
                            elseif($row['Status']=="Resolved"){
                                echo "<span class='badge bg-primary'>Resolved</span>";
                            }
                            else{
                                echo "<span class='badge bg-dark'>Closed</span>";
                            }
                             ?>
                        </td>
                        <td><?php echo date("d M Y", strtotime($row['DateReported'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>