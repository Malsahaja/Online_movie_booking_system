<!-- Spinner Start 
<div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
 Spinner End -->


<!-- Navbar Start -->
<nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
    <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>

    <!--<form class="d-none d-md-flex ms-4">
        <input class="form-control bg-dark border-0" type="search" placeholder="Search">
    </form>-->
    
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item dropdown">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="d-none d-lg-inline-flex"><?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                    <a href="../index.php" class="dropdown-item">Back to User Page</a>
                    <!--<a href="#" class="dropdown-item">Settings</a>-->
                    <a href="../logout.php" class="dropdown-item">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
<!-- Navbar End -->