<?php
session_start();
if (isset($_SESSION["user_logged_in"])) {
    header("Location: user_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "task_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_hashed = password_hash($password, PASSWORD_DEFAULT); // Hash password for security

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Email already exists. Please use a different email.";
    } else {
        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password_hashed);
        if ($stmt->execute()) {
            $_SESSION["user_logged_in"] = true;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email;
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error: Could not register. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Registration</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Register</h2>
    <?php if (isset($error_message)): ?>
      <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
      <input type="text" name="name" placeholder="Full Name" required><br><br>
      <input type="email" name="email" placeholder="Email" required><br><br>
      <input type="password" name="password" placeholder="Password" required><br><br>
      <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="user_login.php">Login here</a></p>
  </div>
</body>
</html>
