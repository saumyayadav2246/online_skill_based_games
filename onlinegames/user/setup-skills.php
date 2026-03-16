<?php
session_start();
include('../connection.php');

// AUTHENTICATION CHECK
if(!isset($_SESSION['auth_token'])) {
    $_SESSION['message'] = "Please login to continue";
    header("Location: ../login.php");
    exit();
}

$token = $_SESSION['auth_token'];
$now = date("Y-m-d H:i:s");

$stmt = $conn->prepare("SELECT * FROM users WHERE auth_token=? AND token_expiry > ? LIMIT 1");
$stmt->bind_param("ss", $token, $now);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if($row['has_skills'] == 1) {
        header("Location: dashboard.php");
        exit();
    }
} else {
    session_destroy();
    echo "<script>localStorage.clear(); window.location.href='../login.php';</script>";
    exit();
}

// Fetch available skills
$skills_query = mysqli_query($conn, "SELECT * FROM skills ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - Brain League</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .skill-badge { 
            cursor: pointer; 
            transition: all 0.2s ease; 
            margin: 5px; 
            border: 2px solid #dee2e6 !important;
            user-select: none;
        }
        .skill-badge:hover { border-color: #1d4ed8 !important; color: #1d4ed8; }
        .skill-badge.selected { 
            background-color: #1d4ed8 !important; 
            color: white !important; 
            border-color: #1d4ed8 !important;
            box-shadow: 0 4px 10px rgba(29, 78, 216, 0.3);
        }
        .text-error { color: #dc3545; font-size: 0.9rem; display: none; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm sticky-top py-2">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand fw-bold fs-5 me-auto d-flex align-items-center text-decoration-none" href="#">
                <img src="../images/logo.jpeg" alt="Brain League" class="rounded me-2" style="width: 32px; height: 32px; object-fit: cover;">
                <span class="d-none d-md-inline fw-bold" style="color: #1d4ed8;">Brain League</span>
            </a>
            
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle d-flex align-items-center text-decoration-none text-dark p-2 rounded-pill bg-white shadow-sm border" 
                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../images/<?php echo htmlspecialchars($row['profile_image'] ?? 'default-user.png'); ?>" 
                         class="rounded-circle me-md-2" width="36" height="36" style="object-fit: cover;">
                    <span class="d-none d-lg-inline fw-semibold me-1"><?php echo htmlspecialchars($row['first_name']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0">
                    <li><a class="dropdown-item text-danger py-2 fw-semibold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-patch-check-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold">One last step, <?php echo htmlspecialchars($row['first_name']); ?>!</h2>
                        <p class="text-muted mb-4">Select the skills that best describe your expertise.</p>

                        <div id="validation-msg" class="alert alert-danger py-2 mb-4 text-error">
                            <i class="bi bi-exclamation-circle me-1"></i> You can select a maximum of 3 skills.
                        </div>

                        <form action="../api/save-skills.php" method="POST" id="skillsForm">
                            <div class="d-flex flex-wrap justify-content-center mb-4">
                                <?php while($skill = mysqli_fetch_assoc($skills_query)): ?>
                                    <div class="badge rounded-pill bg-white text-dark p-3 skill-badge" 
                                         data-id="<?php echo $skill['id']; ?>">
                                        <?php echo htmlspecialchars($skill['name']); ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>

                            <input type="hidden" name="skill_ids" id="skill_ids_input">
                            
                            <hr class="my-4">
                            
                            <button type="submit" name="save_skills_btn" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">
                                Complete My Profile <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const badges = document.querySelectorAll('.skill-badge');
        const hiddenInput = document.getElementById('skill_ids_input');
        const errorMsg = document.getElementById('validation-msg');
        let selectedIds = [];

        badges.forEach(badge => {
            badge.addEventListener('click', () => {
                const id = badge.getAttribute('data-id');
                
                if (selectedIds.includes(id)) {
                    selectedIds = selectedIds.filter(item => item !== id);
                    badge.classList.remove('selected');
                    errorMsg.style.display = 'none';
                } else {
                    if (selectedIds.length >= 3) {
                        errorMsg.style.display = 'block';
                        return; 
                    }
                    selectedIds.push(id);
                    badge.classList.add('selected');
                    errorMsg.style.display = 'none';
                }
                hiddenInput.value = selectedIds.join(',');
            });
        });

        document.getElementById('skillsForm').onsubmit = function(e) {
            const count = selectedIds.length;
            if (count === 0) {
                alert("Please select at least 1 skill to continue.");
                e.preventDefault();
                return false;
            }
        };
    </script>
<?php include('../includes/footer.php'); ?>