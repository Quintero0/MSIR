<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "worker") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
$userID = $_SESSION['userID'];
$sql = "SELECT * FROM notifications
WHERE UserID = ?
ORDER BY DateSent DESC";
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

    <title>Notifications | MSIR</title>

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
            <li><a href="worker-dashboard.php">Dashboard</a></li>
            <li><a href="worker-reportIncident.php">Report Incident</a></li>
            <li><a href="worker-myReports.php">My Reports</a></li>
            <li class="active"><a href="worker-notifications.php">Notifications</a></li>
            <li><a href="worker-profile.php">Profile</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->

    <main class="main-content">
        <h2 class="mb-4">My Notifications</h2>
        <?php if(mysqli_num_rows($result) == 0) { ?>
        <div class="aler alert-info">
            You have no notifications yet
        </div>
        <?php } ?>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="card p-3 mb-3 shadow-sm">
            <div class="d-flex justify-content-between">
                <strong><?php echo $row['Message']; ?></strong>
                <?php if($row['Status'] == "Unread") { ?>
                <span class="badge bg-danger">Unread</span>
                <?php } else { ?>
                <span class="badge bg-success">Read</span>
                <?php } ?>
            </div>
            <small class="text-muted mt2"><?php echo date("d M Y:i", strtotime($row['DateSent'])); ?></small>
        </div>
        <?php } ?>
    </main>
</div>
</body>
</html>