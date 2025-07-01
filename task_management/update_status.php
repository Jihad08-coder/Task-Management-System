<?php
session_start();
if (!isset($_SESSION["user_logged_in"])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST["task_id"];
    $status = $_POST["status"];

    $conn = new mysqli("localhost", "root", "", "task_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $task_id);
    $stmt->execute();
}

header("Location: user_dashboard.php");
exit();
