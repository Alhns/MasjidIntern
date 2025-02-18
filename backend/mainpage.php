<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../frontend/login.html"); // Redirect to login if not logged in
    exit();
}

date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to GMT+8
$firstDay = date('Y-m-01'); // First day of current month
$lastDay = date('Y-m-t');   // Last day of current month

$masjid_id = $_SESSION['masjid_id'];

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

// Fetch the status of Form 1
try {
    $sql = "
    SELECT f.*, 
           u.name AS user_name, 
           u.ic AS user_ic, 
           u.phone AS user_phone, 
           u.address AS user_address, 
           u.job AS user_job, 
           u.masjid_id, 
           m.masjid_name, 
           v1.name AS verify_name_1, 
           v2.name AS verify_name_2, 
           v3.name AS verify_name_3
    FROM form f 
    JOIN user u ON f.ic = u.ic
    JOIN masjid m ON u.masjid_id = m.masjid_id 
    LEFT JOIN user v1 ON f.verify_id_1 = v1.user_id
    LEFT JOIN user v2 ON f.verify_id_2 = v2.user_id
    LEFT JOIN user v3 ON f.verify_id_3 = v3.user_id
    WHERE DATE(f.reg_date) BETWEEN :firstdate AND :lastdate
    AND m.masjid_id = :masjid_id
    ORDER BY f.total_vote DESC
";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'firstdate' => $firstDay,
        'lastdate' => $lastDay,
        'masjid_id' => $masjid_id
    ]);
    $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            position: relative;
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
        .cancel-btn {
            padding: 5px 10px;
            font-size: 14px;
            border: none;
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .cancel-btn:hover {
            background-color: #c82333;
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
        <button type="button" disabled>Mesyuarat Agung Tahunan</button>
        <a href="../frontend/choosedate.html">
            <button type="button">Mesyuarat Agung Pencalonan Jawatankuasa Kariah</button>
        </a>
        <a href="../backend/form1_masjid.php">
            <button type="button">Form 1</button>
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
            <th>Comment</th>
            <th>Action</th>
        </tr>
        <?php if (empty($bookings)): ?>
            <tr><td colspan="7">No bookings found.</td></tr>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                    <td><?php echo htmlspecialchars($booking['date']); ?></td>
                    <td><?php echo date('H:i', strtotime($booking['time'])); ?></td>
                    <td><?php echo htmlspecialchars($booking['place']); ?></td>
                    <td>
                        <?php
                        if ($booking['status_code'] == 0) {
                            echo "Pending";
                        } elseif ($booking['status_code'] == 1) {
                            echo "Approved";
                        } elseif ($booking['status_code'] == 2) {
                            echo "Rejected";
                        } else {
                            echo "Adjusted";
                        }
                        ?>
                    </td>
                    <td><?php echo !empty($booking['comment']) ? htmlspecialchars($booking['comment']) : '-'; ?></td>
                    <td>
                        <?php if ($booking['status_code'] == 0): ?>
                            <form action="../backend/cancel_booking.php" method="POST">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                <button type="submit" class="cancel-btn">Cancel</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <h1 style="text-align: center; margin-top: 30px;">Form 1</h1>

    <?php if (!empty($forms)): ?>
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>IC</th>
                    <th>Phone</th>
                    <th>Masjid Name</th>
                    <th>Vote</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forms as $form): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($form['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($form['user_ic']); ?></td>
                        <td><?php echo htmlspecialchars($form['user_phone']); ?></td>
                        <td><?php echo htmlspecialchars($form['masjid_name']); ?></td>
                        <td><?php echo htmlspecialchars($form['total_vote']); ?></td>
                        <td><?php echo htmlspecialchars($form['role']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No Form 1 records found.</p>
    <?php endif; ?>
    </table>
    <h1 style="text-align: center; margin-top: 30px;">Form 2 Status</h1>
    
    <?php if (!empty($forms)): ?>
        <table class="booking-table">
            <thead>
                <tr>
                <th>No</th>
                <th>Name</th>
                <th>IC</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Job</th>
                <th>Masjid Name</th>
                <th>Verify Name 1</th>
                <th>Verify Name 2</th>
                <th>Verify Name 3</th>
                <th>Role</th>
                <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($forms as $form): 
                    // Conditional display based on status_code
                    $statusText = 'none';
                    if ($form['status_code'] == 2) {
                        $statusText = 'APPROVED BY PTA';
                    } elseif ($form['status_code'] == 3) {
                        $statusText = 'APPROVED BY JHEPP';
                    }
                ?>
                    <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($form['name']); ?></td>
                    <td><?php echo htmlspecialchars($form['ic']); ?></td>
                    <td><?php echo htmlspecialchars($form['phone_num']); ?></td>
                    <td><?php echo htmlspecialchars($form['address']); ?></td>
                    <td><?php echo htmlspecialchars($form['job']); ?></td>
                    <td><?php echo htmlspecialchars($form['masjid_name']); ?></td>
                    <td><?php echo htmlspecialchars($form['verify_name_1']); ?></td>
                    <td><?php echo htmlspecialchars($form['verify_name_2']); ?></td>
                    <td><?php echo htmlspecialchars($form['verify_name_3']); ?></td>
                    <td><?php echo htmlspecialchars($form['role']); ?></td>
                    <td><?php echo $statusText; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; margin-top: 30px;">No results found for the selected date range.</p>
    <?php endif; ?>

</body>
</html>