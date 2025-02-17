<?php
session_start();
include('connection.php');

// Retrieve masjid_id from URL
$masjidID = isset($_GET['masjid_id']) ? intval($_GET['masjid_id']) : null;

// Check if masjid_id is valid
if (!$masjidID) {
    die("Error: Masjid ID is missing or invalid.");
}

// Get the first and last day of the current month
$firstDay = date('Y-m-01'); // Example: 2024-02-01
$lastDay = date('Y-m-t');   // Example: 2024-02-29

try {
    // Get the Masjid Name
    $stmtMasjid = $conn->prepare("SELECT masjid_name FROM masjid WHERE masjid_id = ?");
    $stmtMasjid->execute([$masjidID]);
    $masjidData = $stmtMasjid->fetch(PDO::FETCH_ASSOC);

    if (!$masjidData) {
        die("Error: Masjid not found.");
    }
    $masjidName = $masjidData['masjid_name'];

    // Query to retrieve form data for the selected masjid within the current month
    $stmt = $conn->prepare("
        SELECT f.*, u.name, u.ic, u.phone, u.address, u.job, u.masjid_id 
        FROM form f
        JOIN user u ON f.ic = u.ic
        WHERE u.masjid_id = ? 
        AND f.date BETWEEN ? AND ? 
        ORDER BY f.date DESC
    ");
    $stmt->execute([$masjidID, $firstDay, $lastDay]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styles2.css">
    <title>Form Data for <?php echo htmlspecialchars($masjidName); ?></title>
</head>
<body>

    <h2>Form Data for <?php echo htmlspecialchars($masjidName); ?></h2>
    <p>Showing records from <strong><?php echo $firstDay; ?></strong> to <strong><?php echo $lastDay; ?></strong></p>

    <?php if (!empty($searchResults)): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>IC</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Job</th>
                    <th>Total Vote</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($searchResults as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['ic']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['job']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_vote']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No records found for <?php echo htmlspecialchars($masjidName); ?> within this month.</p>
    <?php endif; ?>

        <!-- Back Button without passing masjid_id -->
        <button onclick="window.location.href = 'form_PTA.php'">Back</button>
</body>
</html>
