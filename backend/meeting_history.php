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
</head>
<body>

<?php require '../include/header.php'; ?>

<div class="container">
<div class="text-center">
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
</div>
   

    <!-- Meeting Table -->
    <div id="meetingTable">
    <table class="table table-bordered text-center">
    <thead class="table-primary text-white">
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
                            <td><?php echo date('H:i', strtotime($meeting['meeting_time'])); ?></td>
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

    <div class="text-center">
    <button onclick="window.location.href = 'form_JHEPP.php'" class="btn btn-primary mb-2">Back</button>
</div>

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
