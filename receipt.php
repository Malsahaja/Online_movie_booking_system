<?php
session_start();
include('config/config.php');
include('phpqrcode/qrlib.php');

// Fetch user and booking details
$user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
$booking_id = $_GET['booking_id'];

$query = "
    SELECT b.book_date, b.book_time, b.total_amount, b.payment_date, m.title, u.username, u.email, u.phone_no
    FROM booking b
    JOIN movie_detail m ON b.movie_id = m.movie_id
    JOIN user u ON b.user_id = u.user_id
    WHERE b.booking_id = ?
";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
} else {
    echo "No booking details found.";
    exit;
}

// Generate QR code
$qr_content = "Booking ID: " . $booking_id . "\nMovie Title: " . $booking['title'];
$qr_filename = 'qr_codes/booking_' . $booking_id . '.png';
QRcode::png($qr_content, $qr_filename, QR_ECLEVEL_L, 3, 4);

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

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
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
                        <h2>Receipt</h2>
                        <p>Welcome to the CineEase Movie Ticket Booking System.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Login Section Begin -->
    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="section-title">
                            <h4>Your Receipt</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3"></div>
                        <div class="card col-lg-6 col-md-6 col-sm-6" id="receipt-content">
                            <div class="card-header">
                                <center>
                                    <img src="img/logo/CineEaseLatest.PNG" width="50%">
                                    <h6>Multimedia University - MMU Cyberjaya</h6>
                                </center>
                                <table>
                                    <tr>
                                        <td>1-300-80-0668</td>
                                        <td style="padding: 12px 2px 12px 155px;">Customer Id: <?php echo htmlspecialchars($user_id); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="padding: 1px 2px 1px 155px;">Date: <?php echo date('d-m-Y H:i:s', strtotime($booking['payment_date'])); ?></td>
                                    </tr>
                                </table>
                                <hr>
                                <center>
                                    <h3>Movie Name: <?php echo htmlspecialchars($booking['title']); ?></h3>
                                </center>
                                <table>
                                    <tr>
                                        <th>Name</th>
                                        <th style="padding: 1px 105px;">Phone</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                        <td style="padding: 12px 105px;"><?php echo htmlspecialchars($booking['phone_no']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <th style="padding: 1px 105px;">Payment Amount</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                        <td style="padding: 12px 105px;">RM <?php echo htmlspecialchars($booking['total_amount']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Date</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($booking['payment_date'])); ?></td>
                                    </tr>
                                </table>
                                <hr>
                                <h4>BOOKING DETAILS:</h4>
                                <table>
                                    <tr>
                                        <th>Booking Date</th>
                                        <th style="padding: 0px 2px 0px 60px;">Booking Time</th>
                                    </tr>
                                    <tr>
                                        <td style="padding-right: 150px;"><?php echo htmlspecialchars($booking['book_date']); ?></td>
                                        <td style="padding: 12px 2px 12px 60px;"><?php echo htmlspecialchars($booking['book_time']); ?></td>
                                    </tr>
                                </table>
                                <center>
                                    <img src="qr_codes/booking_<?php echo htmlspecialchars($booking_id); ?>.png" alt="QR Code">
                                </center>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3"></div>
                    </div>
                    <!-- Add Print fpdf -->
                    <div class="text-center mt-4">
                        <form method="POST" action="generate_pdf.php" target="_blank">
                            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
                            <button type="submit" class="btn btn-primary">Print PDF</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Section End -->

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
