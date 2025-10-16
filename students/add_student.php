<?php
session_start();
if (!isset($_SESSION['user']))
    header('Location: ../index.php');

include '../db_connect.php';
$error = '';
$success = '';

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
        $stmt = $conn->prepare("INSERT INTO students(name,email,dob,gender,course,subjects,phone,address) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssss", $name, $email, $dob, $gender, $course, $subjects, $phone, $address);

        if ($stmt->execute())
            $success = "Student added successfully! <a href='view_students.php'>View Students</a>";
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
    <title>Add Student</title>
    <link rel="stylesheet" href="../assets/css/add_student.css">
</head>

<body>

    <div class="container">
        <h2 class="page-title">Add Student</h2>

        <?php
            if ($error)
                echo "<p class='error-msg'>$error</p>";
            if ($success)
                echo "<p class='success-msg'>$success</p>";
        ?>

        <form method="post" class="student-form">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="dob">DOB:</label>
            <input type="date" name="dob" id="dob">

            <label>Gender:</label>
            <div class="gender-options">
                <input type="radio" name="gender" value="Male" id="male"> <label for="male">Male</label>
                <input type="radio" name="gender" value="Female" id="female"> <label for="female">Female</label>
            </div>

            <label for="course">Course:</label>
            <select name="course" id="course">
                <option value="">Select</option>
                <option value="BCA">BCA</option>
                <option value="MCA">MCA</option>
                <option value="BSc Computer Science">BSc Computer Science</option>
                <option value="MSc Computer Science">MSc Computer Science</option>
                <option value="BBA">BBA</option>
                <option value="MBA">MBA</option>
            </select>

            <label>Subjects:</label>
            <div class="subjects-options">
                <input type="checkbox" name="subjects[]" value="DBMS" id="dbms"> <label for="dbms">DBMS</label>
                <input type="checkbox" name="subjects[]" value="PHP" id="php"> <label for="php">PHP</label>
                <input type="checkbox" name="subjects[]" value="C++" id="cpp"> <label for="cpp">C++</label>
                <input type="checkbox" name="subjects[]" value="Java" id="java"> <label for="java">Java</label>
                <input type="checkbox" name="subjects[]" value="Web" id="web"> <label for="web">Web</label>
            </div>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone">

            <label for="address">Address:</label>
            <textarea name="address" id="address"></textarea>

            <input type="submit" value="Add Student" class="btn-submit">
        </form>
    </div>

</body>

</html>
