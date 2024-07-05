<?php
session_start();
include('../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

// Check if the movie_id is set in the URL
if(isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];

    // Prepare a statement to prevent SQL injection
    $stmt = $link->prepare("SELECT * FROM movie_detail WHERE movie_id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a movie was found
    if($result->num_rows > 0) {
        $movie_detail = $result->fetch_assoc();
    } else {
        echo "No movie found with the provided ID.";
        exit;
    }
} else {
    echo "No movie ID provided.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CINE EASE</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Additional Styles for Specific Div -->
    <style>
        .anime__details__pic {
            background-size: cover;
            background-position: center;
            width: 100%;
            height: 400px; /* Adjust height as needed */
        }
    </style>
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Section Begin -->
        <?php include('inc/sidebar.php'); ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Header Section Begin -->
            <?php include('inc/header.php'); ?>
            <!-- Header End -->

            <!-- Table Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <div class="row">
                            <div class="col-lg-12">
                                    <div class="anime__details__pic set-bg" style="background-image: url('<?php echo htmlspecialchars($movie_detail['img_banner']); ?>');">
                                        <!-- Placeholder for Accessibility -->
                                        <div class="sr-only">
                                            <?php echo htmlspecialchars($movie_detail['title']); ?>
                                        </div>
                                    </div>
                                </div>
                                <h1></h1>
                                <div class="col-lg-3">
                                    <div class="anime__details__pic set-bg" style="background-image: url('<?php echo htmlspecialchars($movie_detail['img_link']); ?>');">
                                        <!-- Placeholder for Accessibility -->
                                        <div class="sr-only">
                                            <?php echo htmlspecialchars($movie_detail['title']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="anime__details__text">
                                        <div class="anime__details__title">
                                            <h3><?php echo htmlspecialchars($movie_detail['title']); ?></h3>
                                            <span></span>
                                        </div>
                                        <p><?php echo htmlspecialchars($movie_detail['description']); ?></p>
                                        <div class="anime__details__widget">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <ul>
                                                        <li><span>Director :</span> <?php echo htmlspecialchars($movie_detail['director']); ?></li>
                                                        <li><span>Writers :</span> <?php echo htmlspecialchars($movie_detail['writers']); ?></li>
                                                        <li><span>Stars :</span> <?php echo htmlspecialchars($movie_detail['stars']); ?></li>
                                                        <li><span>Duration :</span> <?php echo htmlspecialchars($movie_detail['hour']) . "h " . htmlspecialchars($movie_detail['minutes']) . "m"; ?></li>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <ul>
                                                        <li>
                                                            <span>Date Aired :</span> 
                                                            <?php 
                                                            $date_start_air = new DateTime($movie_detail['date_start_air']);
                                                            $date_end_air = new DateTime($movie_detail['date_end_air']);
                                                            
                                                            echo htmlspecialchars($date_start_air->format('d/m/Y')) . " To " . htmlspecialchars($date_end_air->format('d/m/Y')); 
                                                            ?>
                                                        </li>
                                                        <li><span>Genre :</span> <?php echo htmlspecialchars($movie_detail['genre']); ?></li>
                                                        <li><span>Price :</span> RM <?php echo htmlspecialchars($movie_detail['price']); ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="anime__details__btn">
                                            <a href="edit_movie_detail.php?movie_id=<?php echo $movie_id; ?>" class="btn btn-primary m-2"><span>Edit Movie</span> <i class="fa fa-angle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Section Begin -->
            <?php include('inc/footer.php'); ?>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
