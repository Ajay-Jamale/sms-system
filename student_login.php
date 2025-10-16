<?php
session_start();
include 'db_connect.php';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username && $password){
        $res = mysqli_query($conn,"SELECT * FROM students WHERE username='".mysqli_real_escape_string($conn,$username)."' LIMIT 1");
        if(mysqli_num_rows($res) == 1){
            $row = mysqli_fetch_assoc($res);
            if(password_verify($password,$row['password'])){
                $_SESSION['student'] = $row['id'];  // store student ID
                $_SESSION['student_name'] = $row['name'];
                header('Location: student_dashboard.php'); 
                exit;
            } else $error = "Invalid password";
        } else $error = "Student not found";
    } else $error = "Enter username and password";
}
?>
<!DOCTYPE html>
<html>
<head><title>Student Login</title></head>
<body>
<h2>Student Login</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
Username:<br><input type="text" name="username" required><br>
Password:<br><input type="password" name="password" required><br><br>
<input type="submit" value="Login">
</form>
</body>
</html>
