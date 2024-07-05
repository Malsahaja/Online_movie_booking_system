<?php
session_start();
include('../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

// Function to validate price
function validatePrice($price) {
    // Check if price is a valid decimal number
    if (!is_numeric($price)) {
        return false;
    }

    return true;
}

// Define variables and initialize with empty values
$title = $description = $director = $writers = $stars = $date_start_air = $date_end_air = $genre = $hour = $minutes = $price = "";
$title_err = "";
$msg = "";

// Check if movie_id parameter is passed
if (isset($_GET['movie_id']) || isset($_POST['movie_id'])) {
    $movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : $_POST['movie_id'];

    // If it's a GET request, fetch the movie details
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['movie_id'])) {
        // Prepare a statement to fetch movie details
        $stmt = $link->prepare("SELECT * FROM movie_detail WHERE movie_id = ?");
        $stmt->bind_param("i", $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Check if movie details are found
        if ($result->num_rows > 0) {
            $movie_detail = $result->fetch_assoc();
            // Assign fetched values to variables
            $title = $movie_detail['title'];
            $description = $movie_detail['description'];
            $director = $movie_detail['director'];
            $writers = $movie_detail['writers'];
            $stars = $movie_detail['stars'];
            $date_start_air = $movie_detail['date_start_air'];
            $date_end_air = $movie_detail['date_end_air'];
            $genre = $movie_detail['genre'];
            $hour = $movie_detail['hour'];
            $minutes = $movie_detail['minutes'];
            $img_link = $movie_detail['img_link'];
            $price = $movie_detail['price'];
        } else {
            echo "No movie found with the provided ID.";
            exit;
        }
    }
} else {
    echo "No movie ID provided.";
    exit;
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Get genres
    if (isset($_POST['genres'])) {
        $genre = implode(", ", $_POST['genres']);
    } else {
        $genre = "";
    }

    // Validate price
    if (!validatePrice($_POST["price"])) {
        $msg = "Invalid price format. Please enter a valid numeric value.";
    } else {
        $price = $_POST["price"];
    }

    // Handle image upload
    /*if (isset($_FILES['choosefile']) && $_FILES['choosefile']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "img/"; // Ensure this directory exists
        $target_file = $target_dir . basename($_FILES["choosefile"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["choosefile"]["tmp_name"]);
        if ($check !== false) {
            // Allow certain file formats
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["choosefile"]["tmp_name"], $target_file)) {
                    $img_link = $target_file;
                } else {
                    $msg = "Sorry, there was an error uploading your file.";
                }
            } else {
                $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            $msg = "File is not an image.";
        }
    } else {
        $img_link = $movie_detail['img_link']; // Use the existing image if no new image is uploaded
    }*/

    // Check input errors before updating in database
    if (empty($title_err) && empty($msg)) {
        // Prepare an update statement
        $sql = "UPDATE movie_detail SET title=?, description=?, director=?, writers=?, stars=?, date_start_air=?, date_end_air=?, genre=?, hour=?, minutes=?, price=? WHERE movie_id=?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssssi", $param_title, $param_description, $param_director, $param_writers, $param_stars, $param_date_start_air, $param_date_end_air, $param_genre, $param_hour, $param_minutes, $param_price, $param_movie_id);
            
            // Set parameters
            $param_title = $title;
            $param_description = trim($_POST["description"]);
            $param_director = trim($_POST["director"]);
            $param_writers = trim($_POST["writers"]);
            $param_stars = trim($_POST["stars"]);
            $param_date_start_air = trim($_POST["date_start_air"]);
            $param_date_end_air = trim($_POST["date_end_air"]);
            $param_genre = $genre;
            $param_hour = trim($_POST["hour"]);
            $param_minutes = trim($_POST["minutes"]);
            $param_price = $price;
            $param_movie_id = $movie_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to view_movie_detail.php
                header("location: view_movie_detail.php?movie_id=" . $movie_id);
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CINE EASE - Edit Movie</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <?php include('inc/sidebar.php'); ?>
        <div class="content">
            <?php include('inc/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">Edit Movie</h6>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>">
                                        <span class="text-danger"><?php echo $title_err;?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea name="description" class="form-control"><?php echo htmlspecialchars($description); ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="director" class="col-sm-2 col-form-label">Director</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="director" class="form-control" value="<?php echo htmlspecialchars($director); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="writers" class="col-sm-2 col-form-label">Writers</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="writers" class="form-control" value="<?php echo htmlspecialchars($writers); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="stars" class="col-sm-2 col-form-label">Stars</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="stars" class="form-control" value="<?php echo htmlspecialchars($stars); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="date_start_air" class="col-sm-2 col-form-label">Date Start Air</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="date_start_air" class="form-control" value="<?php echo htmlspecialchars($date_start_air); ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="date_end_air" class="col-sm-2 col-form-label">Date End Air</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="date_end_air" class="form-control" value="<?php echo $date_end_air; ?>">
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Genre</legend>
                                    <div class="col-sm-10">
                                        <?php
                                        $genres = [
                                            'Action', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Mystery', 'Romance',
                                            'Thriller', 'Western', 'Science Fiction', 'Animation', 'Adventure',
                                            'Documentary', 'Family', 'Musical', 'Crime', 'Biography', 'History',
                                            'War', 'Sport'
                                        ];
                                        $selected_genres = explode(", ", $genre);
                                        foreach($genres as $genre_option) {
                                            $checked = in_array($genre_option, $selected_genres) ? 'checked' : '';
                                            echo "<div class='form-check form-check-inline'>";
                                            echo "<input class='form-check-input' type='checkbox' name='genres[]' id='$genre_option' value='$genre_option' $checked>";
                                            echo "<label class='form-check-label' for='$genre_option'>$genre_option</label>";
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </fieldset>
                                <div class="row mb-3">
                                    <label for="duration" class="col-sm-2 col-form-label">Duration</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <input type="text" name="hour" class="form-control" value="<?php echo $hour; ?>" placeholder="Hour">
                                            <span class="input-group-text">Hour</span>
                                            <input type="text" name="minutes" class="form-control" value="<?php echo $minutes; ?>" placeholder="Minutes">
                                            <span class="input-group-text">Minutes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="duration" class="col-sm-2 col-form-label">Ticket Price</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                        <span class="input-group-text">RM</span>
                                            <input type="number" name="price" class="form-control" value="<?php echo $price; ?>" placeholder="Movie Price">
                                        </div>
                                    </div>
                                </div>
                                <!--<?php /* ?>
                                <div class="row mb-3">
                                    <label for="choosefile" class="col-sm-2 col-form-label">Movie Poster</label>
                                    <div class="col-sm-10">
                                        <?php if(!empty($movie_detail['img_link'])): ?>
                                            <div class="anime__details__pic set-bg" style="background-image: url('<?php echo htmlspecialchars($movie_detail['img_link']); ?>');"></div>
                                        <?php endif; ?>
                                        <input class="form-control bg-dark" type="file" name="choosefile">
                                        <span class="text-danger"><?php echo $msg; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="choosefile" class="col-sm-2 col-form-label">Movie Banner</label>
                                    <div class="col-sm-10">
                                        <?php if(!empty($movie_detail['img_banner'])): ?>
                                            <div class="anime__details__pic set-bg" style="background-image: url('<?php echo htmlspecialchars($movie_detail['img_banner']); ?>');"></div>
                                        <?php endif; ?>
                                        <input class="form-control bg-dark" type="file" name="choosefile">
                                        <span class="text-danger"><?php echo $msg; ?></span>
                                    </div>
                                </div>
                                <?php */?>-->
                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('inc/footer.php'); ?>
        </div>
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
