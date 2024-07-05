<?php
session_start();
include('config/config.php');
require('fpdf/fpdf.php'); // Make sure the path to fpdf.php is correct

if (!isset($_POST['booking_id'])) {
    die('Booking ID not provided');
}

$booking_id = intval($_POST['booking_id']);

// Fetch booking details
$query = "SELECT b.*, u.username, u.phone_no, u.email, m.title 
          FROM booking b
          JOIN user u ON b.user_id = u.user_id
          JOIN movie_detail m ON b.movie_id = m.movie_id
          WHERE b.booking_id = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die('Booking not found');
}

// QR Code path
$qr_image = 'qr_codes/booking_' . $booking_id . '.png';

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('img/logo/CineEaseLatest.PNG', 75, 10, 60);
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Multimedia University - MMU Cyberjaya', 0, 1, 'C');
        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Content
    function Content($booking, $qr_image)
    {
        // Customer and date details
        $this->Cell(95, 10, '1-300-80-0668', 0, 0);
        $this->Cell(95, 10, 'Customer Id: ' . $booking['user_id'], 0, 1, 'R');
        $this->Cell(95, 10, '', 0, 0);
        $this->Cell(95, 10, 'Date: ' . date('d-m-Y H:i:s', strtotime($booking['payment_date'])), 0, 1, 'R');
        $this->Ln(10);

        // Movie Title
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Movie Name: ' . $booking['title'], 0, 1, 'C');
        $this->Ln(10);

        // User details
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, 'Name', 1, 0, 'C');
        $this->Cell(95, 10, 'Phone', 1, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(95, 10, $booking['username'], 1, 0, 'C');
        $this->Cell(95, 10, $booking['phone_no'], 1, 1, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, 'Email', 1, 0, 'C');
        $this->Cell(95, 10, 'Payment Amount', 1, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(95, 10, $booking['email'], 1, 0, 'C');
        $this->Cell(95, 10, 'RM ' . $booking['total_amount'], 1, 1, 'C');
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, 'Payment Date', 1, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(95, 10, date('d-m-Y H:i:s', strtotime($booking['payment_date'])), 1, 1, 'C');
        $this->Ln(10);

        // Booking details
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(95, 10, 'Booking Date', 1, 0, 'C');
        $this->Cell(95, 10, 'Booking Time', 1, 1, 'C');

        $this->SetFont('Arial', '', 12);
        $this->Cell(95, 10, date('d-m-Y', strtotime($booking['book_date'])), 1, 0, 'C');
        $this->Cell(95, 10, date('H:i:s', strtotime($booking['book_time'])), 1, 1, 'C');

        // QR Code
        $this->Image($qr_image, 150, 150, 40); // Adjust position and size as needed
    }
}

// Create a new PDF document
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Content($booking, $qr_image);

// Output PDF to a temporary file
$pdf_temp_file = tempnam(sys_get_temp_dir(), 'receipt_');
$pdf->Output($pdf_temp_file, 'F');

// Email setup
$to_email = $booking['email'];
$subject = 'Receipt for Movie Booking';
$message = 'Dear ' . $booking['username'] . ",\n\nPlease find attached your receipt for the movie booking.\n\nThank you!\nCineEase Team";
$from_email = 'your-email@example.com'; // Replace with your email address

// Attachment
$attachment = $pdf_temp_file;

// Headers for attachment
$headers = "From: $from_email\r\n";
$headers .= "Reply-To: $from_email\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"\r\n";
$headers .= "Content-Disposition: attachment; filename=\"receipt.pdf\"\r\n";

// Send email with attachment
if (mail($to_email, $subject, $message, $headers, "-f$from_email")) {
    echo "Email sent successfully with PDF attachment.";
} else {
    echo "Email sending failed.";
}

// Clean up: Delete temporary PDF file
unlink($pdf_temp_file);
?>
