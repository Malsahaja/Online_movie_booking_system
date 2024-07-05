<?php
include('config/config.php');
session_start();

// Fetch the logged-in user's card_id from the card_detail table
$user_id = $_SESSION['user_id'];
$card_id_query = "SELECT card_id FROM card_detail WHERE user_id = ?";
$stmt = $link->prepare($card_id_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($card_id);
$stmt->fetch();
$stmt->close();

// Handle form submission for booking seats
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_seats'])) {
    $movie_id = intval($_POST['movie_id']);
    $seats = implode(',', $_POST['seats']);
    $total_seat = count($_POST['seats']);
    $book_date = htmlspecialchars($_POST['book_date']);
    $book_time = htmlspecialchars($_POST['book_time']);
    $payment_date = date('Y-m-d H:i:s');
    $total_amount = htmlspecialchars($_POST['total_amount']);

    $stmt = $link->prepare("INSERT INTO booking (user_id, movie_id, card_id, seat, total_seat, book_date, book_time, payment_date, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiisssssd", $user_id, $movie_id, $card_id, $seats, $total_seat, $book_date, $book_time, $payment_date, $total_amount);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $link->close();
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
    <title>CINE EASE</title>

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

<style>
    body {
        width: 100%;
        height: 100vh;
        margin: 0;
        padding: 0;
    }
    .center {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tickets {
        width: 550px;
        height: fit-content;
        border: 0.4mm solid rgba(0, 0, 0, 0.08);
        border-radius: 3mm;
        box-sizing: border-box;
        padding: 10px;
        font-family: poppins;
        max-height: 96vh;
        overflow: auto;
        background: white;
        box-shadow: 0px 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .ticket-selector {
        background: rgb(243, 243, 243);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;
        box-sizing: border-box;
        padding: 20px;
    }
    .head {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
    }
    .title {
        font-size: 16px;
        font-weight: 600;
    }
    .seats {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        min-height: 150px;
        position: relative;
    }
    .status {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-evenly;
    }
    .seats::before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 0);
        width: 220px;
        height: 7px;
        background: rgb(141, 198, 255);
        border-radius: 0 0 3mm 3mm;
        border-top: 0.3mm solid rgb(180, 180, 180);
    }
    .item {
        font-size: 12px;
        position: relative;
    }
    .item::before {
        content: "";
        position: absolute;
        top: 50%;
        left: -12px;
        transform: translate(0, -50%);
        width: 10px;
        height: 10px;
        background: rgb(255, 255, 255);
        outline: 0.2mm solid rgb(120, 120, 120);
        border-radius: 0.3mm;
    }
    .item:nth-child(2)::before {
        background: rgb(180, 180, 180);
        outline: none;
    }
    .item:nth-child(3)::before {
        background: rgb(28, 185, 120);
        outline: none;
    }
    .all-seats {
        display: grid;
        grid-template-columns: repeat(10, 1fr);
        grid-gap: 15px;
        margin: 60px 0;
        margin-top: 20px;
        position: relative;
    }
    .seat {
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 0.5mm;
        outline: 0.3mm solid rgb(180, 180, 180);
        cursor: pointer;
    }
    .all-seats input:checked + label {
        background: rgb(28, 185, 120);
        outline: none;
    }
    .seat.booked {
        background: rgb(180, 180, 180);
        outline: none;
    }
    input {
        display: none;
    }
    .timings {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 30px;
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
        padding: 10px 0;
        border-radius: 2mm;
        cursor: pointer;
    }
    .day {
        font-size: 12px;
    }
    .times {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 10px;
    }
    .time {
        font-size: 14px;
        width: fit-content;
        padding: 7px 14px;
        background: rgb(233, 233, 233);
        border-radius: 2mm;
        cursor: pointer;
    }
    .timings input:checked + label {
        background: rgb(28, 185, 120);
        color: white;
    }
    .price {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .total {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        font-size: 16px;
        font-weight: 500;
    }
    .total span {
        font-size: 11px;
        font-weight: 400;
    }
    .price button {
        background: rgb(60, 60, 60);
        color: white;
        font-family: poppins;
        font-size: 14px;
        padding: 7px 14px;
        border-radius: 2mm;
        outline: none;
        border: none;
        cursor: pointer;
    }
</style>

<body>
    <!-- Header Section Begin -->
    <?php include('inc/header.php'); ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="./index.php"><i class="fa fa-home"></i> Home</a>
                        <span>Booking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Booking Section Begin -->
    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="center">
                        <div class="tickets">
                            <form id="bookingForm" method="post" action="">
                                <div class="ticket-selector">
                                    <div class="head">
                                        <div class="title"><?php echo htmlspecialchars($title); ?></div>
                                    </div>
                                    <div class="seats">
                                        <div class="status">
                                            <div class="item available">Available</div>
                                            <div class="item booked">Booked</div>
                                            <div class="item selected">Selected</div>
                                        </div>
                                        <div class="all-seats">
                                            <?php
                                            // Seat labels based on the given mapping
                                            $seat_labels = [
                                                ['F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10'],
                                                ['E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7', 'E8', 'E9', 'E10'],
                                                ['D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7', 'D8', 'D9', 'D10'],
                                                ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8', 'C9', 'C10'],
                                                ['B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10'],
                                                ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10']
                                            ];

                                            foreach ($seat_labels as $row) {
                                                foreach ($row as $seat) {
                                                    $isBooked = in_array($seat, $bookedSeats) ? 'booked' : '';
                                                    $isDisabled = $isBooked ? 'disabled' : '';
                                                    echo '<input type="checkbox" name="seats[]" value="' . $seat . '" id="s' . $seat . '" ' . $isDisabled . ' />
                                                          <label for="s' . $seat . '" class="seat ' . $isBooked . '"></label>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="price">
                                    <div class="total">
                                        <span> <span class="count">0</span> Tickets </span>
                                        <div class="amount">0</div>
                                    </div>
                                    <button type="submit" name="book_seats">Book</button>
                                </div>
                                <!-- Hidden inputs to store booking details -->
                                <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                                <input type="hidden" name="book_date" value="<?php echo $date; ?>">
                                <input type="hidden" name="book_time" value="<?php echo $time; ?>">
                                <input type="hidden" id="total_amount" name="total_amount" value="0">
                                <input type="hidden" id="price" value="<?php echo $price; ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Booking Section End -->

    <!-- Footer Section Begin -->
    <?php include('inc/footer.php'); ?>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
    <!-- Seat Start -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let seats = document.querySelector(".all-seats");
            let tickets = seats.querySelectorAll("input");
            let ticketPrice = Number(document.getElementById("price").value);

            tickets.forEach((ticket) => {
                ticket.addEventListener("change", () => {
                    let amountElement = document.querySelector(".amount");
                    let countElement = document.querySelector(".count");
                    let totalAmountInput = document.getElementById("total_amount");
                    let amount = Number(amountElement.innerHTML);
                    let count = Number(countElement.innerHTML);

                    if (ticket.checked) {
                        count += 1;
                        amount += ticketPrice;
                    } else {
                        count -= 1;
                        amount -= ticketPrice;
                    }
                    amountElement.innerHTML = amount.toFixed(2); // Ensure the amount is formatted as a number
                    countElement.innerHTML = count;
                    totalAmountInput.value = amount.toFixed(2);
                });
            });
        });
    </script>
    <!-- Seat End -->
</body>

</html>