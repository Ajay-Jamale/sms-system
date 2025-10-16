<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: ../index.php');
include '../db_connect.php';
require '../vendor/autoload.php';  // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = (int) $_GET['id'];

// Fetch student and exam details
$stmt = $conn->prepare("
    SELECT s.name, s.email, m.subject, m.marks, m.total_marks, m.exam_date 
    FROM exam_marks m 
    JOIN students s ON m.student_id = s.id 
    WHERE m.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    $_SESSION['msg'] = "Record not found.";
    header('Location: view_marks.php');
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ajayjamale2@gmail.com'; // your Gmail
    $mail->Password = 'wovhbdtcudcbzqwg'; // your App Password (NO spaces!)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom('ajayjamale2@gmail.com', 'Student Management System');
    $mail->addAddress($data['email'], $data['name']);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Exam Marks - " . $data['subject'];
    $mail->Body = "
        <p>Hello <b>{$data['name']}</b>,</p>
        <p>Here are your marks for <b>{$data['subject']}</b>:</p>
        <p><b>Marks:</b> {$data['marks']} / {$data['total_marks']}<br>
        <b>Exam Date:</b> " . date('d-M-Y', strtotime($data['exam_date'])) . "</p>
        <p>Best wishes,<br>Student Management System</p>
    ";

    $mail->send();
    $_SESSION['msg'] = "Marks sent successfully to " . htmlspecialchars($data['email']);
} catch (Exception $e) {
    $_SESSION['msg'] = "Failed to send email. Error: " . $mail->ErrorInfo;
}

header('Location: view_marks.php');
exit;
?>
