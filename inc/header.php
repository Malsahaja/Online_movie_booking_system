
<!-- Header Section Begin -->
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="header__logo">
                    <a href="index.php">
                        <img src="img/logo/CineEaseLatest.PNG" alt="" width="500" height="20">
                    </a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="header__nav">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="index.php">Homepage</a></li>
                            <li><a>Categories <span class="arrow_carrot-down"></span></a>
                                <ul class="dropdown">
                                    <li><a href="now_showing.php">Now Showing</a></li>
                                    <li><a href="recently.php">Recently Added Shows</a></li>
                                </ul>
                            </li>
                            <li><a href="contact_us.php">Contact Us</a></li>
                            <li><a href="about_us.php">About Us</a></li>
                            <li><a href="team_member.php">Team Member</a></li>
                            <?php if (isset($_SESSION['username'])): ?>
                                <li><a><?php echo $_SESSION['username']; ?><span class="arrow_carrot-down"></span></a>
                                    <ul class="dropdown">
                                        <?php if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2): ?>
                                            <li><a href="admin/index.php">Admin Page</a></li>
                                        <?php endif; ?>
                                        <li><a href="purchase_history.php">Purchase History</a></li>
                                        <li><a href="Logout.php">Logout</a></li>
                                        <?php else: ?>
                                        <li><a href="login.php"><span class="icon_profile"></span></a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
</header>
<!-- Header End -->