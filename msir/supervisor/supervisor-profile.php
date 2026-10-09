<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != "supervisor") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
$userID = $_SESSION['userID'];
$sql = "SELECT users.*, department.DepartmentName
FROM users
INNER JOIN department ON users.DepartmentID = department.DepartmentID
WHERE users.UserID = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Profile | MSIR</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
<div class="wrapper">

    <main class="main-content">
        <div class="profile-card">
            <h2>Supervisor Profile</h2>
            <div class="profile-header">
                <h3><?php echo $user['FullName']; ?></h3>
                <p>Supervisor</p>
            </div>
            <hr>
            <div class="profile-info">
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
                    <button class="btn btn-secondary" disabled>Managed by your Administrator</button>
                    <a href="supervisor-dashboard.php" class="button-success">Return to Dashboard</a>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>