<?php
include 'C:\xampp\htdocs\student_management_system\db_connect.php';

if(isset($_POST['id']) && isset($_POST['subject'])) {
    $id = $_POST['id'];
    $subject = $_POST['subject'];

    $query = "UPDATE exam_marks SET subject = '$subject' WHERE id = '$id'";
    mysqli_query($conn, $query);
}

header('Location: view_marks.php');
exit();
?>
