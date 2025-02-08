<?php
session_start();
include('connection.php');

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in."); // Debug message
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id']; // Now it should be set
    $date = $_POST['date'];
    $time = $_POST['time'];
    $place = $_POST['place'];
    $status_code = 'pending';

    try {
        $stmt = $conn->prepare("INSERT INTO booking (user_id, date, time, place, status_code) VALUES (:user_id, :date, :time, :place, :status_code)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':place', $place);
        $stmt->bindParam(':status_code', $status_code);
        $stmt->execute();

        $_SESSION['message'] = "Booking request submitted successfully!";
        header("Location: ../frontend/mainpage.html");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
