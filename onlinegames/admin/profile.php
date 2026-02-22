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
        
        /* DESKTOP SIDEBAR STYLES */
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
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
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

        /* MOBILE OVERRIDES */
        @media (max-width: 991.98px) {
            .navbar .dropdown-menu {
                position: absolute !important;
                top: 100% !important;
                right: 1rem !important;
                z-index: 1060 !important;
            }
        }

        /* STAT CARDS */
        .stat-card {
            border: none;
            border-radius: 20px;
            transition: transform 0.3s ease;
            height: 120px;
        }
        .stat-card:hover { transform: translateY(-8px); }
        .stat-card-primary { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .stat-card-success { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
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
            <h6 class="offcanvas-title fw-bold text-primary mb-0"><i class="bi bi-shield-lock me-2"></i>Admin Menu</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-3" style="background-color: #0f172a;">
            <ul class="nav flex-column nav-pills sidebar-nav h-100 shadow-none">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                <li class="nav-item"><a class="nav-link" href="skills.php"><i class="bi bi-mortarboard-fill me-2"></i>Skills</a></li>
                <li class="nav-item"><a class="nav-link disabled" href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
            </ul>
        </div>
    </div>

    <main class="container-fluid py-4 px-3 px-md-4">
        <div class="row g-4">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block">
                <div class="sidebar-sticky-wrapper">
                    <div class="sidebar-nav p-3">
                        <ul class="nav flex-column h-100">
                            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="skills.php"><i class="bi bi-mortarboard-fill me-2"></i>Skills</a></li>
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include('../includes/footer.php'); ?>