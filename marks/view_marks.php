<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: ../index.php');
include '../db_connect.php';

$res = $conn->query("SELECT m.id, s.name, s.email, m.subject, m.marks, m.total_marks, m.exam_date 
                     FROM exam_marks m 
                     JOIN students s ON m.student_id = s.id 
                     ORDER BY m.exam_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Exam Marks</title>
    <link rel="stylesheet" href="../assets/css/view_marks.css">
</head>
<body>
    <h2>Exam Marks List</h2>

    <?php if (isset($_SESSION['msg'])): ?>
        <p class="success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
    <?php endif; ?>

    <div style="text-align:center;margin-bottom:15px;">
        <a href="add_marks.php" class="btn">Add Marks</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Total</th>
                <th>Date</th>
                <th>Send Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td data-label="Student"><?= htmlspecialchars($row['name']); ?></td>
                    <td data-label="Subject"><?= htmlspecialchars($row['subject']); ?></td>
                    <td data-label="Marks"><?= $row['marks']; ?></td>
                    <td data-label="Total"><?= $row['total_marks']; ?></td>
                    <td data-label="Date"><?= date('d-M-Y', strtotime($row['exam_date'])); ?></td>
                    <td data-label="Email">
                        <a href="send_marks.php?id=<?= $row['id']; ?>" class="btn">Send</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
