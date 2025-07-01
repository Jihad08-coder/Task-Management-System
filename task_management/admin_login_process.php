<?php
// admin_login_process.php

session_start();

// Fixed admin credentials
$admin_email = "jahid@admin.com";
$admin_password = "jahid";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_name"] = "Jahid";
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid admin email or password'); window.location.href='admin_login.php';</script>";
    }
}
?>
