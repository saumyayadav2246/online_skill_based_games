<?php
// Start session and include database connection
session_start();
include('../connection.php');

// AUTHENTICATION CHECK
if(!isset($_SESSION['auth_token'])) {
    $_SESSION['message'] = "Please login to access the admin panel";
    header("Location: login.php");
    exit();
}

$token = $_SESSION['auth_token'];
$now = date("Y-m-d H:i:s");

// SECURE PREPARED STATEMENT FOR LOGGED IN ADMIN
$stmt = $conn->prepare("SELECT * FROM users WHERE auth_token=? AND token_expiry > ? LIMIT 1");
$stmt->bind_param("ss", $token, $now);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    session_destroy();
    echo "<script>localStorage.clear(); window.location.href='login.php';</script>";
    exit();
}

// --- DATA FETCHING FOR THE TABLE & STATS ---

// 1. Fetch real count of non-admin users
$total_users_query = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_admin = 0");
$total_users_count = $total_users_query->fetch_assoc()['total'];

// 2. Fetch all non-admin users for the table
$users_list = $conn->query("SELECT id, first_name, last_name, email, profile_image, created_at, has_skills FROM users WHERE is_admin = 0 ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Brain League</title>
    <link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        
        /* SIDEBAR STYLES (Keeping your original) */
        @media (min-width: 992px) {
            .sidebar-sticky-wrapper { position: sticky; top: 90px; height: calc(100vh - 120px); display: flex; flex-direction: column; }
            .sidebar-nav { flex-grow: 1; overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.2) transparent; }
        }
        .sidebar-nav { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .sidebar-nav .nav-link { color: rgba(255,255,255,0.9); padding: 12px 20px; border-radius: 10px; margin: 4px 12px; transition: all 0.3s ease; font-weight: 500; }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active { color: white !important; background: rgba(255,255,255,0.2); transform: translateX(8px); }

        /* STAT CARDS */
        .stat-card { border: none; border-radius: 20px; transition: transform 0.3s ease; height: 120px; }
        .stat-card-primary { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .stat-card-success { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }

        /* TABLE SPECIFIC STYLES */
        .user-avatar-sm { width: 35px; height: 35px; object-fit: cover; border-radius: 8px; }
        .table-container { border: none; border-radius: 15px; background: white; overflow: hidden; }
        .table thead th { background-color: #f8fafc; color: #64748b; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-top: none; }
        .table-responsive {
                /* Ensures there is enough room for the dropdown even with 1 row */
                min-height: 250px; 
            overflow-x: auto;
            /* This allows the dropdown to "overflow" the container visually */
            overflow-y: visible !important; 
        }

        /* Ensure the container doesn't clip the dropdown */
        .table-container {
            overflow: visible !important;
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
            <h6 class="offcanvas-title fw-bold text-primary mb-0"><i class="bi bi-shield-lock me-2"></i>Admin Menu</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-3" style="background-color: #0f172a;">
            <ul class="nav flex-column nav-pills sidebar-nav h-100 shadow-none">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                <li class="nav-item"><a class="nav-link" href="skills.php"><i class="bi bi-mortarboard-fill me-2"></i>Skills</a></li>
                <li class="nav-item"><a class="nav-link diabled" href="#"><i class="bi bi-mortarboard-fill me-2"></i>Settings</a></li>
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
                            <li class="nav-item"><a class="nav-link active" href="users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="skills.php"><i class="bi bi-mortarboard-fill me-2"></i>Skills</a></li>
                            <li class="nav-item"><a class="nav-link disabled" href="#"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-xl-10">
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <div class="card stat-card text-white shadow stat-card-primary">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="text-uppercase small fw-bold">Total Users</h6>
                                    <h2 class="fw-bold mb-0"><?php echo number_format($total_users_count); ?></h2>
                                </div>
                                <i class="bi bi-people fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm table-container">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold mb-0 text-dark">User Management</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Full Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($users_list->num_rows > 0): ?>
                                    <?php while($user = $users_list->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="../images/<?php echo htmlspecialchars($user['profile_image'] ?? 'default-user.png'); ?>" 
                                                     class="user-avatar-sm me-2 border">
                                                <span class="fw-semibold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php if($user['has_skills'] == 1): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Skills Set</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border rounded-pill">No Skills</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-pill px-3 disabled" 
                                                        data-bs-toggle="dropdown" 
                                                        data-bs-boundary="viewport" 
                                                        aria-expanded="false">
                                                    Manage <i class="bi bi-chevron-down ms-1 small"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                    <li><a class="dropdown-item disabled" href="view_user.php?id=<?php echo $user['id']; ?>"><i class="bi bi-eye me-2"></i> View Profile</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger disabled" href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')"><i class="bi bi-trash me-2"></i> Delete User</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">No users found in database.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div> 
        </div>
    </main>

<?php include('../includes/footer.php'); ?>