<?php
require_once "includes/db_connection.php";
require_once "includes/audit.php";
$message = "";

if (isset($_POST['signup'])) {
    $employeeNumber = $_POST['employeeNumber'];
    $fullName = $_POST['fullName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phoneNumber'];
    $department = $_POST['department'];

    $role = "Worker";
    $status = "Active";
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users
            (EmployeeNumber, FullName, Username, Password, Role, Email, PhoneNumber, Status, DepartmentID)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $employeeNumber,
        $fullName,
        $username,
        $hashedPassword,
        $role,
        $email,
        $phoneNumber,
        $status,
        $department
    );
    if (mysqli_stmt_execute($stmt)) {
        $message = "Account created successfully!";
        addAuditLog($conn, $user['UserID'], "Has been added to the system");
    } else {
        $message = "Error: " . mysqli_stmt_error($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | MSIR</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="auth-page">
    <div class="auth-right">

        <!-- Left Side -->
        <div class="auth-left">
            <div class="overlay">
                <div class="welcome-text">
                    <h1>ZERO HARM IS POSSIBLE</h1>
                    <h3>Mining Safety Incident Reporting System</h3>
                    <p>Create your worker account to start reporting workplace incidents quickly and securely.</p>
                </div>
            </div>
        </div>

        <!-- Right Side -->

        <div class="main-content">
            <div class="signup-box shadow">
                <div class="title">
                    <h2>Create Account</h2>
                    <p>All new accounts are registered as Workers.</p>
                </div>

                <form action="" method="POST">
                    <div class="auth-container">
                        <div class="mb-3">
                            <label class="form-label">Employee Number</label>
                            <input type="text" class="form-control" name="employeeNumber" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="fullName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="department" required>
                                <option value="">Select Department</option>
                                <option value="1">MINING</option>
                                <option value="2">PLANT</option>
                                <option value="3">SHE</option>
                                <option value="4">MET SERVICES</option>
                                <option value="5">FINANCE</option>
                                <option value="6">ICT</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phoneNumber" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>       
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirmPassword" required>
                        </div>
                    </div>
                    <button type="submit" name="signup" class="auth-btn">Create Account</button>
                </form>

                <hr>
                <div class="auth-link">
                    <p>Already have an account?<a href="login.php">Login Here</a></p><br>
                    <p></p><a href="index.php"><i class="bi bi-arrow-left-circle"></i>Back to Home</a></p>
                </div>
                <?php if ($message !=""): ?>
                    <div class="message-success">
                        <?php echo $message; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>