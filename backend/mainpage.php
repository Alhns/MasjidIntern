<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../frontend/login.html"); // Redirect to login if not logged in
    exit();
}

// Include database connection
require '../backend/connection.php';

// Fetch bookings for the logged-in user
try {
    $stmt = $conn->prepare("SELECT * FROM booking WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
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
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
        }
        .header {
            background-color: green;
            padding: 10px;
            color: white;
            text-align: center;
        }
        .buttons-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            background-color: red;
        }
        .booking-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        .booking-table th, .booking-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .booking-table th {
            background: #007BFF;
            color: white;
        }
        .booking-table tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1> 
        <form action="../backend/logout.php" method="POST">
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>

    <div class="buttons-container">
        <!-- Button for Mesyuarat Agung Tahunan (No action) -->
        <button type="button" disabled>Mesyuarat Agung Tahunan</button>

        <!-- Button for Mesyuarat Agung Pencalonan Jawatankuasa Kariah (Redirect to choosedate.html) -->
        <a href="../frontend/choosedate.html">
            <button type="button">Mesyuarat Agung Pencalonan Jawatankuasa Kariah</button>
        </a>
    </div>

    <h2 style="text-align: center; margin-top: 30px;">Your Booking Status</h2>

    <table class="booking-table">
        <tr>
            <th>Booking ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Place</th>
            <th>Status</th>
        </tr>
        <?php if (empty($bookings)): ?>
            <tr><td colspan="5">No bookings found.</td></tr>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                    <td><?php echo htmlspecialchars($booking['date']); ?></td>
                    <td><?php echo htmlspecialchars($booking['time']); ?></td>
                    <td><?php echo htmlspecialchars($booking['place']); ?></td>
                    <td>
                        <?php
                        if ($booking['status_code'] == 0) {
                            echo "Pending";
                        } elseif ($booking['status_code'] == 1) {
                            echo "Approved";
                        } else {
                            echo "Rejected";
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

</body>
</html>
