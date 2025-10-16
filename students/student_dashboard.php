<?php
session_start();
if(!isset($_SESSION['student'])) header('Location: student_login.php');
?>
<h2>Welcome, <?php echo $_SESSION['student_name']; ?> (Student)</h2>
<ul>
    <li><a href="view_profile.php">My Profile</a></li>
    <li><a href="my_attendance.php">My Attendance</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>
