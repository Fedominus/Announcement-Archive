<?php
    session_start();

    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Clear cookie
    setcookie("username", "", time() - 3600, "/");

    // Redirect to login
    header("Location: login.php");
    exit();
?>
