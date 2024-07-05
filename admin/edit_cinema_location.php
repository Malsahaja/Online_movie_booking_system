<?php
session_start();
include('../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

// Define variables and initialize with empty values
$location = $city = $negeri = "";
$location_err = "";

// Check if the cinema_id is set in the query string
if (isset($_GET['cinema_id']) && !empty(trim($_GET['cinema_id']))) {
    $cinema_id = trim($_GET['cinema_id']);

    // Fetch the existing data from the database
    $sql = "SELECT * FROM cinema WHERE cinema_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_cinema_id);
        $param_cinema_id = $cinema_id;

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $location = $row['location'];
                $city = $row['city'];
                $negeri = $row['negeri'];
            } else {
                echo "Error: No record found with the given ID.";
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            exit();
        }
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error: Invalid request.";
    exit();
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate location
    if (empty(trim($_POST["location"]))) {
        $location_err = "Please enter a location.";
    } else {
        $location = ucwords(trim($_POST["location"]));
    }

    // Capitalize each word in city and negeri
    $city = ucwords(trim($_POST["city"]));
    $negeri = ucwords(trim($_POST["negeri"]));

    // Check input errors before updating in database
    if (empty($location_err)) {
        // Prepare an update statement
        $sql = "UPDATE cinema SET location = ?, city = ?, negeri = ? WHERE cinema_id = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_location, $param_city, $param_negeri, $param_cinema_id);
            
            // Set parameters
            $param_location = $location;
            $param_city = $city;
            $param_negeri = $negeri;
            $param_cinema_id = $cinema_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to view_location.php
                header("location: view_location.php?cinema_id=" . $cinema_id);
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
                            <h6 class="mb-4">Edit Cinema Location</h6>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?cinema_id=' . $cinema_id; ?>" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Cinema Location</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="location" class="form-control <?php echo (!empty($location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($location); ?>" placeholder="Movie Location"/>
                                        <span class="invalid-feedback"><?php echo $location_err; ?></span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="negeri" class="col-sm-2 col-form-label">Negeri/State</label>
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
                                    <label for="city" class="col-sm-2 col-form-label">Bandar/City</label>
                                    <div class="col-sm-10">
                                        <select id="city" name="city" class="form-control">
                                            <option value="">Select Bandar/City</option>
                                            <!-- The options will be populated dynamically based on the selected state -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Update</button>
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
    <script>
        // Cities data based on states
        const cities = {
            "Johor": ["Johor Bahru", "Batu Pahat", "Kluang"],
            "Kedah": ["Alor Setar", "Sungai Petani", "Kulim"],
            "Kelantan": ["Kota Bharu", "Tanah Merah", "Gua Musang"],
            "Wilayah Persekutuan Kuala Lumpur": ["Kuala Lumpur"],
            "Labuan": ["Labuan"],
            "Melaka": ["Melaka"],
            "Negeri Sembilan": ["Seremban", "Port Dickson", "Nilai"],
            "Pahang": ["Kuantan", "Bentong", "Temerloh"],
            "Penang": ["George Town", "Butterworth", "Bukit Mertajam"],
            "Perak": ["Ipoh", "Taiping", "Teluk Intan"],
            "Perlis": ["Kangar"],
            "Wilayah Persekutuan Putrajaya": ["Putrajaya"],
            "Sabah": ["Kota Kinabalu", "Sandakan", "Tawau"],
            "Sarawak": ["Kuching", "Miri", "Sibu"],
            "Selangor": ["Shah Alam", "Petaling Jaya","Puchong", "Subang Jaya"],
            "Terengganu": ["Kuala Terengganu", "Dungun", "Kemaman"]
        };

        // Update city options based on selected state
        document.getElementById('negeri').addEventListener('change', function() {
            const state = this.value;
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Select Bandar/City</option>'; // Clear current options

            if (state && cities[state]) {
                cities[state].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.text = city;
                    citySelect.appendChild(option);
                });
            }
        });

        // Set initial cities if a state was previously selected
        const initialState = "<?php echo $negeri; ?>";
        const initialCity = "<?php echo $city; ?>";
        if (initialState) {
            document.getElementById('negeri').value = initialState;
            const citySelect = document.getElementById('city');
            cities[initialState].forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.text = city;
                if (city === initialCity) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });
        }
    </script>
</body>
</html>
