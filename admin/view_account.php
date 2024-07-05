<?php
session_start();
include('../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

$user_role = $_SESSION['role'];

define('ROLE_MASTER', 1);
define('ROLE_ADMIN', 2);
define('ROLE_USER', 3);

if ($user_role == ROLE_MASTER) {
    $query = "SELECT * FROM user ORDER BY user_id DESC";
} elseif ($user_role == ROLE_ADMIN) {
    $query = "SELECT * FROM user WHERE role IN (".ROLE_ADMIN.", ".ROLE_USER.") ORDER BY user_id DESC";
} else {
    $query = "SELECT * FROM user WHERE role = ".ROLE_USER." ORDER BY user_id DESC";
}

$result = mysqli_query($link, $query);

$role_names = [
    ROLE_MASTER => 'Master',
    ROLE_ADMIN => 'Admin',
    ROLE_USER => 'User'
];
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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
                            <h6 class="mb-4">Responsive Table</h6>
                            <div class="table-responsive">
                                <table id="datatables" class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone Number</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                            $i = 1;
                                            if ($result) {
                                                while ($user = mysqli_fetch_array($result)) {
                                                    echo "<tr>";
                                                    echo "<th>" . $i++ . "</th>";
                                                    echo "<td>" . htmlspecialchars($user['fullname']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($user['phone_no']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($role_names[$user['role']]) . "</td>";
                                                    
                                                    echo "<td>";
                                                    if ($user_role < $user['role']) {
                                                        echo "<a href='#' class='text-danger delete' onclick='confirmDelete(" . $user['user_id'] . ")'><i class='far fa-trash-alt'></i></a>";
                                                    }
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7'>No records found.</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>
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
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script>
        new DataTable('#datatables');
    </script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "action/delete_user.php?id=" + userId;
            }
        }
    </script>
</body>

</html>
