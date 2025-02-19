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
    <title>Form Data for <?php echo htmlspecialchars($masjidName); ?></title>
</head>
<body>
<?php require '../include/header.php'; ?>
    <h2 class="text-center">Form Data for <?php echo htmlspecialchars($masjidName); ?></h2>
    <p class="text-center">Showing records from <strong><?php echo $firstDay; ?></strong> to <strong><?php echo $lastDay; ?></strong></p>

    <?php if (!empty($searchResults)): ?>
        <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-primary text-white">
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

    <div class="text-center">
            <button onclick="window.location.href = 'form_PTA.php'" class="btn btn-primary mb-2">Back</button>
        </div>
        

        <?php require '../include/footer.php'; ?>
</body>
</html>
