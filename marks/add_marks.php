<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: ../index.php');
include '../db_connect.php';

$stmt = $conn->prepare("SELECT id, name, email FROM students ORDER BY name ASC");
$stmt->execute();
$students = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_POST['student_id'];
    $subject = htmlspecialchars(trim($_POST['subject']));
    $marks = (int)$_POST['marks'];
    $total_marks = (int)$_POST['total_marks'];
    $exam_date = $_POST['exam_date'];

    $stmt = $conn->prepare("INSERT INTO exam_marks (student_id, subject, marks, total_marks, exam_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isiis", $student_id, $subject, $marks, $total_marks, $exam_date);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Marks added successfully!";
        header('Location: view_marks.php');
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Exam Marks</title>
    <link rel="stylesheet" href="../assets/css/view_marks.css">
</head>
<body>
    <h2>Add Exam Marks</h2>

    <?php if (isset($error)): ?>
        <p style="color:red;text-align:center;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" class="form-container">
        <label>Student:</label>
        <select name="student_id" required>
            <option value="">-- Select --</option>
            <?php while ($s = $students->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Subject:</label>
        <select name="subject" required>
            <option value="">-- Select Subject --</option>
            <option value="Physics">Physics</option>
            <option value="Chemistry">Chemistry</option>
            <option value="Biology">Biology</option>
            <option value="Maths">Maths</option>
            <option value="PCB">PCB</option>
            <option value="PCM">PCM</option>
        </select>

        <label>Marks:</label>
        <input type="number" name="marks" required>

        <label>Total Marks:</label>
        <input type="number" name="total_marks" required>

        <label>Exam Date:</label>
        <input type="date" name="exam_date" required>

        <input type="submit" value="Add Marks" class="btn">
    </form>
</body>
</html>
