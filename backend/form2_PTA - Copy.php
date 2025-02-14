<?php
session_start();
include('connection.php');

$searchIC = '';
// Initialize session array if not set
if (!isset($_SESSION['search_results'])) {
    $_SESSION['search_results'] = [];
}

echo "<pre>Debug Search Results:";
print_r($_SESSION['search_results']);
echo "</pre>";


// Retrieve masjid_id from URL
$masjid_id = isset($_GET['masjid_id']) ? intval($_GET['masjid_id']) : null;

date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to GMT+8
// Get the first and last day of the current month
$firstDay = date('Y-m-01'); // Example: 2024-02-01
$lastDay = date('Y-m-t');   // Example: 2024-02-29


// Query to check if a form in the month
$sql = "SELECT f.*, u.name, u.ic, u.phone, u.address, u.job, u.masjid_id, m.masjid_name
        FROM form f 
        JOIN user u ON f.ic = u.ic
        JOIN masjid m ON u.masjid_id = m.masjid_id 
        WHERE DATE(f.reg_date) BETWEEN :firstdate AND :lastdate
        AND m.masjid_id = :masjid_id 
        ORDER BY f.total_vote desc";

// Generate debug query by replacing placeholders with actual values
$debug_sql = str_replace(
    [':firstdate', ':lastdate', ':masjid_id'],
    ["'$firstDay'", "'$lastDay'", "'$masjid_id'"],
    $sql
);

// Print Debug Query
echo "<pre>Debug SQL Query: " . $debug_sql . "</pre>";

$stmt = $conn->prepare($sql);
$stmt->execute([
    'firstdate' => $firstDay,
    'lastdate' => $lastDay,
    'masjid_id' => $masjid_id
]);
$forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Append today's forms to search_results, avoiding duplicates
$existingICs = array_column($_SESSION['search_results'], 'ic');

foreach ($forms as &$form) {
    if (!isset($form['total_vote'])) { 
        $form['total_vote'] = 0; // Ensure total_vote is always set
    }
    if (!in_array($form['ic'], $existingICs)) {
        $_SESSION['search_results'][] = $form;
    }
}

// Handle search request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_ic'])) {
    $searchIC = trim($_POST['search_ic']);

    if (!empty($searchIC)) {
        try {
            $stmt = $conn->prepare("SELECT username, pswd, name, masjid_id, ic, phone, address, job FROM user WHERE ic = :ic");
            $stmt->execute(['ic' => $searchIC]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if ($results) {
                foreach ($results as &$user) {
                    // Check if the masjid_id is different
                    if ($user['masjid_id'] != $masjid_id) {
                        echo "<script>alert('Anda Bukan Ahli Qaryah');</script>";
                        continue; // Skip adding this user
                    }
    
                    // Ensure total_vote is set
                    if (!isset($user['total_vote'])) { 
                        $user['total_vote'] = 0;
                    }
    
                    // Check for duplicates before adding
                    $existingICs = array_column($_SESSION['search_results'], 'ic');
                    if (!in_array($user['ic'], $existingICs)) {
                        $_SESSION['search_results'][] = $user;
                    } else {
                        echo "<script>alert('Warning: This IC is already in the table!');</script>";
                    }
                }
            } else {
                echo "<script>alert('No user found with this IC!');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error fetching data: " . addslashes($e->getMessage()) . "');</script>";
        }
    }    
}

// Handle total_vote and role update for individual users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vote'])) {

    echo "<pre>Debug POST Data:\n";
    print_r($_POST);
    echo "</pre>";
   // exit; // Stop execution to check output

    // Extract form_id dynamically
    $updateform = key($_POST['users']); // Get the first key in the users array
    $newVote = intval($_POST['total_vote']);
    $newRole = $_POST['role']; // Get selected role

    // Debug: Check if values are being received
    echo "<pre>Debug Update Form ID: " . $updateform . "</pre>";
    echo "<pre>Debug New Vote Count: " . $newVote . "</pre>";
    echo "<pre>Debug New Role: " . $newRole . "</pre>";

    // Update total_vote and role in the session array
    foreach ($_SESSION['search_results'] as &$user) {
        if ($user['form_id'] == $updateform || $user['ic'] == $_POST['users'][$updateform]['ic']) { 
            $user['total_vote'] = $newVote;
            $user['role'] = $newRole;
            break;
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 2 PTA</title>
    <link rel="stylesheet" href="../Styles/styles2.css">
</head>
<body>
    <h1>Search User Data by IC</h1>
    
    <div class="search-section">
        <form method="POST" action="">
            <label for="search_ic">Enter IC:</label>
            <input type="text" id="search_ic" name="search_ic" pattern="\d{12,}" maxlength="20" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <?php if (!empty($_SESSION['search_results'])): ?>
        <h2>Search Results:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>IC</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Job</th>
                        <th>Total Vote</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['search_results'] as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['ic']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['job']); ?></td>
    <form method="POST" action="">
        <?php if (!isset($row['total_vote'])): ?>
    <td>
    <?php $key = isset($row['form_id']) ? $row['form_id'] : $row['ic']; ?>
    <input type="hidden" name="users[<?php echo $key; ?>][ic]" value="<?php echo htmlspecialchars($row['ic']); ?>">
        <input type="number" name="total_vote" min="0" value="0" required>
    </td>
<?php else: ?>
    <td>
    <?php $key = isset($row['form_id']) ? $row['form_id'] : $row['ic']; ?>
        <input type="hidden" name="users[<?php echo $key; ?>][ic]" value="<?php echo htmlspecialchars($row['ic']); ?>">
        <input type="number" name="total_vote" min="0" value="<?php echo array_key_exists('total_vote', $row) ? htmlspecialchars($row['total_vote']) : '0'; ?>" required>
    </td>
<?php endif; ?>

    <td>
        <!-- Ensure role exists, default to "Please select a role" -->
        <select name="role">
            <option value="" disabled <?php echo (!isset($row['role']) || empty($row['role'])) ? 'selected' : ''; ?>>Please select a role</option>
            <option value="Pengerusi" <?php echo (isset($row['role']) && $row['role'] == 'Pengerusi') ? 'selected' : ''; ?>>Pengerusi</option>
            <option value="Naib Pengerusi" <?php echo (isset($row['role']) && $row['role'] == 'Naib Pengerusi') ? 'selected' : ''; ?>>Naib Pengerusi</option>
            <option value="Setiausaha" <?php echo (isset($row['role']) && $row['role'] == 'Setiausaha') ? 'selected' : ''; ?>>Setiausaha</option>
        </select>
    </td>

    <td>
    <button type="submit" name="update_vote" value="1">Update Vote and Role</button>
    </form>
</td>

                                </tr>
                           
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form method="POST" action="db_update_form_PTA.php">
    <?php foreach ($_SESSION['search_results'] as $row): ?>

        <?php $key = isset($row['form_id']) ? $row['form_id'] : $row['ic']; ?>
            <input type="hidden" name="users[<?php echo $key; ?>][ic]" value="<?php echo htmlspecialchars($row['ic']); ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][name]" value="<?php echo htmlspecialchars($row['name']); ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][masjid_id]" value="<?php echo htmlspecialchars($row['masjid_id']); ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][phone]" value="<?php echo htmlspecialchars($row['phone']); ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][address]" value="<?php echo htmlspecialchars($row['address']); ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][job]" value="<?php echo htmlspecialchars($row['job']); ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][role]" value="<?php echo isset($row['role']) ? htmlspecialchars($row['role']) : ''; ?>">
            <input type="hidden" name="users[<?php echo $key; ?>][total_vote]" value="<?php echo isset($row['total_vote']) ? htmlspecialchars($row['total_vote']) : '0'; ?>" required>
        <?php endforeach; ?>
        <button type="submit" name="update_all">Save</button>
        </form>
    <?php else: ?>
        <p>No results found for the entered IC.</p>
    <?php endif; ?>
</body>
</html>