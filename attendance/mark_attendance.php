<?php
session_start();

if (!isset($_SESSION['user']))
    header('Location: ../index.php');

include '../db_connect.php';

$date = $_GET['date'] ?? date('Y-m-d');

$res = mysqli_query($conn, "SELECT * FROM students ORDER BY name ASC");
if (!$res) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../assets/css/attendance.css">
</head>

<body>

<div class="container">
    <h2 class="page-title">Mark Attendance for <?php echo date('d-M-Y', strtotime($date)); ?></h2>

    <?php
    if (isset($_SESSION['msg'])) {
        echo '<p class="success-msg">' . $_SESSION['msg'] . '</p>';
        unset($_SESSION['msg']);
    }
    ?>

    <form method="post" action="save_attendance.php" class="attendance-form">
        <input type="hidden" name="date" value="<?php echo $date; ?>">

        <div class="table-container">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>P</th>
                        <th>A</th>
                        <th>L</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td data-label="P">
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="P" required>
                            </td>
                            <td data-label="A">
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="A">
                            </td>
                            <td data-label="L">
                                <input type="radio" name="status[<?php echo $row['id']; ?>]" value="L">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="btn-container">
            <input type="submit" value="Save Attendance" class="btn-submit">
        </div>
    </form>
</div>

</body>
</html>
