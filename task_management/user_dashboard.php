<?php
session_start();
if (!isset($_SESSION["user_logged_in"])) {
    header("Location: user_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "task_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all tasks
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
  <title>User Dashboard</title>
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
  </style>
</head>
<body>
  <div class="container">
    <h2>Welcome, <?= $_SESSION["user_name"] ?>!</h2>
    <h3>📋 Your Task List</h3>

    <?php if (count($tasks) > 0): ?>
    <table>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Description</th>
        <th>Due Date</th>
        <th>Status</th>
        <th>Update</th>
      </tr>
      <?php foreach ($tasks as $index => $task): ?>
      <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($task['title']) ?></td>
        <td><?= htmlspecialchars($task['description']) ?></td>
        <td><?= $task['due_date'] ?></td>
        <td><?= $task['status'] ?></td>
        <td>
          <form action="update_status.php" method="POST" style="display:inline;">
            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
            <select name="status">
              <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
              <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>
            <button type="submit" class="btn">Update</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
      <p>No tasks available yet.</p>
    <?php endif; ?>

    <br><a href="logout.php" class="btn">Logout</a>
  </div>
</body>
</html>
