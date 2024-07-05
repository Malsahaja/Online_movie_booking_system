<?php
session_start();
include('config/config.php');

// Set timezone to Asia/Kuala_Lumpur
date_default_timezone_set('Asia/Kuala_Lumpur');
$current_date = date('Y-m-d');

// Query to fetch movie details
$sql = "SELECT * FROM movie_detail";
$result = mysqli_query($link, $sql);

// Function to get total comments for a movie_id
function getTotalComments($movie_id, $link) {
    $query = "SELECT COUNT(*) AS user_comment FROM comment WHERE movie_id = ?";
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $movie_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_comment);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $user_comment;
    } else {
        return 0;
    }
}

$recently_added_shows = [];
$upcoming_shows = [];
$hero_section_shows = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date_start_air = $row['date_start_air'];
    $date_end_air = $row['date_end_air'];

    if ($date_start_air <= $current_date && $date_end_air >= $current_date) {
        if (count($recently_added_shows) < 9) {
            $recently_added_shows[] = $row;
        }
        if (count($hero_section_shows) < 9) {
            $hero_section_shows[] = $row;
        }
    } elseif ($date_start_air > $current_date) {
        if (count($upcoming_shows) < 9) {
            $upcoming_shows[] = $row;
        }
    }
}

// Pagination configuration
$products_per_page = 9;
$total_products = count($recently_added_shows);
$total_pages = ceil($total_products / $products_per_page);

// Determine current page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages)); // Ensure current page is within valid range

// Calculate starting index for products on current page
$start_index = ($current_page - 1) * $products_per_page;
$paginated_shows = array_slice($recently_added_shows, $start_index, $products_per_page);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CINE EASE - Popular Shows</title>

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

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        <span>Now Showing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Section Begin -->
    <section class="product-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="popular__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Now Showing</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach ($paginated_shows as $row) :
                                $movie_id = $row['movie_id'];
                                $title = htmlspecialchars($row['title']);
                                $description = htmlspecialchars($row['description']);
                                $genre = htmlspecialchars($row['genre']);
                                $img_link = htmlspecialchars($row['img_link']);
                                $comment_count = getTotalComments($movie_id, $link);
                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg" data-setbg="admin/<?php echo $img_link; ?>">
                                            <div class="comment"><i class="fa fa-comments"></i> <?php echo $comment_count; ?></div>
                                        </div>
                                        <div class="product__item__text">
                                            <ul>
                                                <?php
                                                $genres = explode(',', $genre);
                                                foreach ($genres as $g) {
                                                    echo "<li>" . htmlspecialchars(trim($g)) . "</li>";
                                                }
                                                ?>
                                            </ul>
                                            <h5><a href="movie-details.php?movie_id=<?php echo $movie_id; ?>"><span><?php echo htmlspecialchars($title); ?></span></a></h5>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <center>
                        <div class="product__pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <?php if ($i == $current_page) : ?>
                                    <a href="#" class="current-page"><?php echo $i; ?></a>
                                <?php else : ?>
                                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <a href="#"><i class="fa fa-angle-double-right"></i></a>
                        </div>
                    </center>
                    <!-- End Pagination -->
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

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
