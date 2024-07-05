<?php
    session_start();
    include('config/config.php');

    // Fetch booking details along with movie title
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in the session
    $query = "
        SELECT b.booking_id, b.book_date, b.book_time, b.total_amount, m.title
        FROM booking b
        JOIN movie_detail m ON b.movie_id = m.movie_id
        WHERE b.user_id = ?
        ORDER BY b.book_date DESC
    ";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
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
                        <h2>Purchase History</h2>
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
                            <h4>Purchase History</h4>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-striped table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Movie Name</th>
                                    <th scope="col">Book Date</th>
                                    <th scope="col">Book Time</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                if ($result) {
                                    while ($booking = $result->fetch_assoc()) { 
                                        $title = htmlspecialchars($booking['title']);
                                        $book_date = htmlspecialchars($booking['book_date']);
                                        $book_time = htmlspecialchars($booking['book_time']);
                                        $total_amount = htmlspecialchars($booking['total_amount']);
                                        $booking_id = htmlspecialchars($booking['booking_id']);
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $i++; ?></th>
                                            <td><?php echo $title; ?></td>
                                            <td><?php echo $book_date; ?></td>
                                            <td><?php echo $book_time; ?></td>
                                            <td><?php echo $total_amount; ?></td>
                                            <td><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='receipt.php?booking_id=" . $booking_id . "' class='text-info content-link'><i class='far fa-file'></i></a>"; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan='7'>No records found.</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
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
