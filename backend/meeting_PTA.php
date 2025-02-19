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
    $meetingParts = htmlspecialchars($_POST['meeting_part']);

    // Validate input data
    if (empty($meetingDate) || empty($meetingTime) || empty($meetingPlace) || empty($meetingParts) || empty($formId)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        $participants = explode(',', $meetingParts); // Split participants by comma

        try {
            // Check if form_id exists
            $stmt = $conn->prepare("SELECT form_id FROM form WHERE form_id = :form_id");
            $stmt->bindParam(':form_id', $formId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Insert each participant
                foreach ($participants as $participant) {
                    $participant = trim($participant); // Remove spaces

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

                echo "<script>alert('Meeting added successfully!'); window.location.href='meeting_history.php';</script>";
            } else {
                echo "<script>alert('Invalid Form ID.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

// Fetch form data for dropdown
$sql = "SELECT form_id FROM form";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .form-label {
            font-weight: bold;
        }
        textarea {
            resize: none;
        }
        .btn-primary {
            width: 100%;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php require '../include/header.php'; ?>
<div class="container">
    <h2>Insert Meeting Data</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="meeting_date" class="form-label">Meeting Date:</label>
            <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
        </div>

        <div class="mb-3">
            <label for="meeting_time" class="form-label">Time:</label>
            <input type="time" class="form-control" id="meeting_time" name="meeting_time" required>
        </div>

        <div class="mb-3">
            <label for="meeting_place" class="form-label">Location:</label>
            <input type="text" class="form-control" id="meeting_place" name="meeting_place" placeholder="Enter location" required>
        </div>

        <div class="mb-3">
            <label for="meeting_part" class="form-label">Participants (comma-separated):</label>
            <textarea class="form-control" id="meeting_part" name="meeting_part" rows="3" placeholder="Enter participant names separated by commas" required></textarea>
        </div>

        <div class="mb-3">
            <label for="form_id" class="form-label">Select Form:</label>
            <select class="form-select" name="form_id" id="form_id" required>
                <option value="" disabled selected>Select a Form</option>
                <?php foreach ($formResults as $form): ?>
                    <option value="<?php echo htmlspecialchars($form['form_id']); ?>">
                        <?php echo htmlspecialchars($form['form_id']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Meeting</button>
    </form>

    <a href="form_PTA.php" class="back-btn">← Back to Main Page</a>
</div>
<?php require '../include/footer.php'; ?>
</body>
</html>
