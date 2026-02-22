<?php
// Start session and include database connection
session_start();
include('../connection.php');

// AUTHENTICATION CHECK
if(!isset($_SESSION['auth_token'])) {
    $_SESSION['message'] = "Please login to access your profile";
    header("Location: login.php");
    exit();
}

$token = $_SESSION['auth_token'];
$now = date("Y-m-d H:i:s");

// 1. FETCH BASIC USER DATA
$stmt = $conn->prepare("SELECT * FROM users WHERE auth_token=? AND token_expiry > ? LIMIT 1");
$stmt->bind_param("ss", $token, $now);
$stmt->execute();
$user_result = $stmt->get_result();

if($user_result->num_rows > 0) {
    $row = $user_result->fetch_assoc();
    $user_id = $row['id']; // Get ID to fetch skills
} else {
    session_destroy();
    echo "<script>localStorage.clear(); window.location.href='login.php';</script>";
    exit();
}

// 2. FETCH USER SKILLS VIA JOIN
// This connects the user_skills link table to the main skills definition table
$skills_query = "SELECT s.name 
                 FROM skills s 
                 JOIN user_skills us ON s.id = us.skill_id 
                 WHERE us.user_id = ?";
$skill_stmt = $conn->prepare($skills_query);
$skill_stmt->bind_param("i", $user_id);
$skill_stmt->execute();
$skills_result = $skill_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Brain League</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        
        /* Sidebar Styles (Consistent with Dashboard) */
        @media (min-width: 992px) {
            .sidebar-sticky-wrapper {
                position: sticky;
                top: 90px;
                height: calc(100vh - 120px);
                display: flex;
                flex-direction: column;
            }
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
        }

        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.2);
            transform: translateX(8px);
        }

        /* Profile Header & Skill Badges */
        .profile-header-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 100%);
            height: 140px;
            border-radius: 15px 15px 0 0;
        }
        .profile-avatar-wrapper {
            margin-top: -70px;
            padding: 5px;
            background: white;
            border-radius: 50%;
            display: inline-block;
        }
        .skill-badge {
            background: #e0e7ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .skill-badge:hover {
            background: #4338ca;
            color: white;
        }
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
                <a class="dropdown-toggle d-flex align-items-center text-decoration-none text-dark p-2 rounded-pill bg-white shadow-sm border" href="#" role="button" data-bs-toggle="dropdown">
                    <img src="../images/<?php echo htmlspecialchars($row['profile_image'] ?? 'default-user.png'); ?>" class="rounded-circle me-md-2" width="36" height="36" style="object-fit: cover;">
                    <span class="d-none d-lg-inline fw-semibold me-1"><?php echo htmlspecialchars($row['first_name']); ?></span>
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
                            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-house-door-fill me-2"></i>Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="tournaments.php"><i class="bi bi-trophy-fill me-2"></i>Tournaments</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="games.php"><i class="bi bi-controller me-2"></i>Games</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="leaderboard.php"><i class="bi bi-bar-chart-fill me-2"></i>Leaderboard</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-xl-10">
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="profile-header-bg"></div>
                    <div class="card-body pt-0 px-4 px-md-5">
                        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end mb-4">
                            <div class="profile-avatar-wrapper shadow-sm mb-3 mb-md-0">
                                <img src="../images/<?php echo htmlspecialchars($row['profile_image'] ?? 'default-user.png'); ?>" 
                                     class="rounded-circle" width="130" height="130" style="object-fit: cover;">
                            </div>
                            <div class="ms-md-4 mb-md-2 text-center text-md-start flex-grow-1">
                                <h2 class="fw-bold mb-0"><?php echo htmlspecialchars($row['first_name'] . ' ' . ($row['last_name'] ?? '')); ?></h2>
                                <p class="text-muted mb-0">@<?php echo htmlspecialchars($row['username'] ?? 'brain_leaguer'); ?></p>
                            </div>
                            <div class="mb-md-2">
                                <a href="edit-profile.php" class="btn btn-primary rounded-pill px-4 btn-sm disabled">Edit Profile</a>
                            </div>
                        </div>

                        <div class="row pt-2">
                            <div class="col-md-7 border-end-md">
                                <h6 class="text-uppercase small fw-bold text-muted mb-3">About Me</h6>
                                <p class="mb-4">Welcome to my Brain League profile! I'm here to sharpen my skills and climb the leaderboard.</p>
                                
                                <h6 class="text-uppercase small fw-bold text-muted mb-3">Contact Information</h6>
                                <div class="list-group list-group-flush mb-4">
                                    <div class="list-group-item px-0 border-0 bg-transparent py-1">
                                        <i class="bi bi-envelope-at me-2 text-primary"></i> <?php echo htmlspecialchars($row['email']); ?>
                                    </div>
                                    <div class="list-group-item px-0 border-0 bg-transparent py-1">
                                        <i class="bi bi-calendar3 me-2 text-primary"></i> Joined <?php echo date('F Y', strtotime($row['created_at'])); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 ps-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-uppercase small fw-bold text-muted mb-0">My Skills</h6>
                                    <!-- <?php if($row['has_skills'] == 1): ?>
                                        <a href="setup-skills.php" class="small text-decoration-none">Update</a>
                                    <?php endif; ?> -->
                                </div>
                                
                                <div class="d-flex flex-wrap gap-2">
                                    <?php 
                                    if($skills_result->num_rows > 0) {
                                        while($skill = $skills_result->fetch_assoc()) {
                                            echo '<span class="skill-badge">' . htmlspecialchars($skill['name']) . '</span>';
                                        }
                                    } else {
                                        echo '<div>
                                                <p class="text-muted small mb-2">No skills showcased yet.</p>
                                                <a href="setup-skills.php" class="btn btn-sm btn-outline-primary rounded-pill">Add Skills</a>
                                              </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include('../includes/footer.php'); ?>