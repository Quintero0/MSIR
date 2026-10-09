<?php 
session_start();
 if (!isset($_SESSION['role']) || $_SESSION['role'] != "supervisor") {
    header("Location: ../login.php");
    exit();
 }
 require_once "../includes/db_connection.php";
 require_once "../includes/audit.php";
 $message = "";

 //get all incidents
 $incidents = mysqli_query($conn, "SELECT IncidentID, Title FROM incidents ORDER BY IncidentID DESC");

 //save assessment
 if (isset($_POST['saveRisk'])) {
    $incidentID = $_POST['incidentID'];
    $supervisorID = $_SESSION['userID'];
    $hazard = $_POST['hazard'];
    $riskLevel = $_POST['riskLevel'];
    $likelihood = $_POST['likelihood'];
    $control = $_POST['control'];
    $sql = "INSERT INTO riskassessment 
    (IncidentID, SupervisorID, Hazard, RiskLevel, Likelihood, ControlMeasure) 
    VALUES (?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt, "iissss", $incidentID, $supervisorID, $hazard, $riskLevel, $likelihood, $control
    );
    if(mysqli_stmt_execute($stmt)) {
        $message = "Risk assessment saved successfully!";
    }
 }
 ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Risk Assessment | MSIR</title>

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
            <li><a href="supervisor-dashboard.php">Dashboard</a></li>
            <li><a href="supervisor-manageReports.php">Manage Reports</a></li>
            <li class="active"><a href="supervisor-riskAssessment.php">Risk Assessment</a></li>
            <li><a href="supervisor-profile.php">Profile</a></li>
            <li><a href="../login.php">Logout</a></li>
        </ul>

    </aside>
    <!-- Main Content -->
    <main class="main-content">
        <h2>Risk Assessment</h2>
        <?php if($message != ""){ ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php } ?>
        <form method="POST">
            <select name="incidentID" class="form-control mb-3" required>
                <option value="">Select Incident</option>
                <?php while($row = mysqli_fetch_assoc($incidents)){ ?>
                <option value="<?php echo $row['IncidentID']; ?>">
                    INC00<?php echo $row['IncidentID']; ?> - 
                    <?php echo $row['Title']; ?>
                </option>
                <?php } ?>
            </select>
            <label>Hazard Identified</label>
            <input type="text" name="hazard" class="form-control mb-3" required>
            <label>Risk level</label>
            <select name="riskLevel" class="form-control mb-3" required>
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
                <option>Critical</option>
            </select>
            <label>Likelihood</label>
            <select name="likelihood" class="form-control mb-3" required>
                <option>Rare</option>
                <option>Possible</option>
                <option>Likely</option>
                <option>Almost certain</option>
            </select>
            <label>Control Measures</label>
            <textarea name="control" rows="4" class="form-control mb-3" required></textarea>
            <button type="submit" name="saveRisk" class="button-success">Save Assessment</button>
        </form>
    </main>
</div>
</body>
</html>