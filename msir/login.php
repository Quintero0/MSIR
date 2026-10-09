<?php
session_start();
require_once "includes/db_connection.php";
require_once "includes/audit.php";
if (isset($_POST['login'])) {

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT *  FROM users WHERE Username = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['Password'])) {
        if ($user['Status'] != "Active") {
            echo "<script>
            alert('Your account has been deactivated. Contact your ICT team!');
            </script>";
            exit();
        }
        $_SESSION['username'] = $user['Username'];
        $_SESSION['role'] = strtolower($user['Role']);
        $_SESSION['userID'] = $user['UserID'];
        addAuditLog($conn, $user['UserID'], "Logged into the system");

        if ($_SESSION['role'] == "worker") {
            header("Location: worker/worker-dashboard.php");
            exit;

        }
        elseif ($_SESSION['role'] == "supervisor") {
            header("Location: supervisor/supervisor-dashboard.php");
            exit;
        }
        elseif ($_SESSION['role'] == "administrator") {
            header("Location: admin/admin-dashboard.php");
            exit;
        }
    }
    else {
        echo "<script>
        aler('Incorrect password');
        </script>";
    }
}
else {
    echo"<script>
    alert('User not found.');
    </script>";
}
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MSIR</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="auth-page">
    <!-- Left Side -->
        <!-- Right Side -->
        <div class="auth-right">
            <div class="login-box shadow">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-check login-icon"></i>
                    <h2>Welcome Back</h2>
                    <p>Please login to continue.</p>
                </div>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                   <button type="submit" name="login" class="button-success">Login</button>
                </form>

                <hr>
                <div class="auth-link">
                    <p> Don't have an account?<a href="signup.php"> Register Here</a></p>
                </div>

                <div class="auth-link">
                    <a href="index.php"><i class="bi bi-arrow-left-circle"></i>Back to Home</a>
                </div>
            </div>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>