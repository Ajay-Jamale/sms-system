<?php
session_start();
if(!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';

// Fetch all students
$res = mysqli_query($conn,"SELECT * FROM students ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Students List</title>
<link rel="stylesheet" href="../assets/css/students_list.css">
</head>
<body>

<div class="container">
    <h2 class="page-title">Students List</h2>

    <?php
    if(isset($_SESSION['msg'])){
        echo '<p class="success-msg">'.$_SESSION['msg'].'</p>';
        unset($_SESSION['msg']);
    }
    ?>

    <div class="add-btn-container">
        <a href="add_student.php" class="btn-add">Add Student</a>
    </div>

    <div class="table-responsive">
        <table class="students-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Subjects</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td data-label="ID"><?php echo $row['id']; ?></td>
                    <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td data-label="Course"><?php echo $row['course']; ?></td>
                    <td data-label="Subjects"><?php echo $row['subjects']; ?></td>
                    <td data-label="Phone"><?php echo $row['phone']; ?></td>
                    <td data-label="Actions">
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="action-btn edit">Edit</a>
                        <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
