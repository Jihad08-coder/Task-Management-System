<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn = new mysqli("localhost", "root", "", "task_db");
    
    // Fetch current status
    $result = $conn->query("SELECT status FROM tasks WHERE id = $id");
    $task = $result->fetch_assoc();

    if ($task['status'] == 'Pending') {
        $new_status = 'Completed';
    } else {
        $new_status = 'Pending';
    }

    // Update status
    $stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    
    header("Location: admin_dashboard.php");
    exit();
}
?>
