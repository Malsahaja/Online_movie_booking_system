<?php
include('config/config.php');

// Initialize variables
$username = $fullname = $phone_no = $email = $role = $password = $confirm_password = "";
$username_err = $fullname_err = $phone_no_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate fullname
    if (empty(trim($_POST["fullname"]))) {
        $fullname_err = "Please enter your fullname.";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', trim($_POST["fullname"]))) {
        $fullname_err = "Fullname can only contain letters and spaces.";
    } else {
        $sql = "SELECT user_id FROM user WHERE fullname = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_fullname);
            $param_fullname = trim($_POST["fullname"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $fullname_err = "The fullname has already been registered.";
                } else {
                    $fullname = trim($_POST["fullname"]);
                }
            } else {
                echo "Ouch! There's something wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        $sql = "SELECT user_id FROM user WHERE username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "The username has already been registered.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Ouch! There's something wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate phone number
    if (empty(trim($_POST["phone_no"]))) {
        $phone_no_err = "Please enter your phone number.";
    } elseif (!preg_match('/^[0-9]+$/', trim($_POST["phone_no"]))) {
        $phone_no_err = "Phone number can only contain numbers.";
    } else {
        $sql = "SELECT user_id FROM user WHERE phone_no = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_phone_no);
            $param_phone_no = trim($_POST["phone_no"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $phone_no_err = "The phone number has already been registered.";
                } else {
                    $phone_no = trim($_POST["phone_no"]);
                }
            } else {
                echo "Ouch! There's something wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $sql = "SELECT user_id FROM user WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email"]);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "The email has already been registered.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Ouch! There's something wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Capture role
    if (isset($_POST["role"])) {
        $role = $_POST["role"];
    } else {
        $role = 3; // Default to User role if not set
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must have at least 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Check input errors before inserting into database
    if (empty($fullname_err) && empty($username_err) && empty($phone_no_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO user (fullname, username, phone_no, email, role, password) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_fullname, $param_username, $param_phone_no, $param_email, $param_role, $param_password);
            $param_fullname = $fullname;
            $param_username = $username;
            $param_phone_no = $phone_no;
            $param_email = $email;
            $param_role = $role;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "Ouch! There's something wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
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
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

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
    <!-- Page Preloder -->
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
                        <h2>Sign Up</h2>
                        <p>Welcome to the CineEase Movie Ticket Booking System.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Signup Section Begin -->
    <section class="signup spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login__form">
                        <h3>Sign Up</h3>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="input__item">
                                <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($fullname); ?>" placeholder="Fullname">
                                <span class="icon_profile"></span>
                                <span class="invalid-feedback"><?php echo $fullname_err; ?></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>" placeholder="Username">
                                <span class="icon_profile"></span>
                                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="phone_no" class="form-control <?php echo (!empty($phone_no_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($phone_no); ?>" placeholder="Phone Number">
                                <span class="icon_phone"></span>
                                <span class="invalid-feedback"><?php echo $phone_no_err; ?></span>
                            </div>
                            <div class="input__item">
                                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>" placeholder="Email Address">
                                <span class="icon_mail"></span>
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Password">
                                <span class="icon_lock"></span>
                                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" placeholder="Confirm Password">
                                <span class="icon_lock"></span>
                                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            </div>
                            <button type="submit" class="site-btn">Register</button>
                        </form>
                        <h5>Already have an account? <a href="login.php">Log In!</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Signup Section End -->

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