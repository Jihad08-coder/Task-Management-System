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

$tasks = [];
$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }
    th {
      background-color:rgb(109, 255, 112);
    }
    .btn-group a {
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo $_SESSION["admin_name"]; ?>!</h2>

    <!-- Task Creation Form -->
    <form action="create_task.php" method="POST">
      <input type="text" name="task_title" placeholder="Task Title" required><br><br>
      <textarea name="task_description" placeholder="Task Description" rows="4" required></textarea><br><br>
      <input type="date" name="due_date" required><br><br>
      <button type="submit" class="btn">Create Task</button>
    </form>

    <h3>📋 Task List</h3>
    <?php if (count($tasks) > 0): ?>
    <table>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Description</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($tasks as $index => $task): ?>
      <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($task['title']) ?></td>
        <td><?= htmlspecialchars($task['description']) ?></td>
        <td><?= $task['due_date'] ?></td>
        <td><?= $task['status'] ?></td>
        <td class="btn-group">
          <!-- Edit Button -->
          <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn">Edit</a>

          <!-- Delete Button -->
          <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
      <p>No tasks created yet.</p>
    <?php endif; ?>

    <br><a href="logout.php" class="btn">Logout</a>
  </div>
</body>
</html>
