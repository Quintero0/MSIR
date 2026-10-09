<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !="worker") {
    header("Location: ../login.php");
    exit;
}
require_once "../includes/db_connection.php";
$userID = $_SESSION['userID'];
$sql = "SELECT users.*, department.DepartmentName
FROM users
INNER JOIN department
ON users.DepartmentID = department.DepartmentID
WHERE users.UserID = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
    <head>
    <title>Worker Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootsrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    </head>
    <body class="dashboard-body">
        <div class="wrapper">
            <!--Main Content-->
            <main class="main-content">
                
            <div class="profile-card">
                
        <div class="profile-card">
            <h2>Worker Profile</h2>
            <div class="profile-header">
                <h3><?php echo $user['FullName']; ?></h3>
                <p>Worker</p>
            </div>
                        <hr>
                <div class="info-row">
                    <span>Employee Number</span>
                    <strong><?php echo $user['EmployeeNumber']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Full Name</span>
                    <strong><?php echo $user['FullName']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Email</span>
                    <strong><?php echo $user['Email']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Phone Number</span>
                    <strong><?php echo $user['PhoneNumber']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Department</span>
                    <strong><?php echo $user['DepartmentName']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Role</span>
                    <strong><?php echo $user['Role']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Status</span>
                            <span class="status-active"><?php echo $user['Status']; ?></span>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-secondary" disabled>Contact your ICT TEAM to edit your profile!</button>
                        <a href="worker-dashboard.php" class="button-success">Return to Dashboard</a>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </body>
</html>