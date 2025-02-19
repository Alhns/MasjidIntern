<?php
include('../backend/connection.php');

$formId = isset($_POST['form_id']) ? $_POST['form_id'] : "";

// Base SQL query
$sql = "SELECT meeting_date, meeting_time, meeting_place, form_id, meeting_part FROM meeting";

// If a specific form_id is selected, filter results
if (!empty($formId)) {
    $sql .= " WHERE form_id = :form_id";
}

$sql .= " ORDER BY meeting_date DESC";

$stmt = $conn->prepare($sql);

// Bind form_id if filtering
if (!empty($formId)) {
    $stmt->bindParam(':form_id', $formId, PDO::PARAM_INT);
}

$stmt->execute();
$meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
