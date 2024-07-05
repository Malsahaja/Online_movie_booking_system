<?php
session_start();
include('../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

// Check if the team_id is set in the URL
if(isset($_GET['team_id'])) {
    $team_id = $_GET['team_id'];

    // Prepare a statement to prevent SQL injection
    $stmt = $link->prepare("SELECT * FROM team_member WHERE team_id = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a team was found
    if($result->num_rows > 0) {
        $team_member = $result->fetch_assoc();
    } else {
        echo "No team found with the provided ID.";
        exit;
    }
} else {
    echo "No team ID provided.";
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
                                <div class="col-lg-3">
                                    <div class="anime__details__pic set-bg" style="background-image: url('<?php echo htmlspecialchars($team_member['img_link']); ?>');">
                                        <!-- Placeholder for Accessibility -->
                                        <div class="sr-only">
                                            <?php echo htmlspecialchars($team_member['fullname']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="anime__details__text">
                                        <div class="anime__details__title">
                                            <h3><?php echo htmlspecialchars($team_member['fullname']); ?></h3>
                                            <span></span>
                                        </div>
                                        <p><?php echo htmlspecialchars($team_member['description']); ?></p>
                                        <div class="anime__details__widget">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <ul>
                                                        <li><span>Age :</span> <?php echo htmlspecialchars($team_member['age']); ?></li>
                                                        <li><span>Date of Birth :</span> <?php echo htmlspecialchars($team_member['date_of_birth']); ?></li>
                                                        <li><span>Status :</span> <?php echo htmlspecialchars($team_member['status']); ?></li>
                                                        <li><span>Music Taste :</span> <?php echo htmlspecialchars($team_member['genre']); ?></li>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <ul>
                                                    <li><span>Staying At :</span> <?php echo htmlspecialchars($team_member['city']) . ", " . htmlspecialchars($team_member['negeri']);?></li>
                                                        <li><span>Stuyding :</span> <?php echo htmlspecialchars($team_member['studying']); ?></li>
                                                        <li><span>University :</span> <?php echo htmlspecialchars($team_member['university']); ?></li>
                                                        <li><span>Student ID :</span> <?php echo htmlspecialchars($team_member['student_id']); ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="anime__details__btn">
                                            <a href="edit_team_member.php?team_id=<?php echo $team_id; ?>" class="btn btn-primary m-2"><span>Edit Member</span> <i class="fa fa-angle-right"></i></a>
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
