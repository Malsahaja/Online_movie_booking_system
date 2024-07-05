<?php
session_start();
include('config/config.php');

// Set the default timezone to Kuala Lumpur
date_default_timezone_set("Asia/Kuala_Lumpur");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if the required parameters are set
if (isset($_GET['movie_id'], $_GET['date'], $_GET['time'])) {
    $movie_id = intval($_GET['movie_id']);
    $date = htmlspecialchars($_GET['date']);
    $time = htmlspecialchars($_GET['time']);

    // Validate the date and time (optional but recommended)
    $dateTime = DateTime::createFromFormat('Y-m-d H:i', "$date $time");
    if (!$dateTime) {
        echo "Invalid date or time format.";
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $link->prepare("SELECT * FROM movie_detail WHERE movie_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Fetch the movie details
            $row = $result->fetch_assoc();
            $movie_id = htmlspecialchars($row['movie_id']);
            $title = htmlspecialchars($row['title']);
            $date_start_air = htmlspecialchars($row['date_start_air']);
            $date_end_air = htmlspecialchars($row['date_end_air']);
            $price = htmlspecialchars($row['price']);
            $hour = htmlspecialchars($row['hour']);
            $minutes = htmlspecialchars($row['minutes']);
        } else {
            // Handle case where movie_id is not found
            echo "Movie not found.";
            $stmt->close();
            exit();
        }
        $stmt->close();
    } else {
        echo "Database error: Unable to prepare statement.";
        exit();
    }

    // Ensure $user_id is set, assuming it comes from session or another source
    if (isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);

        $stmt1 = $link->prepare("SELECT * FROM user WHERE user_id = ?");
        if ($stmt1) {
            $stmt1->bind_param("i", $user_id);
            $stmt1->execute();
            $result1 = $stmt1->get_result();

            if ($result1 && $result1->num_rows > 0) {
                // Fetch the user details
                $row1 = $result1->fetch_assoc();
                $user_id = htmlspecialchars($row1['user_id']);
                $username = htmlspecialchars($row1['username']);
                $phone_no = htmlspecialchars($row1['phone_no']); // Corrected field name
                $email = htmlspecialchars($row1['email']);
            } else {
                // Handle case where user_id is not found
                echo "User not found.";
                $stmt1->close();
                exit();
            }
            $stmt1->close();
        } else {
            echo "Database error: Unable to prepare statement.";
            exit();
        }
    } else {
        // Handle case where user_id is not set in the session
        echo "User not logged in.";
        exit();
    }
} else {
    // Handle case where movie_id, date, or time is not passed in the URL
    echo "Required parameters missing.";
    exit();
}

// Handling the booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the payment details from the form
    $card_name = htmlspecialchars($_POST['card_name']);
    $card_number = htmlspecialchars($_POST['card_number']);
    $ex_date = htmlspecialchars($_POST['ex_date']);
    $cvv = intval($_POST['cvv']);
    $total_amount = $price; // Assuming the total amount is the movie price
    $payment_date = date('Y-m-d H:i:s'); // Current timestamp

    // Insert booking data into the database
    $stmt2 = $link->prepare("INSERT INTO booking (user_id, movie_id, book_date, book_time, payment_date, total_amount, card_name, card_number, ex_date, cvv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt2) {
        $stmt2->bind_param("iisssdsssi", $user_id, $movie_id, $date, $time, $payment_date, $total_amount, $card_name, $card_number, $ex_date, $cvv);
        if ($stmt2->execute()) {
            // Redirect to index.php upon successful insertion
            $stmt2->close();
            header('Location: index.php');
            exit(); // Make sure to exit after redirection to prevent further execution
        } else {
            echo "Error storing booking: " . $stmt2->error;
        }
        $stmt2->close();
    } else {
        echo "Database error: Unable to prepare statement.";
    }

    // Close connection
    $link->close();
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
    <style>
        .front {
            margin: 5px 4px 45px 0;
            background-color: #EDF979;
            color: #000000;

            padding: 9px 0;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <!-- Page Preloader 
    <div id="preloder">
        <div class="loader"></div>
    </div>-->

    <!-- Header Section Begin -->
    <?php include('inc/header.php'); ?>
    <!-- Header End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="movie-details.php?movie_id=<?php echo $movie_id; ?>">
                            <?php echo htmlspecialchars($title); ?>
                        </a>
                        <span>Booking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    Username: <span id="username"><?php echo $username; ?></span><br>
                                    Phone no.: <span id="mobile"><?php echo $phone_no; ?></span><br>
                                    Movie Name: <span id="movie_name"><?php echo $title; ?></span><br>
                                    Payment Date: <span id="payment_date"><?php echo date("D-m-y ", strtotime('today')); ?></span>
                                </div>
                                <div class="col-lg-6">
                                    Email: <span id="email"><?php echo $email; ?></span><br>
                                    Price: <span id="show_time"><?php echo $price; ?></span><br>
                                    Movie Time: <span id="show_time"><?php echo $time; ?></span><br>
                                    Booking Date: <span id="booking_date"><?php echo date('d-m-y', strtotime($date)); ?></span>
                                </div>
                            </div>
                            <form method="POST" action="">
                                <!-- Ensure that action is set correctly with parameters -->
                                <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <input type="hidden" name="time" value="<?php echo $time; ?>">
                                <div id="credit-card" class="tab-pane fade show active pt-3">
                                    <div class="form-group">
                                        <label for="card_name">
                                            <h6>Card Owner</h6>
                                        </label>
                                        <input type="text" id="card_name" name="card_name" placeholder="Card Owner Name" class="form-control" required>
                                        <div id="validatecardname"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="card_number">
                                            <h6>Card number</h6>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" id="card_number" name="card_number" placeholder="Valid card number" class="form-control" required>
                                        </div>
                                        <div id="validatecardnumber"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>
                                                    <h6>Expiration Date</h6>
                                                </label>
                                                <div class="input-group">
                                                    <input type="date" id="ex_date" name="ex_date" placeholder="MM" class="form-control" required>
                                                </div>
                                                <div id="validateexdate"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-4">
                                                <label data-toggle="tooltip" title="Three digit CV code on the back of your card">
                                                    <h6>CVV</h6>
                                                </label>
                                                <input type="number" id="cvv" name="cvv" class="form-control" required>
                                                <div id="validatecvv"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="msg"></div>
                                    <div class="card-footer">
                                        <button type="submit" id="payment" class="subscribe btn btn-primary btn-block shadow-sm">Confirm Payment</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
