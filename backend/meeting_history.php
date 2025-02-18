<?php
session_start();
include('../backend/connection.php'); // Include database connection

try {
    // Retrieve all meeting records
    $stmtMeetings = $conn->prepare("
        SELECT meeting_date, meeting_time, meeting_place, form_id, meeting_part 
        FROM meeting 
        ORDER BY meeting_date DESC
    ");
    $stmtMeetings->execute();
    $meetings = $stmtMeetings->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting History</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: auto;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<?php require '../include/header.php'; ?>
<div class="container">
    <h2>Meeting History</h2>

    <?php if ($meetings): ?>
        <table>
            <tr>
                <th>Meeting Date</th>
                <th>Meeting Time</th>
                <th>Meeting Place</th>
                <th>Form ID</th>
                <th>Participants</th>
            </tr>
            <?php foreach ($meetings as $meeting): ?>
                <tr>
                    <td><?php echo htmlspecialchars($meeting['meeting_date']); ?></td>
                    <td><?php echo htmlspecialchars($meeting['meeting_time']); ?></td>
                    <td><?php echo htmlspecialchars($meeting['meeting_place']); ?></td>
                    <td><?php echo htmlspecialchars($meeting['form_id']); ?></td>
                    <td><?php echo htmlspecialchars($meeting['meeting_part']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No meeting history available.</p>
    <?php endif; ?>

    <a href="form_JHEPP.php" class="back-btn">Back</a>
</div>
<?php require '../include/footer.php'; ?>
</body>
</html>
