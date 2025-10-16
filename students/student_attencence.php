<?php
session_start();
if(!isset($_SESSION['student'])) header('Location: student_login.php');
include 'db_connect.php';

$student_id = $_SESSION['student'];
$res = mysqli_query($conn,"SELECT attendance_date, status FROM attendance WHERE student_id=$student_id ORDER BY attendance_date DESC");
?>
<h2>My Attendance</h2>
<table border="1" cellpadding="5">
<tr><th>Date</th><th>Status</th></tr>
<?php while($row=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?php echo $row['attendance_date']; ?></td>
<td><?php echo $row['status']; ?></td>
</tr>
<?php endwhile; ?>
</table>
