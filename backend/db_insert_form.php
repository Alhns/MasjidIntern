<?php
session_start();
include('connection.php');
date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to GMT+8
$currentDate = date('Y-m-d H:i:s'); // Get current date and time in GMT+8

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_all'])) {
    if (isset($_POST['users']) && is_array($_POST['users'])) {
        echo "<pre style='color: blue;'>DEBUG: Received Data:</pre>";
        echo "<pre>";
        print_r($_POST['users']);  // Debug: Show all sent data
        echo "</pre>";

        try {
            $conn->beginTransaction(); // Start DB transaction

            foreach ($_POST['users'] as $ic => $user) {
                $name = $user['name'];
                $masjidId = $user['masjid_id'];
                $phone = $user['phone'];
                $address = $user['address'];
                $job = $user['job'];
                $totalVote = intval($user['total_vote']);

                $stmt = $conn->prepare("
                    INSERT INTO form (ic, name, date, phone_num, address, job, total_vote)
                    VALUES (:ic, :name, :date, :phone, :address, :job, :total_vote)
                ");

                $stmt->bindParam(':ic', $ic, PDO::PARAM_STR);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                $stmt->bindParam(':address', $address, PDO::PARAM_STR);
                $stmt->bindParam(':job', $job, PDO::PARAM_STR);
                $stmt->bindParam(':date', $currentDate, PDO::PARAM_STR);
                $stmt->bindParam(':total_vote', $totalVote, PDO::PARAM_INT);
                $stmt->execute();
            }

            $conn->commit(); // Commit transaction
            echo "<pre style='color: green;'>Data successfully inserted into the form table!</pre>";

            // Clear session after successful insert
            $_SESSION['search_results'] = [];

        } catch (PDOException $e) {
            $conn->rollBack(); // Rollback on error
            echo "<pre style='color: red;'>Error inserting data: " . $e->getMessage() . "</pre>";
        }
    } else {
        echo "<pre style='color: red;'>No users data received!</pre>";
    }
}
?>
