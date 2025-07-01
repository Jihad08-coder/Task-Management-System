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
    $result = $conn->query("SELECT * FROM tasks WHERE id = $id");

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        header("Location: admin_dashboard.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["task_title"];
    $description = $_POST["task_description"];
    $due_date = $_POST["due_date"];
    $status = $_POST["status"];

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, due_date=?, status=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $description, $due_date, $status, $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_message = "Error: Could not update task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Task</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Edit Task</h2>
    <?php if (isset($error_message)): ?>
      <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="edit_task.php?id=<?= $task['id'] ?>" method="POST">
      <input type="text" name="task_title" value="<?= htmlspecialchars($task['title']) ?>" placeholder="Task Title" required><br><br>
      <textarea name="task_description" placeholder="Task Description" rows="4" required><?= htmlspecialchars($task['description']) ?></textarea><br><br>
      <input type="date" name="due_date" value="<?= $task['due_date'] ?>" required><br><br>
      <select name="status" required>
        <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
      </select><br><br>
      <button type="submit" class="btn">Update Task</button>
    </form>
  </div>
</body>
</html>
