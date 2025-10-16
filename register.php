<?php
session_start();
include 'db_connect.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            $msg = "Registration successful! <a href='index.php'>Login</a>";
        } else {
            $msg = "Error: User might already exist!";
        }
        $stmt->close();
    } else {
        $msg = "Enter username and password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

<header>
    <h1>Student Management System</h1>
    <nav>
        <a href="index.php" class="btn">Sign In</a>
        <a href="register.php" class="btn">Sign Up</a>
    </nav>
</header>

<div class="login-box">
    <h2>Register</h2>
    <?php if ($msg) { echo "<p class='msg'>$msg</p>"; } ?>
    <form method="post">
        <input type="text" name="username" placeholder="Choose Username" required><br>
        <input type="password" name="password" placeholder="Choose Password" required><br>
        <input type="submit" value="Register">
    </form>
</div>

</body>
</html>
