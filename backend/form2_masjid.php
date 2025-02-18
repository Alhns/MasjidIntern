<?php
session_start();
require '../backend/connection.php'; // Adjust path if needed

// Get the current date
date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to GMT+8
$current_date = date('Y-m-d'); // Get current date and time in GMT+8
$masjid_id = $_SESSION['masjid_id'];

// Query to check if a booking exists for today
$sql = "SELECT * FROM booking b 
JOIN user u ON b.user_id = u.user_id
JOIN masjid m ON u.masjid_id = m.masjid_id 
WHERE b.date = :booking_date AND m.masjid_id = :masjid_id AND b.status_code = 1 ";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'booking_date' => $current_date,
    'masjid_id' => $masjid_id
]);

$booking = $stmt->fetch(PDO::FETCH_ASSOC);
/*
$debug_query = str_replace(
    [':booking_date', ':masjid_id'],
    ["'".$current_date."'", "'".$masjid_id."'"],
    $sql
);
echo "Debug SQL Query: " . $debug_query . "<br>"; // This helps in debugging
*/
if (!$booking) {
    header("Location: mainpage.php");
    exit();
}
try {
    // Define the SQL query
    $query = "SELECT f.*, u.*, m.*
        FROM form f 
        JOIN user u ON u.ic = f.ic
        JOIN masjid m ON u.masjid_id = m.masjid_id
        WHERE DATE(f.date) = :current_date AND m.masjid_id = $masjid_id
        ORDER BY f.total_vote DESC LIMIT 10";

    $stmt = $conn->prepare($query);

    // Bind the parameter
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);

 /*   // Debugging: Display the SQL query with actual values
    $debug_query = str_replace(":current_date", "'$current_date'", $query);
    echo "<pre>Executing Query: " . $debug_query . "</pre>";
*/
    $stmt->execute();
    
    // Fetch the results
    $bookings2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

   /* // Debugging: Print the retrieved data
    echo "<pre>Fetched Results: ";
    print_r($bookings2);
    echo "</pre>";
*/
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Form 2</title>
    <script type="text/javascript" src="../Script/ajax.js"></script>
</head>
<body>

<?php require '../include/header.php'; ?>
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
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-primary text-white">
                <tr>
                    <th>Name</th>
                    <th>Masjid</th>
                    <th>IC</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Job</th>
                    <th>Total Vote</th>
                    </thead>
                    <tbody>
        <?php foreach ($bookings2 as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                <td><?php echo htmlspecialchars($booking['masjid_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['ic']); ?></td>
                <td><?php echo htmlspecialchars($booking['phone_num']); ?></td>
                <td><?php echo htmlspecialchars($booking['address']); ?></td>
                <td><?php echo htmlspecialchars($booking['job']); ?></td>
                <td><?php echo htmlspecialchars($booking['total_vote']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back to Main Page -->
    <div class="text-center mt-4">
    <a href="../backend/mainpage.php" class="btn btn-primary">Back to Main Page</a>
</div>

    <?php require '../include/footer.php'; ?>
</body>
</html>
