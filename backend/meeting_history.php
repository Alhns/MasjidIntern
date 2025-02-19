<?php
session_start();
include('../backend/connection.php'); // Include database connection

try {
    // Retrieve all distinct form IDs
    $stmtForms = $conn->prepare("SELECT DISTINCT form_id FROM meeting ORDER BY form_id ASC");
    $stmtForms->execute();
    $forms = $stmtForms->fetchAll(PDO::FETCH_ASSOC);

    // Default SQL query (show all meetings)
    $sql = "SELECT meeting_date, meeting_time, meeting_place, form_id, meeting_part FROM meeting ORDER BY meeting_date DESC";
    $stmtMeetings = $conn->prepare($sql);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: auto;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
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

    <!-- Dropdown to filter by form_id -->
    <label for="formFilter" class="form-label">Filter by Form ID:</label>
    <select id="formFilter" class="form-select">
        <option value="">Show All</option>
        <?php foreach ($forms as $form): ?>
            <option value="<?php echo htmlspecialchars($form['form_id']); ?>">
                <?php echo htmlspecialchars($form['form_id']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Meeting Table -->
    <div id="meetingTable">
        <table>
            <thead>
                <tr>
                    <th>Meeting Date</th>
                    <th>Meeting Time</th>
                    <th>Meeting Place</th>
                    <th>Form ID</th>
                    <th>Participants</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($meetings): ?>
                    <?php foreach ($meetings as $meeting): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($meeting['meeting_date']); ?></td>
                            <td><?php echo htmlspecialchars($meeting['meeting_time']); ?></td>
                            <td><?php echo htmlspecialchars($meeting['meeting_place']); ?></td>
                            <td><?php echo htmlspecialchars($meeting['form_id']); ?></td>
                            <td><?php echo htmlspecialchars($meeting['meeting_part']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No meeting history available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="form_JHEPP.php" class="back-btn">Back</a>
</div>

<?php require '../include/footer.php'; ?>

<script>
$(document).ready(function() {
    $("#formFilter").change(function() {
        var formId = $(this).val(); // Get selected form_id

        $.ajax({
            url: "fetch_meetings.php", // Fetch data dynamically
            type: "POST",
            data: { form_id: formId },
            success: function(response) {
                $("#meetingTable").html(response); // Update table dynamically
            }
        });
    });
});
</script>

</body>
</html>
