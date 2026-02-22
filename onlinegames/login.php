<?php 
session_start();
// Redirect user to dashboard if already logged in
if(isset($_SESSION['auth_token'])) {
    header("Location: user/dashboard.php");
    exit();
}
include('includes/header.php'); 
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm sticky-top">
    <div class="container">
        <!-- Brand/Logo -->
        <a class="navbar-brand fw-bold fs-4" href="index.php" style="color: #1d4ed8 !important;">
            <i class="bi bi-brain me-2"></i>Brain League
        </a>
        
        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Right side links (only show on public pages) -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link btn btn-outline-primary px-4 py-2 rounded-pill" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary px-4 py-2 rounded-pill" href="register.php">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow-sm mt-5">
                <h3 class="text-center mb-4">Login</h3>

                <?php
                    if(isset($_SESSION['message'])) {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                ' . $_SESSION['message'] . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        unset($_SESSION['message']);
                    }
                ?>

                <form action="api/login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="example@college.edu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="login_btn" class="btn btn-primary">Log In</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                    <a href="index.php" class="small text-muted">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>