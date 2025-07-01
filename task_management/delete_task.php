<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "task_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Delete the task from the database
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: admin_dashboard.php");
    exit();
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
