<?php
    //Logout
    session_start();

    $_SESSION = array();
    session_destroy();
    header("location: index.php");
exit;
?>