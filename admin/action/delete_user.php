<?php
session_start();
include('../../config/config.php');

// Check if user is not logged in or role is not allowed
if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: ../index.php"); // Redirect to index.php if conditions are not met
    exit(); // Ensure that script stops execution after redirection
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Delete the user
    $query = "DELETE FROM user WHERE user_id = ?";
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Redirect back to the view account page
header("Location: ../view_account.php");
exit();
?>
