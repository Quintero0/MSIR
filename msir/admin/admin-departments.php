<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "administrator") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$message = "";
//Add department
if(isset($_POST['addDepartment'])) {
    $department = trim($_POST['department']);
    $stmt = mysqli_prepare($conn, "INSERT INTO department (DepartmentName) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $department);
    if (mysqli_stmt_execute($stmt)) {
        $message = "New Department ADDED!!!";
    }
    addAuditLog($conn, $_SESSION['userID'], "Added department ".$department);
}
//delete Department
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM department WHERE DepartmentID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $message = "Department REMOVED!!!";
    addAuditLog($conn, $_SESSION['userID'], "Deleted department ".$id);
}
$departments = mysqli_query($conn, "SELECT * FROM department ORDER BY DepartmentName");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Departments | MSIR</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
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
                <li><a href="admin-dashboard.php">Dashboard</a></li>
                <li><a href="admin-manageUsers.php">Manage Users</a></li>
                <li class="active"><a href="admin-departments.php">Departments</a></li>
                <li><a href="admin-incidentTypes.php">Incident Types</a></li>
                <li><a href="admin-auditLogs.php">Audit Logs</a></li>
                <li><a href="admin-reports.php">Reports</a></li>
                <li><a href="../login.php">Logout</a></li>
            </ul>
     </aside>

    <!-- Main Content -->
     <main class="main-content">
        <h2>Department Management</h2>
        <?php if ($message!="") { ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php } ?>
        <div class="card p-3 mb-4 shadow">
            <h5>Add New Department</h5>
            <form method="POST">
                <div class="row">
                    <div class="col-md-9">
                        <input type="text" name="department" class="form-control" placeholder="Department Name" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="addDepartment" class="btn btn-success w-100">ADD</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card p-3 shadow">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th width="100">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row=mysqli_fetch_assoc($departments)) { ?>
                    <tr>
                        <td><?php echo $row['DepartmentID']; ?></td>
                        <td><?php echo $row['DepartmentName']; ?></td>
                        <td>
                            <a href="?delete=<?php echo $row['DepartmentID']; ?>"
                            class="btn btn-danger btn-sm">DELETE</a>
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