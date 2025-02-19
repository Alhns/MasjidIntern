<?php
session_start();
include('connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Date</title>
    <style>
        .form-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body>
<?php require '../include/header.php'; ?>
<div class="container mt-5">
        <div class="card form-card mx-auto" style="max-width: 500px;">
            <h2 class="text-center mb-4">Choose Date for Meeting</h2>
            <form action="../backend/db_choosedate.php" method="POST">
                <div class="mb-3">
                    <label for="date" class="form-label">Select Date:</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Select Time:</label>
                    <input type="time" id="time" name="time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="place" class="form-label">Enter Place:</label>
                    <input type="text" id="place" name="place" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary mt-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <?php require '../include/footer.php'; ?>
</body>
</html>
