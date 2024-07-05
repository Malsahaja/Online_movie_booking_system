<!-- Sidebar Start -->
<div class="sidebar pe-2 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index.php" class="navbar-brand mx--0 mb-3">
                    <img src="../img/logo/CineEaseLatest.PNG" width="200" height="20">
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <!--<div class="position-relative">
                        <img class="rounded-circle" src="" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>-->
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $_SESSION['username']; ?></h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <!--<div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Elements</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="button.php" class="dropdown-item">Buttons</a>
                            <a href="typography.php" class="dropdown-item">Typography</a>
                            <a href="element.php" class="dropdown-item">Other Elements</a>
                        </div>
                    </div>-->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user me-2"></i>Team Member</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="create_member.php" class="dropdown-item">Create an Team Member</a>
                            <a href="view_member.php" class="dropdown-item">View Team Member</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-users me-2"></i>Account</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="create_account.php" class="dropdown-item">Create an Account</a>
                            <a href="view_account.php" class="dropdown-item">View Account</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-film me-2"></i>Movie</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="insert_new_movie.php" class="dropdown-item">Enter An new Movie</a>
                            <a href="view_movie.php" class="dropdown-item">View Movie</a>
                        </div>
                    </div>
                    <!--<div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-map-marker me-2"></i>Cinema Location</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="insert_new_location.php" class="dropdown-item">Add New Location</a>
                            <a href="view_location.php" class="dropdown-item">View Location</a>
                        </div>
                    </div>-->
                    <a href="edit_about_us.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>About Us</a>
                    <!--<a href="widget.php" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Widgets</a>
                    <a href="form.php" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>
                    <a href="table.php" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tables</a>
                    <a href="chart.php" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="signin.php" class="dropdown-item">Sign In</a>
                            <a href="signup.php" class="dropdown-item">Sign Up</a>
                            <a href="404.php" class="dropdown-item">404 Error</a>
                            <a href="blank.php" class="dropdown-item">Blank Page</a>
                        </div>
                    </div>-->
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->