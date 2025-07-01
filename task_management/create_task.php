<?php
// create_task.php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "task_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$task_title = $_POST["task_title"];
$task_description = $_POST["task_description"];
$due_date = $_POST["due_date"];

$sql = "INSERT INTO tasks (title, description, due_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $task_title, $task_description, $due_date);

if ($stmt->execute()) {
    echo "<script>alert('Task created successfully'); window.location.href='admin_dashboard.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
