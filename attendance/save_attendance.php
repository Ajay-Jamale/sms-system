<?php
    session_start();
    if(!isset($_SESSION['user'])) header('Location: ../index.php');

    include '../db_connect.php';
    
    $date = $_POST['date'] ?? date('Y-m-d');
    $status = $_POST['status'] ?? [];

    if (empty($status)) {
        $_SESSION['msg'] = "No attendance data submitted!";
        header("Location: mark_attendance.php?date=$date");
        exit();
    }

    $stmtDelete = $conn->prepare("DELETE FROM attendance WHERE attendance_date = ?");
    $stmtDelete->bind_param("s", $date);
    $stmtDelete->execute();
    $stmtDelete->close();

    $stmtInsert = $conn->prepare("INSERT INTO attendance(student_id, attendance_date, status) VALUES (?, ?, ?)");

    foreach($status as $sid => $val){
        $sid = intval($sid);
        $val = in_array($val, ['P','A','L']) ? $val : 'A';
        $stmtInsert->bind_param("iss", $sid, $date, $val);
        $stmtInsert->execute();
    }

    $stmtInsert->close();

    $_SESSION['msg'] = "Attendance saved successfully!";
    header("Location: mark_attendance.php?date=$date");
    exit();
?>