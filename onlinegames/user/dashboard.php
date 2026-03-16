<?php
// Start session and include database connection
session_start();
include('../connection.php');

// AUTHENTICATION CHECK
if(!isset($_SESSION['auth_token'])) {
    $_SESSION['message'] = "Please login to access the dashboard";
    header("Location: login.php");
    exit();
}

$token = $_SESSION['auth_token'];
$now = date("Y-m-d H:i:s");

// SECURE PREPARED STATEMENT
$stmt = $conn->prepare("SELECT * FROM users WHERE auth_token=? AND token_expiry > ? LIMIT 1");
$stmt->bind_param("ss", $token, $now);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if($row['has_skills'] == 0) {
        header("Location: setup-skills.php");
        exit();
    }
} else {
    session_destroy();
    echo "<script>localStorage.clear(); window.location.href='login.php';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Brain League</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        
        /* DESKTOP SIDEBAR FIXES */
        @media (min-width: 992px) {
            .sidebar-sticky-wrapper {
                position: sticky;
                top: 90px;
                height: calc(100vh - 120px);
                display: flex;
                flex-direction: column;
            }

            .sidebar-nav {
                flex-grow: 1;
                overflow-y: auto;
                scrollbar-width: thin;
                scrollbar-color: rgba(255,255,255,0.2) transparent;
            }

            .sidebar-nav::-webkit-scrollbar { width: 4px; }
            .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        }

        .sidebar-nav {
            background: linear-gradient(180deg, #1e3a8a 0%, #1d4ed8 100%);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            border-radius: 10px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.2);
            transform: translateX(8px);
        }

        /* STAT CARDS */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: transform 0.3s ease;
            height: 120px;
        }
        .stat-card:hover { transform: translateY(-8px); }
        .stat-card-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card-success { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .stat-card-warning { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stat-card-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm sticky-top py-2">
        <div class="container-fluid px-3 px-md-4">
            <button class="navbar-toggler me-2 border-0 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-2"></i>
            </button>
            
            <a class="navbar-brand fw-bold fs-5 me-auto d-flex align-items-center text-decoration-none" href="dashboard.php">
                <img src="../images/logo.jpeg" alt="Brain League" class="rounded me-2" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="d-none d-md-inline fw-bold" style="color: #1d4ed8;">Brain League</span>
            </a>
            
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle d-flex align-items-center text-decoration-none text-dark p-2 rounded-pill bg-white shadow-sm border" 
                   href="#" role="button" data-bs-toggle="dropdown">
                    <img src="../images/<?php echo htmlspecialchars($row['profile_image'] ?? 'default-user.png'); ?>" 
                         class="rounded-circle me-md-2" width="36" height="36" style="object-fit: cover;">
                    <span class="d-none d-lg-inline fw-semibold me-1">
                        <?php echo htmlspecialchars($row['first_name']); ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0">
                    <li class="p-3 border-bottom bg-light rounded-top">
                        <div class="fw-bold"><?php echo htmlspecialchars($row['first_name']); ?></div>
                        <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                    </li>
                    <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider m-0"></li>
                    <li><a class="dropdown-item text-danger py-2 fw-semibold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" style="width: 280px;">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title fw-bold text-primary mb-0"><i class="bi bi-brain me-2"></i>Menu</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-3 bg-primary">
            <ul class="nav flex-column nav-pills sidebar-nav h-100 shadow-none">
                <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-house-door-fill me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="tournaments.php"><i class="bi bi-trophy-fill me-2"></i>Tournaments</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="games.php"><i class="bi bi-controller me-2"></i>Games</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="leaderboard.php"><i class="bi bi-bar-chart-fill me-2"></i>Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
            </ul>
        </div>
    </div>

    <main class="container-fluid py-4 px-3 px-md-4">
        <div class="row g-4">
            
            <div class="col-lg-3 col-xl-2 d-none d-lg-block">
                <div class="sidebar-sticky-wrapper">
                    <div class="sidebar-nav p-3">
                        <h6 class="text-white-50 fw-bold mb-4 text-center small text-uppercase">Navigation</h6>
                        <ul class="nav flex-column h-100">
                            <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-house-door-fill me-2"></i>Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="tournaments.php"><i class="bi bi-trophy-fill me-2"></i>Tournaments</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="games.php"><i class="bi bi-controller me-2"></i>Games</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="leaderboard.php"><i class="bi bi-bar-chart-fill me-2"></i>Leaderboard</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-xl-10">

                <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <?php 
                            echo $_SESSION['message']; 
                            unset($_SESSION['message']); // Clear message after displaying
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row mb-4 g-3">
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card text-white shadow stat-card-primary">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase small fw-bold">Games</h6>
                                    <h2 class="fw-bold mb-0">127</h2>
                                </div>
                                <i class="bi bi-controller fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card text-white shadow stat-card-info">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase small fw-bold">Win Rate</h6>
                                    <h2 class="fw-bold mb-0">78%</h2>
                                </div>
                                <i class="bi bi-trophy fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card text-white shadow stat-card-success">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase small fw-bold">Rank</h6>
                                    <h2 class="fw-bold mb-0">#47</h2>
                                </div>
                                <i class="bi bi-star-fill fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stat-card text-white shadow stat-card-warning">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase small fw-bold">Points</h6>
                                    <h2 class="fw-bold mb-0">2,847</h2>
                                </div>
                                <i class="bi bi-gem fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="fw-bold">Welcome Back, <?php echo htmlspecialchars($row['first_name']); ?>!</h3>
                        <p class="text-muted">Your Brain League journey continues. Check your stats and dive into tournaments or quick games.</p>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a href="tournaments.php" class="btn btn-primary px-4 py-2 rounded-pill disabled">Active Tournaments</a>
                            <a href="games.php" class="btn btn-outline-primary px-4 py-2 rounded-pill disabled">Play Now</a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="bi bi-trophy text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Won Memory Match Tournament</div>
                                        <small class="text-muted">2 hours ago • +250 points</small>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                        <i class="bi bi-star-fill text-success fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">Completed Daily Challenge</div>
                                        <small class="text-muted">Yesterday • +75 points</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> </div>
    </main>

<?php include('../includes/footer.php'); ?>