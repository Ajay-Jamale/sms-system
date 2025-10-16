<?php
session_start();
if (!isset($_SESSION['user'])) header('Location: ../index.php');

include '../db_connect.php';

$query = "SELECT s.name,
        COUNT(CASE WHEN a.status='P' THEN 1 END) AS present,
        COUNT(CASE WHEN a.status='A' THEN 1 END) AS absent,
        COUNT(CASE WHEN a.status='L' THEN 1 END) AS `leave`
        FROM students s
        LEFT JOIN attendance a ON s.id = a.student_id
        GROUP BY s.id";

$res = mysqli_query($conn, $query);

if (!$res) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* === Page Container === */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* === Page Title === */
        .page-title {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 26px;
            letter-spacing: 0.5px;
        }

        /* === Table Wrapper === */
        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }

        /* === Attendance Table === */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #ddd;
            padding: 12px 10px;
            text-align: center;
            font-size: 15px;
        }

        .attendance-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .attendance-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .attendance-table tr:hover {
            background-color: #f1f1f1;
        }

        /* === Status Colors === */
        .attendance-table td[data-label="Present"] {
            color: #1b8a3d;
            font-weight: bold;
        }

        .attendance-table td[data-label="Absent"] {
            color: #d32f2f;
            font-weight: bold;
        }

        .attendance-table td[data-label="Leave"] {
            color: #f57c00;
            font-weight: bold;
        }

        /* === Responsive === */
        @media(max-width: 768px) {
            .attendance-table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                background: #fff;
                margin-bottom: 15px;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 10px 15px;
                border-bottom: 1px solid #eee;
            }

            td:last-child {
                border-bottom: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #333;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="page-title">Student Attendance Report</h2>

        <div class="table-responsive">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Leave</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td data-label="Name"><?= htmlspecialchars($row['name']); ?></td>
                            <td data-label="Present"><?= $row['present']; ?></td>
                            <td data-label="Absent"><?= $row['absent']; ?></td>
                            <td data-label="Leave"><?= $row['leave']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
