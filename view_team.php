<?php
session_start();
include('config/config.php');

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
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CINE EASE - Team Member</title>

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
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <a href="team_member.php">Team Member</a>
                        <span><?php echo htmlspecialchars($team_member['fullname']); ?></span>
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
                        <div class="anime__details__pic set-bg" data-setbg="admin/<?php echo htmlspecialchars($team_member['img_link']); ?>">
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
                                <a></a> 
                                </div>
                            </div>
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

    </body>

    </html>