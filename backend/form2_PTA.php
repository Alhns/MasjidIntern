<?php
session_start();
require '../backend/connection.php'; // Adjust path if needed

// Get the current date
date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to GMT+8
$current_date = date('Y-m-d'); // Get current date and time in GMT+8
$masjid_id = $_SESSION['masjid_id'];

/*
$debug_query = str_replace(
    [':booking_date', ':masjid_id'],
    ["'".$current_date."'", "'".$masjid_id."'"],
    $sql
);
echo "Debug SQL Query: " . $debug_query . "<br>"; // This helps in debugging
*/

try {
    // Define the SQL query
    $query = "SELECT 
    f.ic AS form_ic, 
    f.name AS form_name, 
    f.reg_date AS form_reg_date, 
    f.phone_num AS form_phone, 
    f.address AS form_address, 
    f.job AS form_job, 
    f.total_vote AS form_total_vote,
    f.role AS role,
    u.name AS user_name, 
    u.ic AS user_ic, 
    u.phone AS user_phone, 
    u.address AS user_address, 
    u.job AS user_job, 
    m.masjid_id AS masjid_id, 
    m.masjid_name AS masjid_name
FROM form f 
JOIN user u ON u.ic = f.ic
JOIN masjid m ON u.masjid_id = m.masjid_id";


    $stmt = $conn->prepare($query);


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
            <th>Name</th>
            <th>Masjid</th>
            <th>IC</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Job</th>
            <th>Total Vote</th>
            <th>Date</th>
            <th>Role</th>
        </tr>
        <?php foreach ($bookings2 as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['form_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['masjid_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['form_ic']); ?></td>
                <td><?php echo htmlspecialchars($booking['form_phone']); ?></td>
                <td><?php echo htmlspecialchars($booking['form_address']); ?></td>
                <td><?php echo htmlspecialchars($booking['form_job']); ?></td>
                <td><?php echo htmlspecialchars($booking['form_total_vote']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($booking['form_reg_date'])); ?></td>
                <td>
                <select name="role" onchange="updateRole(this, '<?php echo $booking['user_ic']; ?>')">
                <option value="Pengerusi" <?php echo ($booking['role'] == 'Pengerusi') ? 'selected' : ''; ?>>Pengerusi</option>
                <option value="Naib Pengerusi" <?php echo ($booking['role'] == 'Naib Pengerusi') ? 'selected' : ''; ?>>Naib Pengerusi</option>
                <option value="Setiausaha" <?php echo ($booking['role'] == 'Setiausaha') ? 'selected' : ''; ?>>Setiausaha</option>
                </select>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back to Main Page -->
    <a href="../frontend/mainpage2.html" class="back-btn">Back to Main Page</a>
</body>
</html>
