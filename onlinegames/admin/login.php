<?php 
session_start();
// Redirect user to dashboard if already logged in
if(isset($_SESSION['auth_token'])) {
    header("Location: dashboard.php");
    exit();
}
include('../includes/header.php'); 
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm sticky-top">
    <div class="container">
        <!-- Brand/Logo -->
        <a class="navbar-brand fw-bold fs-4" href="login.php" style="color: #1d4ed8 !important;">
            <i class="bi bi-brain me-2"></i>Brain League
        </a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow-sm mt-5">
                <h3 class="text-center mb-4">Admin Login</h3>

                <?php
                    if(isset($_SESSION['message'])) {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                ' . $_SESSION['message'] . '
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                        unset($_SESSION['message']);
                    }
                ?>

                <form action="../api/login.php" method="POST">
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
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>