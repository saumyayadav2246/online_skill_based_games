<?php
session_start();
include('../connection.php');

if(!isset($_SESSION['auth_token'])){
    header("Location: login.php");
    exit();
}

$token = $_SESSION['auth_token'];
$now = date("Y-m-d H:i:s");

$stmt = $conn->prepare("SELECT * FROM users WHERE auth_token=? AND token_expiry>? LIMIT 1");
$stmt->bind_param("ss",$token,$now);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    session_destroy();
    header("Location: login.php");
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Games - Brain League</title>

<link rel="stylesheet" href="/onlinegames/assets/css/bootstrap.min.css">
<link href="../assets/css/dashboard.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
body { background:#f8f9fa; }

/* Sidebar same as dashboard */
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
    font-weight: 500;
}

.sidebar-nav .nav-link:hover,
.sidebar-nav .nav-link.active {
    color: white !important;
    background: rgba(255,255,255,0.2);
    transform: translateX(8px);
}

/* Game cards */
.game-card{
border-radius:20px;
transition:0.3s;
background:linear-gradient(135deg,#1e3c72,#2a5298);
color:white;
}
.game-card:hover{
transform:translateY(-5px);
}
</style>
</head>

<body>

<!-- NAVBAR SAME AS DASHBOARD -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm sticky-top py-2">
    <div class="container-fluid px-3 px-md-4">
        <button class="navbar-toggler me-2 border-0 p-2" type="button" 
                data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <i class="bi bi-list fs-2"></i>
        </button>
        
        <a class="navbar-brand fw-bold fs-5 me-auto d-flex align-items-center text-decoration-none" href="dashboard.php">
            <img src="../images/logo.jpeg" alt="Brain League" class="rounded me-2" 
                 style="width: 32px; height: 32px; object-fit: cover;">
            <span class="d-none d-md-inline fw-bold" style="color: #1d4ed8;">Brain League</span>
        </a>
        
        <div class="dropdown ms-auto">
            <a class="dropdown-toggle d-flex align-items-center text-decoration-none text-dark p-2 rounded-pill bg-white shadow-sm border" 
               href="#" role="button" data-bs-toggle="dropdown">
                <img src="../images/<?php echo htmlspecialchars($row['profile_image'] ?? 'default-user.png'); ?>" 
                     class="rounded-circle me-md-2" width="36" height="36">
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
                <li><a class="dropdown-item text-danger py-2 fw-semibold" href="logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- MOBILE OFFCANVAS -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" style="width: 280px;">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title fw-bold text-primary mb-0">
            <i class="bi bi-brain me-2"></i>Menu
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3 bg-primary">
        <ul class="nav flex-column nav-pills sidebar-nav h-100 shadow-none">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-house-door-fill me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">
                    <i class="bi bi-trophy-fill me-2"></i>Tournaments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="games.php">
                    <i class="bi bi-controller me-2"></i>Games
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">
                    <i class="bi bi-bar-chart-fill me-2"></i>Leaderboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">
                    <i class="bi bi-gear-fill me-2"></i>Settings
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- MAIN -->
<main class="container-fluid py-4 px-3 px-md-4">
<div class="row g-4">

<!-- DESKTOP SIDEBAR -->
<div class="col-lg-3 col-xl-2 d-none d-lg-block">
    <div class="sidebar-sticky-wrapper">
        <div class="sidebar-nav p-3">
            <h6 class="text-white-50 fw-bold mb-4 text-center small text-uppercase">Navigation</h6>
            <ul class="nav flex-column h-100">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-house-door-fill me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">
                        <i class="bi bi-trophy-fill me-2"></i>Tournaments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="games.php">
                        <i class="bi bi-controller me-2"></i>Games
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">
                        <i class="bi bi-bar-chart-fill me-2"></i>Leaderboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">
                        <i class="bi bi-gear-fill me-2"></i>Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div class="col-lg-9 col-xl-10">

    <h2 class="mb-4">🧠 Analytical & Problem-Solving Games</h2>

    <div class="row g-4 align-items-stretch mb-5">

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🧠 Logic Grid Puzzle</h4>
                    <p class="flex-grow-1">
                        Use clues to deduce the correct matches. Mark ✔ or ❌ on the grid and solve the mystery.
                    </p>
                    <a href="games/game4-algo-race.php" class="btn btn-light mt-auto">Play Logic Grid</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🚪 Escape Room Logic Game</h4>
                    <p class="flex-grow-1">
                        Solve puzzles to escape: decode passwords, crack number locks, and arrange sequences under time pressure.
                    </p>
                    <a href="games/game5-stack-storm.php" class="btn btn-light mt-auto">Play Escape Room</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🔮 Pattern Prediction Game</h4>
                    <p class="flex-grow-1">
                        Identify the hidden rule behind sequences and patterns, then predict what comes next under time pressure.
                    </p>
                    <a href="games/game6-queue-rush.php" class="btn btn-light mt-auto">Play Pattern Game</a>
                </div>
            </div>
        </div>

    </div>

    <h2 class="mb-4">🗣️ Language & Communication Games</h2>

    <div class="row g-4 align-items-stretch mb-5">

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🎙️ Interview Conversation Simulator</h4>
                    <p class="flex-grow-1">
                        Talk to a virtual interviewer and choose the best answers. Build confidence, clarity and structure.
                    </p>
                    <a href="games/game7-interview-sim.php" class="btn btn-light mt-auto">Play Interview Sim</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>📧 Email Response Challenge</h4>
                    <p class="flex-grow-1">
                        Read professional emails and pick the best reply. Learn email etiquette and clear writing.
                    </p>
                    <a href="games/game8-email-challenge.php" class="btn btn-light mt-auto">Play Email Challenge</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🤖 Chat Simulator Game</h4>
                    <p class="flex-grow-1">
                        Choose the best response in real-life situations (teacher, interview, workplace). Train politeness and professional communication.
                    </p>
                    <a href="games/game9-story-continuation.php" class="btn btn-light mt-auto">Play Chat Simulator</a>
                </div>
            </div>
        </div>

    </div>

    <h2 class="mb-4">🔥 SQL Skill Games</h2>

    <div class="row g-4 align-items-stretch">

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>⚡ SQL Build Arena PRO</h4>
                    <p class="flex-grow-1">Drag & drop to build real SQL queries dynamically.</p>
                    <a href="games/game1-city-defender.php" class="btn btn-light mt-auto">Start Game</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🐍 SQL Snake Pro</h4>
                    <p class="flex-grow-1">Collect only valid SQL keywords.</p>
                    <a href="games/game2-query-architect.php" class="btn btn-light mt-auto">Start Game</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card game-card shadow h-100 w-100">
                <div class="card-body d-flex flex-column">
                    <h4>🔫 Shoot a Query</h4>
                    <p class="flex-grow-1">Shoot the correct SQL query.</p>
                    <a href="games/game3-battle-arena.php" class="btn btn-light mt-auto">Start Game</a>
                </div>
            </div>
        </div>

    </div>
</div>

</div>
</main>

<script src="/onlinegames/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>