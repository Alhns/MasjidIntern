<?php
session_start();
include('connection.php');
date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to GMT+8
$currentDate = date('Y-m-d'); // Store only the date (YYYY-MM-DD)

$level_id = $_SESSION['ulevel'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_all'])) {
    if (isset($_POST['users']) && is_array($_POST['users'])) {
 /*       echo "<pre style='color: blue;'>DEBUG: Received Data:</pre>";
        echo "<pre>";
        print_r($_POST['users']);  // Debug: Show all sent data
        echo "</pre>";
*/
        try {
            $conn->beginTransaction(); // Start DB transaction

            foreach ($_POST['users'] as $key => $user) {
                // Determine if key is `form_id` or `ic`
                if (is_numeric($key)) {
                    $form_id = $key; // It's a `form_id`
                    $ic = $user['ic']; // Get IC from user data
                } else {
                    $form_id = null; // No form_id available
                    $ic = $key; // Key is the `ic`
                }

                $name = $user['name'];
                $masjidId = $user['masjid_id'];
                $phone = $user['phone'];
                $address = $user['address'];
                $job = $user['job'];
                $totalVote = intval($user['total_vote']);
                $role = $user['role'];
                $status = $level_id;

                // Check if an entry with the same IC and date exists
                $stmt = $conn->prepare("
                    SELECT form_id, total_vote FROM form 
                    WHERE ic = :ic AND DATE(date) = :currentDate
                ");
                $stmt->bindParam(':ic', $ic, PDO::PARAM_STR);
                $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
                $stmt->execute();
                $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existingRecord) {
                    $form_id = $existingRecord['form_id']; // Get the form_id if exists

                    // If record exists, update total_vote and status_code
                    $updatedVote = $totalVote;
                    $stmt = $conn->prepare("
                        UPDATE form 
                        SET total_vote = :total_vote, status_code = :status_code
                        WHERE form_id = :form_id
                    ");
                    $stmt->bindParam(':total_vote', $updatedVote, PDO::PARAM_INT);
                    $stmt->bindParam(':status_code', $status, PDO::PARAM_INT);
                    $stmt->bindParam(':form_id', $form_id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // If no record exists, insert new entry
                    $stmt = $conn->prepare("
                        INSERT INTO form (ic, name, date, phone_num, address, job, total_vote, status_code, role)
                        VALUES (:ic, :name, NOW(), :phone, :address, :job, :total_vote, :status_code, :role)
                    ");
                    $stmt->bindParam(':ic', $ic, PDO::PARAM_STR);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
                    $stmt->bindParam(':job', $job, PDO::PARAM_STR);
                    $stmt->bindParam(':total_vote', $totalVote, PDO::PARAM_INT);
                    $stmt->bindParam(':status_code', $status, PDO::PARAM_INT);
                    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }

            $conn->commit(); // Commit transaction
            echo "<pre style='color: green;'>Data successfully processed!</pre>";

            // Clear session after successful insert/update
            $_SESSION['search_results'] = [];

            // Redirect to another page after successful insertion
            header("Location: ../frontend/mainpage2.html"); 
            exit();
            
        } catch (PDOException $e) {
            $conn->rollBack(); // Rollback on error
            echo "<pre style='color: red;'>Error inserting/updating data: " . $e->getMessage() . "</pre>";
        }
    } else {
        echo "<pre style='color: red;'>No users data received!</pre>";
    }
}
?>