<?php
session_start();
if (!isset($_SESSION['user']))
    header('Location: ../index.php');

include '../db_connect.php';

$date = $_GET['date'] ?? date('Y-m-d');

$query = "SELECT s.id, s.name, COALESCE(a.status, '-') as status
          FROM students s
          LEFT JOIN attendance a 
          ON s.id = a.student_id AND a.attendance_date = ?
          ORDER BY s.name ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>

    <style>
        /* ---------- Global Styles ---------- */
        body {
            font-family: "Poppins", Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            color: #2e7d32;
            font-size: 26px;
        }

        /* ---------- Form ---------- */
        form {
            text-align: center;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            margin-right: 8px;
        }

        input[type="date"] {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-right: 5px;
            font-size: 15px;
        }

        input[type="submit"] {
            background: #4CAF50;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 15px;
        }

        input[type="submit"]:hover {
            background: #388e3c;
        }

        /* ---------- Table Container ---------- */
        .table-container {
            width: 90%;
            margin: 0 auto 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        /* ---------- Table ---------- */
        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: #4CAF50;
            color: white;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Alternate Row Colors */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* tr:hover {
            background-color: #e8f5e9;
        } */

        /* ---------- Status Colors ---------- */
        td[data-label="Status"] {
            font-weight: 600;
        }

        .status-present {
            color: #1b8a3d;
        }

        .status-absent {
            color: #d32f2f;
        }

        .status-leave {
            color: #f57c00;
        }

        /* ---------- Responsive Table ---------- */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 12px;
                border-bottom: 1px solid #eee;
            }

            td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #4CAF50;
            }

            td:last-child {
                border-bottom: none;
            }
        }

        /* ---------- Message ---------- */
        p {
            text-align: center;
            font-weight: 500;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <h2>View Attendance for <?php echo date('d-M-Y', strtotime($date)); ?></h2>

    <form method="get" action="">
        <label for="date">Select Date:</label>
        <input type="date" name="date" id="date" value="<?php echo $date; ?>">
        <input type="submit" value="View">
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td data-label="Status">
                            <?php
                            switch ($row['status']) {
                                case 'P':
                                    echo '<span class="status-present">Present</span>';
                                    break;
                                case 'A':
                                    echo '<span class="status-absent">Absent</span>';
                                    break;
                                case 'L':
                                    echo '<span class="status-leave">Leave</span>';
                                    break;
                                default:
                                    echo '-';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
