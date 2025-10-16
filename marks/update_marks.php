<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: ../index.php');
include '../db_connect.php';

if (isset($_POST['id']) && isset($_POST['subject'])) {
    $id = (int)$_POST['id'];
    $subject = htmlspecialchars(trim($_POST['subject']));

    $stmt = $conn->prepare("UPDATE exam_marks SET subject = ? WHERE id = ?");
    $stmt->bind_param("si", $subject, $id);
    $stmt->execute();
}

header('Location: view_marks.php');
exit;
?>
