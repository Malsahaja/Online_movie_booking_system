<?php
include('config/config.php');

// Initialize variables
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Validate new password
        if (empty(trim($_POST["new_password"]))) {
            $new_password_err = "Please enter a new password.";
        } elseif (strlen(trim($_POST["new_password"])) < 8) {
            $new_password_err = "Password must have at least 8 characters.";
        } else {
            $new_password = trim($_POST["new_password"]);
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm the password.";
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($new_password_err) && ($new_password != $confirm_password)) {
                $confirm_password_err = "Passwords do not match.";
            }
        }

        // Check input errors before updating the database
        if (empty($new_password_err) && empty($confirm_password_err)) {
            $sql = "UPDATE user SET password = ? WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_username = $username;

                if (mysqli_stmt_execute($stmt)) {
                    // Password updated successfully. Redirect to login page
                    header("location: login.php");
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Close connection
        mysqli_close($link);
    }
} else {
    // Redirect to forget password page if no username is set
    header("location: forget_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CINE EASE</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <!-- Page Preloader -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <?php include('inc/header.php'); ?>
    <!-- Header End -->

    <!-- Normal Breadcrumb Begin -->
    <section class="movie-theater set-bg" data-setbg="img/movie-theater.avif">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Reset Password</h2>
                        <p>Welcome to the CineEase Movie Ticket Booking System.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Reset Password Section Begin -->
    <section class="signup spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login__form">
                        <h3>Reset Password</h3>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username=" . urlencode($username)); ?>">
                            <div class="input__item">
                                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($new_password); ?>" placeholder="New Password">
                                <span class="icon_lock"></span>
                                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($confirm_password); ?>" placeholder="Confirm Password">
                                <span class="icon_lock"></span>
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>
                            <button type="submit" class="site-btn">Update Password</button>
                        </form>
                        <h5>Remembered your password? <a href="login.php">Log In!</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Reset Password Section End -->

    <!-- Footer Section Begin -->
    <?php include('inc/footer.php'); ?>
    <!-- Footer Section End -->

    <!-- Search model Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch"><i class="icon_close"></i></div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>
</html>
