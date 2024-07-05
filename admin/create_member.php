<?php
session_start();
include('../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

// Initialize variables
$fullname = $description = $age = $date_of_birth = $status = $city = $negeri = $studying = $genre = $university = $student_id = "";
$fullname_err = $msg = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $fullname = ucwords(strtolower(trim($_POST["fullname"])));
    $description = trim($_POST["description"]);
    $age = trim($_POST["age"]);
    $date_of_birth = trim($_POST["date_of_birth"]);
    $status = trim($_POST["status"]);
    $city = trim($_POST["city"]);
    $negeri = trim($_POST["negeri"]);
    $studying = trim($_POST["studying"]);
    $university = trim($_POST["university"]);
    $student_id = trim($_POST["student_id"]);

    // Get genres
    $genre = isset($_POST['genres']) ? implode(", ", $_POST['genres']) : "";

    // Handle movie poster upload
    $img_link = null;
    if (isset($_FILES['choosefile']) && $_FILES['choosefile']['error'] == 0) {
        $file = $_FILES['choosefile'];
        $extensions = array('jpg', 'png', 'gif', 'jpeg');
        $file_ext = explode('.', $file['name']);
        $name = $file_ext[0];
        $name = preg_replace("!-!", " ", $name);
        $name = ucwords($name);
        $file_ext = end($file_ext);

        if (!in_array($file_ext, $extensions)) {
            $msg = "$name - Invalid file extension!";
        } else {
            $img_link = 'img/' . basename($file['name']);
            if (!move_uploaded_file($file['tmp_name'], $img_link)) {
                $msg = "Failed to upload image.";
            }
        }
    }

    // Validate fullname
    if (empty($fullname)) {
        $fullname_err = "Please enter a fullname.";
    }

    // If no errors, proceed to insert into database
    if (empty($fullname_err) && empty($msg)) {
        // Prepare SQL statement for INSERT INTO
        $sql = "INSERT INTO team_member (fullname, description, age, date_of_birth, status, city, negeri, studying, genre, university, student_id, img_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $fullname, $description, $age, $date_of_birth, $status, $city, $negeri, $studying, $genre, $university, $student_id, $img_link);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to success page
                header("location: create_member.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else {
            echo "Error: " . mysqli_error($link);
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
    <title>CINE EASE</title>
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
                            <h6 class="mb-4">Create Team Member</h6>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <label for="fullname" class="col-sm-2 col-form-label">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($fullname); ?>" placeholder="Full Name"/>
                                        <span class="invalid-feedback"><?php echo $fullname_err; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="description" placeholder="Description" id="description" style="height: 150px;"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="age" class="col-sm-2 col-form-label">Age</label>
                                    <div class="col-sm-10">
                                        <input type="number" name="age" class="form-control" placeholder="Age">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="date_of_birth" class="col-sm-2 col-form-label">Date Of Birth</label>
                                    <div class="col-sm-10">
                                        <input type="date" name="date_of_birth" class="form-control" placeholder="Date Of Birth">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="status" class="form-control" placeholder="Status">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="negeri" class="col-sm-2 col-form-label">Staying Negeri/State</label>
                                    <div class="col-sm-10">
                                        <select id="negeri" name="negeri" class="form-control">
                                            <option value="">Select Negeri/State</option>
                                            <?php
                                            $states = ["Johor", "Kedah", "Kelantan", "Wilayah Persekutuan Kuala Lumpur", "Labuan", "Melaka", "Negeri Sembilan", "Pahang", "Penang", "Perak", "Perlis", "Wilayah Persekutuan Putrajaya", "Sabah", "Sarawak", "Selangor", "Terengganu"];
                                            foreach ($states as $state) {
                                                echo "<option value='$state'" . ($state == $negeri ? " selected" : "") . ">$state</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="city" class="col-sm-2 col-form-label">Staying Bandar/City</label>
                                    <div class="col-sm-10">
                                        <select id="city" name="city" class="form-control">
                                            <option value="">Select Bandar/City</option>
                                            <!-- You can populate city options dynamically based on the selected negeri using JavaScript -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="studying" class="col-sm-2 col-form-label">Studying</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="studying" class="form-control" placeholder="Studying">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="university" class="col-sm-2 col-form-label">University</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="university" class="form-control" placeholder="University">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="student_id" class="col-sm-2 col-form-label">Student ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="student_id" class="form-control" placeholder="Student ID">
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Music Genre</legend>
                                    <div class="col-sm-10">
                                        <?php
                                        $genres = [
                                            'Alternative', 'Blues', 'Classical', 'Country', 'Dance', 'Electronic', 'Emo', 'Folk',
                                            'Funk', 'Gospel', 'Hip-Hop/Rap', 'Indie', 'Instrumental', 'Jazz', 'malody', 'Metal', 'Pop',
                                            'Punk', 'R&B/Soul', 'Reggae', 'Rock', 'World'
                                        ];
                                        
                                        foreach($genres as $genre) {
                                            echo "<div class='form-check form-check-inline'>";
                                            echo "<input class='form-check-input' type='checkbox' name='genres[]' id='$genre' value='$genre'>";
                                            echo "<label class='form-check-label' for='$genre'>$genre</label>";
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </fieldset>
                                <div class="row mb-3">
                                    <label for="choosefile" class="col-sm-2 col-form-label">Self Protrat Poster</label>
                                    <div class="col-sm-10">
                                        <input class="form-control bg-dark" type="file" name="choosefile">
                                        <span class="text-danger"><?php echo $msg; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <input type="hidden" name="team_id" value="<?php echo $team_id; ?>">
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
    <?php include('inc/script_city.php'); ?>
</body>
</html>