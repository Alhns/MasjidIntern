<?php
session_start();
require '../backend/connection.php'; // Adjust path if needed

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id']; // Get booking ID from form
    $new_status = $_POST['status_code']; // Get new status from dropdown

    try {
        // Prepare the SQL query to update the status
        $stmt = $conn->prepare("UPDATE booking SET status_code = :status_code WHERE booking_id = :booking_id");
        $stmt->bindParam(':status_code', $new_status);
        $stmt->bindParam(':booking_id', $booking_id);

        // Execute the update query
        if ($stmt->execute()) {
            $_SESSION['message'] = "Adjustment has been made!";
        } else {
            $_SESSION['error'] = "Failed to update status.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Redirect back to the same page to show the pop-up message
header("Location: PejabatAgamaDaerah.php");
exit();
?>
