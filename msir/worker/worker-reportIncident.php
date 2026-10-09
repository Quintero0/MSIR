<?php
session_start();
iF(!isset($_SESSION['role']) || $_SESSION['role'] != "worker") {
    header("location: ../login.php");
    exit;
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$message = "";
if (isset($_POST['report'])) {
    $userID = $_SESSION['userID'];
    $title = $_POST['title'];
    $type =$_POST['type'];
    $location = $_POST['location'];
    $severity = $_POST['severity'];
    $description = $_POST['description'];
    $status = "Open";
    $sql = "INSERT INTO incidents
    (UserID, TypeID, Title, Description, Location, Severity, DateReported, Status)
    VALUES (?,?,?,?,?,?, NOW(), ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iisssss",
        $userID,
        $type,
        $title,
        $description,
        $location,
        $severity,
        $status
    );

    if(mysqli_stmt_execute($stmt)) {
        $message = "Incident reported succesfully!";
        $incidentID = mysqli_insert_id($conn);
        addAuditLog($conn, $userID, "Reported Incident INC0".$incidentID);
    } else {
        die(mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Incident | MSIR</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="dasboard-body">

<div class="wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo">
            <img src="../assets/images/lets-go-zero.png" alt="Logo" class="img-fluid">
        </div>

        <ul>
            <li><a href="worker-dashboard.php">Dashboard</a></li>
            <li><a href="worker-reportIncident.php" class="active">Report Incident</a></li>
            <li><a href="worker-myReports.php">My Reports</a></li>
            <li><a href="worker-notifications.php">Notifications</a></li>
            <li><a href="worker-profile.php">Profile</a></li>
            <li><a href="../login.php">Logout</a></li>

        </ul>

    </aside>

    <!-- Main Content -->
    <main class="main-content">

            <div>

                <h2>Report New Incident</h2>
                <?php if($message !=""){?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
                <?php } ?>
                <p>Complete the form below to submit an incident report.</p>
            </div>

        <div class="form-container">

            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Incident Title -->

                <div class="mb-3">
                    <label class="form-label">Incident Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>

                <!-- Row 1 -->

                <div class="row">

                    <div class="mb-3">
                        <label class="form-label">Incident Type</label>
                        <select class="form-select" name="type" required>
                            <option value="">Select Incident Type</option>
                            <option value="2">Equipment Failure</option>
                            <option value="3">Slip / Trip / Fall</option>
                            <option value="4">Fire Incident</option>
                            <option value="5">Gas Leak</option>
                            <option value="6">Chemical Spill</option>
                            <option value="7">Electrical Hazard</option>
                            <option value="8">Unsafe Behaviour</option>
                            <option value="9">Near Miss</option>
                            <option value="10">Injury</option>
                            <option value="1">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Severity</label>
                        <select class="form-select" name="severity" required>
                            <option value="">Select Severity</option>
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                            <option>Critical</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2 -->

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select class="form-select" name="department">
                            <option>Engineering</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Specific Location</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                </div>

                <!-- Row 3 -->

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">Date of Incident</label>
                        <input type="date" class="form-control" name="incidentDate" required>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time of Incident</label>
                        <input type="time" class="form-control" name="incidentTime" required>

                    </div>

                </div>

                <!-- Row 4 -->

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label">Witness Present</label>
                        <select class="form-select" id="witness">
                            <option>No</option>
                            <option>Yes</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Was Anyone Injured?</label>
                        <select class="form-select" id="injured">
                            <option>No</option>
                            <option>Yes</option>
                        </select>
                    </div>
                </div>

                <!-- Hidden Fields -->

                <div class="row">
                    <div class="mb-3" id="witnessNameDiv" style="display:none;">
                        <label class="form-label">Witness Name</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3" id="injuredNumberDiv" style="display:none;">
                        <label class="form-label">Number of Injured Persons</label>
                        <input type="number" class="form-control" min="1">
                    </div>

                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="6" name="description" required></textarea>
                </div>

                <!-- Evidence -->
                <div class="mb-4">
                    <label class="form-label">Attach Evidence</label>
                    <input type="file" class="form-control" name="attachments">
                    <small class="text-muted">Accepted formats: JPG, PNG, PDF (Maximum 5 MB)</small>
                </div>

                <!-- Buttons -->
                <button type="submit" name="report" class="edit-btn">Submit Report</button>
                <button type="reset" class="btn btn-secondary">Clear Form</button>
            </form>

        </div>

    </main>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>
</html>