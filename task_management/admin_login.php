<!-- admin_login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Admin Login</h2>
    <form action="admin_login_process.php" method="POST">
      <input type="email" name="email" placeholder="Admin Email" required><br><br>
      <input type="password" name="password" placeholder="Password" required><br><br>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>
</html>
