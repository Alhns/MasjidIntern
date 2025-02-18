<?php
session_start();
include('connection.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $meetingDate = htmlspecialchars($_POST['meeting_date']);
    $meetingTime = htmlspecialchars($_POST['meeting_time']);
    $meetingPlace = htmlspecialchars($_POST['meeting_place']);
    $formId = htmlspecialchars($_POST['form_id']);
    $meetingParts = htmlspecialchars($_POST['meeting_part']);  // Sanitize participant input

    // Validate input data
    if (empty($meetingDate) || empty($meetingTime) || empty($meetingPlace) || empty($meetingParts) || empty($formId)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Prepare the array of participants
        $participants = explode(',', $meetingParts); // Split into an array by commas

        try {
            // Check if form_id exists in the form table
            $stmt = $conn->prepare("SELECT form_id FROM form WHERE form_id = :form_id");
            $stmt->bindParam(':form_id', $formId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Form ID exists, continue with inserting meeting data
                foreach ($participants as $participant) {
                    $participant = trim($participant); // Trim whitespace from each participant

                    // Insert the participant into the meeting table
                    $sql = "INSERT INTO meeting (meeting_date, meeting_time, meeting_place, form_id, meeting_part) 
                            VALUES (:meeting_date, :meeting_time, :meeting_place, :form_id, :meeting_part)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        'meeting_date' => $meetingDate,
                        'meeting_time' => $meetingTime,
                        'meeting_place' => $meetingPlace,
                        'form_id' => $formId,
                        'meeting_part' => $participant
                    ]);
                }

                echo "<script>alert('Meeting participants added successfully.'); window.location.href='meeting_PTA.php';</script>";
            } else {
                echo "<script>alert('Invalid Form ID.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error saving data: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

// Fetch form data for dropdown
$sql = "SELECT form_id FROM form";  // Only fetch form_id
$stmt = $conn->prepare($sql);
$stmt->execute();
$formResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Meeting Data</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
    <h1>Insert Meeting Data</h1>

    <form method="POST" action="">
        <label for="meeting_date">Tarikh Mesyuarat:</label>
        <input type="date" id="meeting_date" name="meeting_date" required>

        <label for="meeting_time">Masa:</label>
        <input type="time" id="meeting_time" name="meeting_time" required>

        <label for="meeting_place">Tempat:</label>
        <input type="text" id="meeting_place" name="meeting_place" required>

        <label for="meeting_part">Ahli Mesyuarat (separate by commas):</label>
        <textarea id="meeting_part" name="meeting_part" required></textarea>

        <label for="form_id">Select Form:</label>
        <select name="form_id" id="form_id" required>
            <option value="" disabled selected>Select a Form</option>
            <?php foreach ($formResults as $form): ?>
                <option value="<?php echo $form['form_id']; ?>"><?php echo $form['form_id']; ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save Meeting</button>
    </form>

    <a href="form_PTA.php">Back to Mainpage</a>
</body>
</html>
