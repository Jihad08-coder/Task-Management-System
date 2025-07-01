<?php
session_start();

// Destroy the session to log the user out
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session completely

// Redirect to the home page (index.php)
header("Location: index.php");
exit();
?>
