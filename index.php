<?php
session_start();
include 'db_connect.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
        }

        $stmt->close();
    } else {
        $error = "Enter username and password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <h2>Login</h2>
    <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="submit" value="Login">
    </form>
</div>

</body>
</html>
