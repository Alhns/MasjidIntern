<?php
session_start();
include('connection.php');

$searchIC = '';

// Initialize session array if not set
if (!isset($_SESSION['search_results'])) {
    $_SESSION['search_results'] = [];
}

// Handle search request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_ic'])) {
    $searchIC = trim($_POST['search_ic']);

    if (!empty($searchIC)) {
        try {
            $stmt = $conn->prepare("SELECT username, pswd, name, masjid_id, ic, phone, address, job FROM user WHERE ic = :ic");
            $stmt->bindParam(':ic', $searchIC, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check for duplicates before appending
            $existingICs = array_column($_SESSION['search_results'], 'ic');
            if (!in_array($searchIC, $existingICs)) {
                foreach ($results as &$user) {
                    if (!isset($user['total_vote'])) { 
                        $user['total_vote'] = 0; // Ensure total_vote is always set
                    }
                }
                $_SESSION['search_results'] = array_merge($_SESSION['search_results'], $results);
            } else {
                echo "<pre style='color: red;'>Warning: This IC is already in the table!</pre>";
            }
        } catch (PDOException $e) {
            echo "<pre style='color: red;'>Error fetching data: " . $e->getMessage() . "</pre>";
        }
    }
}

// Handle total_vote update for individual users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vote'])) {
    $updateIC = $_POST['ic'];
    $newVote = intval($_POST['total_vote']);

    foreach ($_SESSION['search_results'] as &$user) {
        if ($user['ic'] === $updateIC) {
            $user['total_vote'] = $newVote;
            break;
        }
    }
}

// Handle updating all votes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_all'])) {
    if (isset($_POST['votes']) && is_array($_POST['votes'])) {
        foreach ($_POST['votes'] as $ic => $vote) {
            foreach ($_SESSION['search_results'] as &$user) {
                if ($user['ic'] === $ic) {
                    $user['total_vote'] = intval($vote);
                    break;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search User Data by IC</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .search-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Search User Data by IC</h1>
    
    <div class="search-section">
        <form method="POST" action="db_insert_form.php">
            <label for="search_ic">Enter IC:</label>
            <input type="text" id="search_ic" name="search_ic" pattern="\d{12,}" maxlength="20" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <?php if (!empty($_SESSION['search_results'])): ?>
        <h2>Search Results:</h2>
        <form method="POST" action="db_insert_form.php">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Masjid ID</th>
                        <th>IC</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Job</th>
                        <th>Total Vote</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['search_results'] as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['masjid_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['ic']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['job']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="ic" value="<?php echo htmlspecialchars($row['ic']); ?>">
                                    <input type="number" name="total_vote" min="1" value="<?php echo isset($row['total_vote']) ? htmlspecialchars($row['total_vote']) : '0'; ?>" required>
                                    <button type="submit" name="update_vote">Set Vote</button>
                                </form>
                            </td>
                            <td>
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][ic]" value="<?php echo $row['ic']; ?>">
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][name]" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][masjid_id]" value="<?php echo $row['masjid_id']; ?>">
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][phone]" value="<?php echo $row['phone']; ?>">
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][address]" value="<?php echo $row['address']; ?>">
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][job]" value="<?php echo $row['job']; ?>">
                        <input type="hidden" name="users[<?php echo $row['ic']; ?>][total_vote]" min="1" value="<?php echo isset($row['total_vote']) ? $row['total_vote'] : '0'; ?>" required>
                    </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="update_all">Update All</button>
        </form>
    <?php else: ?>
        <p>No results found for the entered IC.</p>
    <?php endif; ?>
</body>
</html>
