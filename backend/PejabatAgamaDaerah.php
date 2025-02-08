<?php
session_start();
require '../backend/connection.php'; // Adjust path if needed

// Fetch all booking records from the database
try {
    $stmt = $conn->prepare("SELECT * FROM booking");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            padding: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        select {
            padding: 5px;
            font-size: 14px;
        }
        .update-btn {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .update-btn:hover {
            background-color: #218838;
        }
        .back-btn {
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .back-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <!-- Show Alert Message (if any) -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']); // Clear the message after displaying
    }

    if (isset($_SESSION['error'])) {
        echo "<script>alert('" . $_SESSION['error'] . "');</script>";
        unset($_SESSION['error']); // Clear the error after displaying
    }
    ?>

    <h2>Booking List</h2>

    <table>
        <tr>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Place</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                <td><?php echo htmlspecialchars($booking['user_id']); ?></td>
                <td><?php echo htmlspecialchars($booking['date']); ?></td>
                <td><?php echo htmlspecialchars($booking['time']); ?></td>
                <td><?php echo htmlspecialchars($booking['place']); ?></td>
                <td>
                    <form action="updatestatus.php" method="POST">
                        <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                        <select name="status_code">
                            <option value="0" <?php echo ($booking['status_code'] == 0) ? 'selected' : ''; ?>>Pending</option>
                            <option value="1" <?php echo ($booking['status_code'] == 1) ? 'selected' : ''; ?>>Approved</option>
                            <option value="2" <?php echo ($booking['status_code'] == 2) ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back to Main Page -->
    <a href="../frontend/mainpage2.html" class="back-btn">Back to Main Page</a>

</body>
</html>
