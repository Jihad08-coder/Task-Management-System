<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $conn = new mysqli("localhost", "root", "", "task_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_logged_in"] = true;
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $email;

            header("Location: user_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>User Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
      <input type="email" name="email" placeholder="Email" required><br><br>
      <input type="password" name="password" placeholder="Password" required><br><br>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>
</html>
