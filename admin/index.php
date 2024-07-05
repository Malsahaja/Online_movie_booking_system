<?php
session_start();
include('../config/config.php');

// Set the default timezone to Kuala Lumpur
date_default_timezone_set("Asia/Kuala_Lumpur");

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

// Fetch sales data
$today_sales = 0;
$this_week_sales = 0;
$this_month_sales = 0;
$this_year_sales = 0;

$today = date('Y-m-d');
$this_week_start = date('Y-m-d', strtotime('monday this week'));
$this_month_start = date('Y-m-01');
$this_year_start = date('Y-01-01');

$sales_query = "
    SELECT 
        SUM(CASE WHEN DATE(payment_date) = ? THEN total_amount ELSE 0 END) AS today_sales,
        SUM(CASE WHEN DATE(payment_date) BETWEEN ? AND ? THEN total_amount ELSE 0 END) AS this_week_sales,
        SUM(CASE WHEN DATE(payment_date) BETWEEN ? AND LAST_DAY(?) THEN total_amount ELSE 0 END) AS this_month_sales,
        SUM(CASE WHEN DATE(payment_date) BETWEEN ? AND ? THEN total_amount ELSE 0 END) AS this_year_sales
    FROM booking
";

$stmt = $link->prepare($sales_query);
$stmt->bind_param(
    'sssssss', 
    $today, 
    $this_week_start, $today, 
    $this_month_start, $this_month_start,
    $this_year_start, $today
);
$stmt->execute();
$sales_result = $stmt->get_result()->fetch_assoc();

if ($sales_result) {
    $today_sales = $sales_result['today_sales'];
    $this_week_sales = $sales_result['this_week_sales'];
    $this_month_sales = $sales_result['this_month_sales'];
    $this_year_sales = $sales_result['this_year_sales'];
}

// Fetch recent sales (max 6 rows)
$recent_sales_query = "
    SELECT b.booking_id, b.book_date, b.book_time, b.total_amount, b.payment_date, u.username, m.title
    FROM booking b
    JOIN user u ON b.user_id = u.user_id
    JOIN movie_detail m ON b.movie_id = m.movie_id
    ORDER BY b.payment_date DESC
    LIMIT 6
";
$recent_sales_result = $link->query($recent_sales_query);
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
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">

        <!-- Sidebar Section Begin -->
        <?php include('inc/sidebar.php'); ?>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            
            <!-- Header Section Begin -->
            <?php include('inc/header.php'); ?>
            <!-- Header End -->

            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <!-- today sale start -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Today Sale</p>
                                <h6 class="mb-0">RM<?php echo number_format($today_sales, 2); ?></h6>
                            </div>
                        </div>
                    </div>
                    <!-- today sale end -->
                    <!-- this week sale start -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">This Week Sale</p>
                                <h6 class="mb-0">RM<?php echo number_format($this_week_sales, 2); ?></h6>
                            </div>
                        </div>
                    </div>
                    <!-- this week sale end -->
                    <!-- this month sale start -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">This Month Sale</p>
                                <h6 class="mb-0">RM<?php echo number_format($this_month_sales, 2); ?></h6>
                            </div>
                        </div>
                    </div>
                    <!-- this month sale end -->
                    <!-- this year sale start -->
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">This Year Sale</p>
                                <h6 class="mb-0">RM<?php echo number_format($this_year_sales, 2); ?></h6>
                            </div>
                        </div>
                    </div>
                    <!-- this year sale end -->
                </div>
            </div>
            <!-- Sale & Revenue End -->

            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Recent Sales</h6>
                        <a href="Booked_movie.php">Show All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th scope="col">No</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Movie</th>
                                    <th scope="col">Booking Date</th>
                                    <th scope="col">Booking Time</th>
                                    <th scope="col">Payment Date</th>
                                    <th scope="col">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $max_rows = 6; // Maximum number of rows to display

                                if ($recent_sales_result) {
                                    while ($booking = $recent_sales_result->fetch_assoc()) {
                                        if ($i > $max_rows) {
                                            break; // Exit the loop after 6 rows
                                        }

                                        $username = htmlspecialchars($booking['username']);
                                        $title = htmlspecialchars($booking['title']);
                                        $book_date = htmlspecialchars($booking['book_date']);
                                        $book_time = htmlspecialchars($booking['book_time']);
                                        $payment_date = htmlspecialchars($booking['payment_date']);
                                        $total_amount = htmlspecialchars($booking['total_amount']);
                                        ?>
                                        <tr>
                                            <td scope="row"><?php echo $i++; ?></td>
                                            <td><?php echo $username; ?></td>
                                            <td><?php echo $title; ?></td>
                                            <td><?php echo $book_date; ?></td>
                                            <td><?php echo $book_time; ?></td>
                                            <td><?php echo $payment_date; ?></td>
                                            <td>RM<?php echo number_format($total_amount, 2); ?></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan='7'>No records found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Sales End -->

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
