<?php include('includes/header.php'); ?>

<!-- FULLY RESPONSIVE Light Blue Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 fs-md-3" href="index.php" style="color: #1d4ed8 !important;">
            <i class="bi bi-brain me-2"></i>Brain League
        </a>
        
        <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if(basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
                    <li class="nav-item mx-lg-2 mx-1">
                        <a class="nav-link btn btn-outline-primary px-4 py-2 rounded-pill fs-6 fs-sm-5 shadow-sm" href="login.php">
                            <i class="bi bi-box-arrow-in-right me-1 d-none d-sm-inline"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary px-4 py-2 rounded-pill fs-6 fs-sm-5 shadow" href="register.php">
                            <i class="bi bi-person-plus me-1 d-none d-sm-inline"></i>Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- FULLY RESPONSIVE HERO SECTION -->
<section class="vh-100 d-flex align-items-center py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                <!-- RESPONSIVE LOGO -->
                <div class="logo-section mb-4">
                    <img src="images/logo.jpeg" alt="Brain League - Battle of Minds" 
                         class="img-fluid rounded shadow-lg mx-auto d-block p-3"
                         style="
                            max-width: 500px; 
                            width: 100%; 
                            height: auto;
                            max-height: 300px;
                         ">
                </div>
                
                <!-- RESPONSIVE CTAs -->
                <div class="cta-section mt-5">
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center mb-4">
                        <a href="login.php" class="btn btn-primary btn-lg px-5 py-3 fs-5 shadow-lg">
                            <i class="bi bi-play-circle me-2"></i>Start Battle
                        </a>
                        <a href="register.php" class="btn btn-outline-primary btn-lg px-5 py-3 fs-5 shadow-lg">
                            <i class="bi bi-person-plus me-2"></i>Join League
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>