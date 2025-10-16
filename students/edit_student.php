<?php
session_start();
if (!isset($_SESSION['user']))
    header('Location: ../index.php');

include '../db_connect.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: view_students.php');
    exit();
}

$error = '';
$success = '';

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    $_SESSION['msg'] = "Student not found!";
    header('Location: view_students.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'] ?? '';
    $course = $_POST['course'] ?? '';
    $subjects = implode(',', $_POST['subjects'] ?? []);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name && $email) {
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, dob=?, gender=?, course=?, subjects=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $name, $email, $dob, $gender, $course, $subjects, $phone, $address, $id);

        if ($stmt->execute())
            $success = "Student updated successfully! <a href='view_students.php'>View Students</a>";
        else
            $error = "DB Error: " . $stmt->error;

        $stmt->close();
    } else
        $error = "Name and Email are required!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/css/add_student.css">
</head>

<body>

    <div class="container">
        <h2 class="page-title">Edit Student</h2>

        <?php
        if ($error)
            echo "<p class='error-msg'>$error</p>";
        if ($success)
            echo "<p class='success-msg'>$success</p>";
        ?>

        <form method="post" class="student-form">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($student['email']); ?>"
                required>

            <label for="dob">DOB:</label>
            <input type="date" name="dob" id="dob" value="<?php echo $student['dob']; ?>">

            <label>Gender:</label>
            <div class="gender-options">
                <input type="radio" name="gender" value="Male" id="male" <?php echo ($student['gender'] == 'Male') ? 'checked' : ''; ?>>
                <label for="male">Male</label>
                <input type="radio" name="gender" value="Female" id="female" <?php echo ($student['gender'] == 'Female') ? 'checked' : ''; ?>>
                <label for="female">Female</label>
            </div>

            <label for="course">Course:</label>
            <select name="course" id="course">
                <option value="">Select</option>
                <?php
                $courses = ["BCA", "MCA", "BSc Computer Science", "MSc Computer Science", "BBA", "MBA"];
                foreach ($courses as $c) {
                    $sel = ($student['course'] == $c) ? 'selected' : '';
                    echo "<option value='$c' $sel>$c</option>";
                }
                ?>
            </select>

            <label>Subjects:</label>
            <div class="subjects-options">
                <?php
                $allSubjects = ["DBMS", "PHP", "C++", "Java", "Web"];
                $studentSubjects = explode(',', $student['subjects']);
                foreach ($allSubjects as $sub) {
                    $chk = in_array($sub, $studentSubjects) ? 'checked' : '';
                    echo "<input type='checkbox' name='subjects[]' value='$sub' $chk> <label>$sub</label>";
                }
                ?>
            </div>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">

            <label for="address">Address:</label>
            <textarea name="address" id="address"><?php echo htmlspecialchars($student['address']); ?></textarea>

            <input type="submit" value="Update Student" class="btn-submit">
        </form>
    </div>

</body>

</html>