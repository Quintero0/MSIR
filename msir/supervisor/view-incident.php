<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "supervisor") {
    header("Location: ../login.php");
    exit();
}
require_once "../includes/db_connection.php";
require_once "../includes/audit.php";
$incidentID = $_GET['id'];
$sql = "SELECT incidents.*, users.FullName, users.EmployeeNumber,
department.DepartmentName, incidenttypes.TypeName
FROM incidents
INNER JOIN users ON incidents.UserID = users.UserID
INNER JOIN department ON users.DepartmentID = department.DepartmentID
INNER JOIN incidenttypes ON incidents.TypeID = incidenttypes.TypeID
WHERE incidents.IncidentID = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $incidentID);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$incident = mysqli_fetch_assoc($result);

$message = "";
if (isset($_POST['updateStatus'])) {
    $newStatus = trim($_POST['status']);
    $update = "UPDATE incidents
    SET Status = ?
    WHERE IncidentID = ?";
    $stmt2 = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param(
        $stmt2,
        "si",
        $newStatus,
        $incidentID
    );

    if (!mysqli_stmt_execute($stmt2)) {
        die(mysqli_error($conn));
    }
    echo "Saved as: " . $newStatus;
    addAuditLog($conn, $_SESSION['userID'], "Updated Incident INC0".$incidentID. "to".$newStatus);
    exit();
}
//save corrective action
if (isset($_POST['saveAction'])) {
    $action = trim($_POST['actionTaken']);
    $supervisorID = $_SESSION['userID'];
    $sql = "INSERT INTO actions 
    (IncidentID, SupervisorID, ActionTaken, DateActioned)
    VALUES (?, ?, ?, NOW())";
    $stmt3 = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt3, "iis", $incidentID, $supervisorID, $action);
    if (mysqli_stmt_execute($stmt3)) {
        $message = "Corrective action saved!!";
    }
    addAuditLog($connz, $_SESSION['userID'], "Added corrective action to Incident INC0".$incidentID);
}
//get corrective actions
$actionSQL = "SELECT actions.*, users.FullName
FROM actions
INNER JOIN users
ON actions.SupervisorID = users.UserID
WHERE actions.IncidentID = ?
ORDER BY DateActioned DESC";

$stmt4 = mysqli_prepare($conn, $actionSQL);
mysqli_stmt_bind_param($stmt4, "i", $incidentID);
mysqli_stmt_execute($stmt4);
$actions = mysqli_stmt_get_result($stmt4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>view Incidents | MSIR</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- Main Content -->
    <main class="main-content">
        <h2 class="mb-4">Incident Details</h2>
        <div class="card p-4 shadow">

            <div class="info-row">
                <span>Worker</span>
                <strong><?php echo $incident['FullName']; ?></strong>
            </div>
            
            <div class="info-row">
                <span>Employee Number</span>
                <strong><?php echo $incident['EmployeeNumber']; ?></strong>
            </div>
            
            <div class="info-row">
                <span>Department</span>
                <strong><?php echo $incident['DepartmentName']; ?></strong>
            </div>
            
            <div class="info-row">
                <span>Incident Type</span>
                <strong><?php echo $incident['TypeName']; ?></strong>
            </div>
            
            <div class="info-row">
                <span>Location</span>
                <strong><?php echo $incident['Location']; ?></strong>
            </div>
            
            <div class="info-row">
                <span>Severity</span>
                <strong><?php echo $incident['Severity']; ?></strong>
            </div>
            
            <div class="info-row">
                <span>Status</span>
                <strong><?php echo $incident['Status']; ?></strong>
            </div>

                <h5>Incident Description</h5>
                <p><?php echo $incident['Description']; ?></p>

            <?php if($message!=""){  ?>
            <div class="alert alert-success mt-3">
                <?php echo $message; ?>
                <?php } ?>
                <hr>
                <form method="POST">
                    <label class="form-label">Update Incident Status</label>
                    <select name="status" class="form-select mb-3" required>
                        <option value="">------</option>
                        <option class="status-open" value="Open">Open</option>
                        <option class="status-investigation" value="Under Investigation">Under Investigation</option>
                        <option class="resolved" value="Resolved">Resolved</option>
                        <option class="status-closed" value="Closed">Closed</option>
                    </select>
                    <button type="submit" name="updateStatus" class="button-success">Update Status</button>
                </form>
                <hr>
                <h5>Corrective Action</h5>
                <form method="POST">
                    <textarea name="actionTaken" class="form-control mb-3" rows="4" 
                    placeholder="Describe the corrective action taken..." required></textarea>
                    <button type="submit" name="saveAction" class="button-success">Save Action</button>
                </form>
                <hr>
                <h5>Previous Corrective Actions</h5>
                <?php while($row = mysqli_fetch_assoc($actions)){ ?>
                <div class="card p-3 mb-3">
                    <strong><?php echo $row['FullName'] ?></strong>
                    <small class="text-muted">
                        <?php echo date("d M Y:i", strtotime($row['DateActioned'])); ?>
                    </small>
                    <p class="mt-2 mb-0">
                        <?php echo $row['ActionTaken']; ?>
                    </p>
                </div>
                <?php } ?>
            </div>
                <a href="supervisor-dashboard.php" class="button-success">Return to Dashboard</a>
        </div>
    </main>
</div>
</body>
</html>