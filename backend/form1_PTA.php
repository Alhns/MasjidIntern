<?php
session_start();
include('../backend/connection.php'); // Include database connection

// Fetch masjid data from the database
try {
    $stmt = $conn->prepare("SELECT * FROM masjid ORDER BY daerah_id, masjid_id");
    $stmt->execute();
    $masjids = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Organize masjids by daerah_id
$daerahs = [
    1 => "Butterworth",
    2 => "Seberang Perai Selatan",
    3 => "Seberang Perai Tengah",
    4 => "Timor Laut",
    5 => "Barat Daya",
    6 => "Kepala Batas"
];

$daerahMasjids = [];
foreach ($masjids as $masjid) {
    $daerahMasjids[$masjid['daerah_id']][] = $masjid;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 1 PTA</title>
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
        .container {
            width: 80%;
            margin: 20px auto;
            text-align: center;
        }
        .daerah-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            display: block;
            width: 100%;
            text-align: left;
        }
        .daerah-button:hover {
            background-color: #0056b3;
        }
        .masjid-list {
            display: none;
            background: white;
            border: 1px solid #ddd;
            margin-top: 5px;
            padding: 10px;
        }
        .masjid-list ul {
            list-style: none;
            padding: 0;
        }
        .masjid-list li {
            padding: 5px;
            text-align: left;
        }
        .masjid-list li:hover {
            background-color: #f2f2f2;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleMasjidList(id) {
            var list = document.getElementById("masjid-list-" + id);
            if (list.style.display === "none" || list.style.display === "") {
                list.style.display = "block";
            } else {
                list.style.display = "none";
            }
        }
    </script>
</head>
<body>

    <div class="header">
        <h1>Senarai Masjid Mengikut Daerah</h1>
    </div>

    <div class="container">
        <?php foreach ($daerahs as $id => $name): ?>
            <button class="daerah-button" onclick="toggleMasjidList(<?php echo $id; ?>)">
                <?php echo $name; ?>
            </button>
            <div class="masjid-list" id="masjid-list-<?php echo $id; ?>">
                <ul>
                    <?php if (!empty($daerahMasjids[$id])): ?>
                        <?php foreach ($daerahMasjids[$id] as $masjid): ?>
                            <li><?php echo htmlspecialchars($masjid['masjid_id'] . " - " . $masjid['masjid_name']); ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No masjid available</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
