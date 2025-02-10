<?php
session_start();
require '../backend/connection.php'; // Adjust the path

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $date = $_POST['date'];
    $status_code = $_POST['status_code'];
    $comment = $_POST['comment'];

    try {
        // Check if the place is already booked
        $check_stmt = $conn->prepare("SELECT * FROM booking WHERE date = :date AND booking_id != :booking_id");
        $check_stmt->bindParam(':date', $date);
        $check_stmt->bindParam(':booking_id', $booking_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $_SESSION['error'] = "This date is already booked!";
            header("Location: PejabatAgamaDaerah.php");
            exit();
        }

        // Update booking details
        $stmt = $conn->prepare("UPDATE booking SET date = :date, status_code = :status_code, comment = :comment WHERE booking_id = :booking_id");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status_code', $status_code);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':booking_id', $booking_id);
        $stmt->execute();

        $_SESSION['message'] = "Booking updated successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: PejabatAgamaDaerah.php");
exit();
?>
