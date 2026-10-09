<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mining Safety Incident Reporting Application</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold">MIMOSA MINE</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="signup.php">Sign Up</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Mining Safety Incident Reporting Application</h1>
                <p class="lead mt-4">Welcome to the Mimosa Mining Safety Incident Reporting Application.
                    This platform allows mine workers to report safety incidents quickly and securely while
                    enabling supervisors and administrators to monitor, investigate, and resolve incidents efficiently.
                </p>
                    
                    <a href="login.php" class="btn btn-primary btn-lg mt-3">Login</a>
                    <a href="signup.php" class="btn btn-outline-secondary btn-lg mt-3">Create Account</a>
            </div>
            
            <div class="col-md-6 text-center">
                <img src="assets/images/Mimosa-mine-logo.png" class="img-fluid" alt="Mining Safety">
            </div>
        </div>
    </div>
</section>


<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Application Features</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-triangle-exclamation fa-3x text-danger mb-3"></i>
                        <h4>Report Incidents</h4>
                        <p>Workers can submit safety incidents with descriptions and supporting evidence.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-chart-line fa-3x text-success mb-3"></i>
                        <h4>Track Reports</h4>
                        <p>Monitor incident progress from submission to resolution.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about py-5">
    <div class="container">
        <h2>Why This Application?</h2>
        <p>The Mining Safety Incident Reporting Application aims to improve workplace safety by providing a centralized platform for reporting, monitoring, and managing safety incidents. The application promotes faster communication, efficient incident handling, and accurate record keeping while supporting better decision-making through real-time reporting.</p>
    </div>
</section>

<footer class="bg-black text-white text-center p-3">
    <p class="mb-0">&copy; 2026 Mimosa Mining Company |Mining Safety Incident Reporting Application</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>