<?php
session_start();
include('config/config.php');

// Set timezone to Asia/Kuala_Lumpur
date_default_timezone_set('Asia/Kuala_Lumpur');
$current_date = date('Y-m-d');

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

// Function to get the average rating and total number of votes for a movie
function getRatingDetails($movie_id, $link) {
    $query = "SELECT COUNT(rating) as total_votes, AVG(rating) as avg_rating FROM comment WHERE movie_id = ?";
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $movie_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $total_votes, $avg_rating);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return ['total_votes' => $total_votes, 'avg_rating' => round($avg_rating / 10, 1)]; // Convert rating scale (0-100) to (0-10) and round to one decimal place
    } else {
        return ['total_votes' => 0, 'avg_rating' => 0];
    }
}

// Function to get the time difference in a human-readable format
function getTimeDifference($time) {
    $current_time = new DateTime();
    $comment_time = new DateTime($time);
    $interval = $current_time->diff($comment_time);

    if ($interval->y > 0) {
        return $interval->y . ' Year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' Month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' Day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' Hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' Minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return $interval->s . ' Second' . ($interval->s > 1 ? 's' : '') . ' ago';
    }
}

// Check if the movie_id is set in the URL
if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];

    // Prepare a statement to prevent SQL injection
    $stmt = $link->prepare("SELECT * FROM movie_detail WHERE movie_id = ?");
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a movie was found
    if ($result->num_rows > 0) {
        $movie_detail = $result->fetch_assoc();
    } else {
        echo "No movie found with the provided ID.";
        exit;
    }

    // Fetch the latest 10 comments for the movie
    $comments_stmt = $link->prepare("SELECT c.comment_id, c.user_id, c.user_comment, c.time, c.rating, u.username, u.profile_picture FROM comment c JOIN user u ON c.user_id = u.user_id WHERE c.movie_id = ? ORDER BY c.time DESC LIMIT 10");
    $comments_stmt->bind_param("i", $movie_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();
} else {
    echo "No movie ID provided.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $user_comment = htmlspecialchars($_POST['user_comment']);
    $movie_id = intval($_POST['movie_id']); // Ensure movie_id is an integer
    $user_rating = intval($_POST['user_rating']); // Ensure user_rating is an integer

    // Validate user_comment, user_rating, and movie_id (ensure movie_id exists in movie_detail table)
    if (empty($user_comment) || empty($movie_id) || $user_rating <= 0) {
        echo "Please fill in all fields.";
        exit;
    }

    // Insert the comment into the database
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login

    // Prepare the SQL statement
    $stmt = $link->prepare("INSERT INTO comment (user_id, movie_id, user_comment, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $user_id, $movie_id, $user_comment, $user_rating);

    // Execute the statement
    if ($stmt->execute()) {
        // Comment inserted successfully
        if (isset($movie_id)) {
            header("Location: movie-details.php?movie_id={$movie_id}");
            exit;
        } else {
            echo "Error: movie_id is not set.";
            // Handle this error case appropriately
        }
    } else {
        // Error occurred
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
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
    <title>CINE EASE - Movie Details</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

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
<style>
    input {
        display: none;
    }
    .timings {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 40px;
        margin-bottom: 20px;
    }
    .dates {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dates-item {
        width: 50px;
        height: 40px;
        background: rgb(233, 233, 233);
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 25px 0px;
        border-radius: 3mm;
        cursor: pointer;
    }
    .day {
        font-size: 18px;
    }
    .times {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
    }
    .time {
        font-size: 18px;
        width: fit-content;
        padding: 7px 14px;
        background: rgb(233, 233, 233);
        border-radius: 3mm;
        cursor: pointer;
    }
    .timings input:checked + label {
        background: rgb(28, 185, 120);
        color: white;
    }
    .test {
        color : white;
    }
    .rating .fa {
        cursor: pointer;
        font-size: 20px; /* Adjust as needed */
    }
    .checked1 {
        color: orange;
    }
    .unchecked1 {
        color: white;
    }
</style>
<body>

    <!-- Header Section Begin -->
    <?php include('inc/header.php'); ?>
    <!-- Header End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php
                        // Displaying movies with details and comment counts
                        mysqli_data_seek($result, 0); // Reset result pointer

                        while ($row = mysqli_fetch_assoc($result)) :
                        $movie_id = $row['movie_id'];
                        $title = htmlspecialchars($row['title']);
                        $description = htmlspecialchars($row['description']);
                        $genre = htmlspecialchars($row['genre']);
                        $date_start_air = htmlspecialchars($row['date_start_air']);
                        $date_end_air = htmlspecialchars($row['date_end_air']);
                        $price = htmlspecialchars($row['price']);
                        $hour = htmlspecialchars($row['hour']);
                        $minutes = htmlspecialchars($row['minutes']);
                        $img_link = htmlspecialchars($row['img_link']);
                        $comment_count = getTotalComments($movie_id, $link);
                    ?>
                    <div class="breadcrumb__links">
                        <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        <span><?php echo htmlspecialchars($row['title']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Anime Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <div class="anime__details__content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="anime__details__pic set-bg" data-setbg="admin/<?php echo htmlspecialchars($row['img_link']); ?>">
                            <div class="comment"><i class="fa fa-comments"></i><?php echo $comment_count; ?></div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="anime__details__text">
                            <div class="anime__details__title">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <span></span>
                            </div>
                            <?php 
                                // Get rating details for the movie
                                $rating_details = getRatingDetails($movie_id, $link);
                                $total_votes = $rating_details['total_votes'];
                                $avg_rating = $rating_details['avg_rating'];
                            ?>
                            <!-- rating start -->
                            <div class="anime__details__rating">
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($avg_rating >= $i): ?>
                                            <a href="#"><i class="fa fa-star"></i></a>
                                        <?php elseif ($avg_rating >= $i - 0.5): ?>
                                            <a href="#"><i class="fa fa-star-half-o"></i></a>
                                        <?php else: ?>
                                            <a href="#"><i class="fa fa-star-o"></i></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <span><?php echo number_format($total_votes); ?> Votes</span>
                            </div>
                            <!-- rating end -->
                            <!-- rating start 
                            <div class="anime__details__rating">
                                <div class="rating">
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star-half-o"></i></a>
                                </div>
                                <span>1.029 Votes</span>
                            </div>
                            rating end -->
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="anime__details__widget">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Director :</span> <?php echo htmlspecialchars($row['director']); ?></li>
                                            <li><span>Writers :</span> <?php echo htmlspecialchars($row['writers']); ?></li>
                                            <li><span>Stars :</span> <?php echo htmlspecialchars($row['stars']); ?></li>
                                            <li><span>Duration :</span> <?php echo htmlspecialchars($row['hour']) . "h " . htmlspecialchars($row['minutes']) . "m"; ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li>
                                                <span>Date Aired :</span> 
                                                <?php 
                                                $date_start_air = new DateTime($row['date_start_air']);
                                                $date_end_air = new DateTime($row['date_end_air']);                
                                                echo htmlspecialchars($date_start_air->format('d/m/Y')) . " To " . htmlspecialchars($date_end_air->format('d/m/Y')); 
                                                ?>
                                            </li>
                                            <li><span>Genre :</span> <?php echo htmlspecialchars($row['genre']); ?></li>
                                            <li><span>Price :</span>RM <?php echo htmlspecialchars($row['price']); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                $date_start_air = new DateTime($movie_detail['date_start_air']);
                                $date_end_air = new DateTime($movie_detail['date_end_air']);
                                
                                // Display booking form only if date_start_air is today
                                if ($current_date >= $date_start_air->format('Y-m-d') && $current_date <= $date_end_air->format('Y-m-d')) {
                            ?>
                            <div class="section-title">
                                <h4>Please choose what time do you want to book.</h4>
                            </div>
                            <!-- booking time start -->
                            <div class="timings">
                                <form id="bookingForm" action="book_movie.php" method="GET">
                                    <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                                    <div class="dates">
                                        <?php
                                            // Set the default timezone
                                            date_default_timezone_set('Asia/Kuala_Lumpur');

                                            // Define start and end dates for booking availability
                                            $date_start_air = date('Y-m-d');  // Today's date
                                            $date_end_air = date('Y-m-d', strtotime('+6 day'));  // One week from today

                                            // Generate dates to display (current date to one week ahead)
                                            $currentDate = date('Y-m-d');
                                            $endDate = date('Y-m-d', strtotime('+6 day'));

                                            $date = $currentDate;
                                            $i = 1;
                                            while ($date <= $endDate) {
                                                // Display each date within the range
                                                echo '<input type="radio" id="d' . $i . '" name="date" value="' . $date . '" required />';
                                                echo '<label class="dates-item" for="d' . $i . '">';
                                                echo '<div class="day">' . date('D', strtotime($date)) . '</div>';
                                                echo '<div class="date">' . date('d', strtotime($date)) . '</div>';
                                                echo '</label>';
                                                $date = date('Y-m-d', strtotime($date . ' +1 day'));
                                                $i++;
                                            }
                                        ?>
                                    </div>
                                    <div class="times">
                                        <?php
                                        // Calculate total movie length in minutes
                                        $total_minutes = ($hour * 60) + $minutes;

                                        // Round up to the nearest 30 minutes
                                        $rounded_minutes = ceil($total_minutes / 30) * 30;

                                        // Add a 30-minute buffer for cleanup and preparation
                                        $total_length_with_buffer = $rounded_minutes + 30;

                                        // Generate time slots from 10 AM to 11 PM
                                        $start_time = new DateTime('10:00');
                                        $end_time = new DateTime('23:00');
                                        $current_time = clone $start_time;
                                        $time_slots = [];

                                        while ($current_time < $end_time) {
                                            $time_slots[] = $current_time->format('H:i');
                                            $current_time->modify('+' . $total_length_with_buffer . ' minutes');
                                        }

                                        // Display the time slots
                                        foreach ($time_slots as $index => $time) {
                                            $id = 't' . ($index + 1);
                                            echo '<input type="radio" name="time" id="' . $id . '" value="' . $time . '" ' . ($index === 0 ? 'checked' : '') . ' required />';
                                            echo '<label for="' . $id . '" class="time">' . $time . '</label>';
                                        }
                                        ?>
                                    </div>
                                </form>
                            </div>
                            <div class="anime__details__btn">
                                <a href="javascript:void(0);" onclick="document.getElementById('bookingForm').submit();" class="watch-btn">
                                    <span>Book Now</span> <i class="fa fa-angle-right"></i>
                                </a>
                            </div>'
                            <?php } else { ?>
                            <div class="section-title">
                                <h4>Ticket Has Not Been Release Yet</h4>
                            </div>
                            <?php } ?>
                            <!-- booking time end -->
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-lg-12 col-md-8">
                        <div class="anime__details__review">
                            <div class="section-title">
                                <h5>Reviews</h5>
                            </div>
                            <?php while ($comment_row = $comments_result->fetch_assoc()) : ?>
                                <div class="anime__review__item">
                                    <div class="anime__review__item__pic">
                                        <?php if (!empty($comment_row['profile_picture'])): ?>
                                            <img src="admin/<?php echo htmlspecialchars($comment_row['profile_picture']); ?>" alt="">
                                        <?php else: ?>
                                            <img src="" alt="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="anime__review__item__text">
                                        <h6><?php echo htmlspecialchars($comment_row['username']); ?> - <span><?php echo getTimeDifference($comment_row['time']); ?></span></h6>
                                        <div class="rating">
                                            <?php 
                                            $rating = $comment_row['rating'] / 10; // Convert rating value to star count (e.g., 40 becomes 4)
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<span class="fa fa-star checked1"></span>';
                                                } else {
                                                    echo '<span class="fa fa-star unchecked1"></span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <p><?php echo htmlspecialchars($comment_row['user_comment']); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <?php if (isset($_SESSION['username'])): ?>
                            <div class="anime__details__form">
                                <div class="section-title">
                                    <h5>Your Comment</h5>
                                </div>
                                <form action="movie-details.php?movie_id=<?php echo $movie_id; ?>" method="POST" id="review-form">
                                    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>"> <!-- Include movie_id -->
                                    <input type="hidden" name="user_rating" id="user_rating" value="0"> <!-- Hidden field to store the rating value -->
                                    <div class="rating">
                                        <span class="fa fa-star unchecked1" data-value="10"></span>
                                        <span class="fa fa-star unchecked1" data-value="20"></span>
                                        <span class="fa fa-star unchecked1" data-value="30"></span>
                                        <span class="fa fa-star unchecked1" data-value="40"></span>
                                        <span class="fa fa-star unchecked1" data-value="50"></span>
                                    </div>
                                    <br>
                                    <textarea name="user_comment" placeholder="Your Comment" required></textarea>
                                    <button type="submit"><i class="fa fa-location-arrow"></i> Review</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Anime Section End -->

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to handle star click
        document.querySelectorAll('.rating .fa').forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                document.getElementById('user_rating').value = value;

                // Remove 'checked1' class from all stars and add 'unchecked1' class
                document.querySelectorAll('.rating .fa').forEach(s => s.classList.remove('checked1'));
                document.querySelectorAll('.rating .fa').forEach(s => s.classList.add('unchecked1'));

                // Add 'checked1' class to selected stars and remove 'unchecked1' class
                this.classList.add('checked1');
                this.classList.remove('unchecked1');

                let sibling = this.previousElementSibling;
                while (sibling) {
                    sibling.classList.add('checked1');
                    sibling.classList.remove('unchecked1');
                    sibling = sibling.previousElementSibling;
                }

                sibling = this.nextElementSibling;
                while (sibling) {
                    sibling.classList.remove('checked1');
                    sibling.classList.add('unchecked1');
                    sibling = sibling.nextElementSibling;
                }
            });
        });
    </script>

</body>

</html>
