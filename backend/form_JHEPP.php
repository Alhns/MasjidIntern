<?php
session_start();
include('../backend/connection.php'); // Include database connection

try {
    // Fetch all daerah names
    $stmt = $conn->prepare("SELECT * FROM daerah ORDER BY daerah_id");
    $stmt->execute();
    $daerahs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch all masjids along with their daerah names
    $stmt = $conn->prepare("SELECT m.*, d.daerah_name FROM masjid m
        JOIN daerah d ON m.daerah_id = d.daerah_id
        ORDER BY d.daerah_id, m.masjid_id");
    $stmt->execute();
    $masjids = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Initialize daerahMasjids with all available daerah names
$daerahMasjids = [];
foreach ($daerahs as $daerah) {
    $daerahMasjids[$daerah['daerah_name']] = []; // Ensure all daerahs appear
}

// Organize masjids by daerah_name
foreach ($masjids as $masjid) {
    $daerahMasjids[$masjid['daerah_name']][] = $masjid;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Masjid Mengikut Daerah</title>
    <link rel="stylesheet" href="../Styles/styles.css">
    <script src="<?php echo '../Script/dropdown_pta.js'; ?>"></script>
</head>
<body>

<?php require '../include/header.php'; ?>
        
        <?php foreach ($daerahMasjids as $daerahName => $masjids): ?>
            <div class="mb-3">
                <button class="btn btn-primary w-100 mt-2 text-start" onclick="toggleMasjidList('<?php echo htmlspecialchars($daerahName); ?>')">
                    <?php echo htmlspecialchars($daerahName); ?>
                </button>
                <div class="masjid-list" id="masjid-list-<?php echo htmlspecialchars($daerahName); ?>" style="display: none;">
                    <ul class="list-group mt-2">
                        <?php if (!empty($masjids)): ?>
                            <?php foreach ($masjids as $masjid): ?>
                                <li class="list-group-item list-group-item-action" onclick="openFormSelection('<?php echo htmlspecialchars($masjid['masjid_name']); ?>')">
                                    <?php echo htmlspecialchars($masjid['masjid_id'] . " - " . $masjid['masjid_name']); ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No masjid available</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="../backend/mainpage3.php" class="btn btn-secondary"> Back to Main Page</a>
        </div>
    </div>
            </button>
        </a>
    </div>
</body>
</html>
