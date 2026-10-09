<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "worker") {
    header("Location: ../login.php");
    exit();
}
include("../includes/db_connection.php");
$username = $_SESSION['username'];

/*get the logged-in worker's UserID */
$userSql = "SELECT UserID, FullName FROM users WHERE Username = ?";
$userStmt = mysqli_prepare($conn, $userSql);
mysqli_stmt_bind_param($userStmt, "s", $username);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);
$user = mysqli_fetch_assoc($userResult);

if(!$user) {
    die("User not found.");
}
$userID = $user['UserID'];
$fullName = $user['FullName'];

/*Total reports*/
$totalSql = "SELECT COUNT(*) AS total FROM incidents WHERE UserID = ?";
$totalStmt = mysqli_prepare($conn, $totalSql);
mysqli_stmt_bind_param($totalStmt, "i", $userID);
mysqli_stmt_execute($totalStmt);
$totalResult = mysqli_stmt_get_result($totalStmt);
$totalData = mysqli_fetch_assoc($totalResult);
$totalReports = $totalData['total'];

/*pending reports*/ 
$pendingSql = "SELECT COUNT(*) AS total FROM incidents WHERE UserID = ? AND Status = 'Pending'";
$pendingStmt = mysqli_prepare($conn, $pendingSql);
mysqli_stmt_bind_param($pendingStmt, "i", $userID);
mysqli_stmt_execute($pendingStmt);
$pendingResult = mysqli_stmt_get_result($pendingStmt);
$pendingData = mysqli_fetch_assoc($pendingResult);
$pendingReports = $pendingData['total'];

/*Under investigation*/
$investigationSql = "SELECT COUNT(*) AS total FROM incidents WHERE UserID = ? 
AND Status = 'Under Investigation'";
$investigationStmt = mysqli_prepare($conn, $investigationSql);
mysqli_stmt_bind_param($investigationStmt, "i", $userID);
mysqli_stmt_execute($investigationStmt);
$investigationResult = mysqli_stmt_get_result($investigationStmt);
$investigationData = mysqli_fetch_assoc($investigationResult);
$investigationReports = $investigationData['total'];

/*resolved reports*/
$resolvedSql = "SELECT COUNT(*) AS total FROM incidents WHERE UserID = ? AND Status = 'Resolved'";
$resolvedStmt = mysqli_prepare($conn, $resolvedSql);
mysqli_stmt_bind_param($resolvedStmt, "i", $userID);
mysqli_stmt_execute($resolvedStmt);
$resolvedResult = mysqli_stmt_get_result($resolvedStmt);
$resolvedData = mysqli_fetch_assoc($resolvedResult);
$resolvedReports = $resolvedData['total'];

/*Recent Reports*/
$recentSql = "SELECT IncidentID, Title, Severity, DateReported, Status FROM incidents
WHERE UserID = ? ORDER BY DateReported DESC LIMIT 5";
$recentStmt = mysqli_prepare($conn, $recentSql);
mysqli_stmt_bind_param($recentStmt, "i", $userID);
mysqli_stmt_execute($recentStmt);
$recentResult = mysqli_stmt_get_result($recentStmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard | MSIR</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
            <li class="active"><a href="worker-dashboard.php">Dashboard</a></li>
            <li><a href="worker-reportIncident.php">Report Incident</a></li>
            <li><a href="worker-myReports.php">My Reports</a></li>
            <li><a href="worker-notifications.php">Notifications</a></li>
            <li><a href="worker-profile.php">Profile</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>

    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="topbar">
            <div>
                <h2> <?php echo $user['FullName']; ?></h2>
                <p>Welcome to the Mining Safety Incident Reporting System</p>
            </div>
            <div class="row mb-4">
                <div class="card text-center shadow p-3 width-30%">
                    <h3>Total Reports</h3>
                    <p><?php echo $totalReports; ?></p>
                    <h3>Pending Reports</h3>
                    <p><?php echo $pendingReports; ?></p>
                    <h3>Under Investigation</h3>
                    <p> <?php echo $investigationReports; ?></p>
                    <h3>Resolved</h3>
                    <p> <?php echo $resolvedReports ?></p>
                </div>
            </div>
            <div class="recent_reports">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Incident ID</th>
                            <th>Title</th>
                            <th>Severity</th>
                            <th>Date Reported</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recentResult) > 0): ?>
                            <?php while($incident = mysqli_fetch_assoc($recentResult)): ?>
                                <tr>
                                    <td>
                                        <?php echo $incident['IncidentID']; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($incident['Title']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($incident['Severity']); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($incident['DateReported']); ?>
                                    </td>
                                    <td>
                                        <?php if($incident['Status']=="Open"){ ?>
                                        <span class="badge bg-success">Open</span>
                                        <?php } elseif($incident['Status']=="Under Investigation"){ ?>
                                        <span class="badge bg-warning text-danger text-dark">Under Investigation</span>
                                        <?php } elseif($incident['Status']=="Resolved"){ ?>
                                        <span class="badge bg-primary">Resolved</span>
                                        <?php } else { ?>
                                        <span class="badge bg-dark">Closed</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No incidents have been reported yet!</td>
                            </tr>
                            <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </main>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>
</html>