<?php
session_start();
require '../backend/connection.php'; // Adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ic'], $_POST['role'])) {
    $ic = $_POST['ic'];
    $role = $_POST['role'];

    try {
        $stmt = $conn->prepare("UPDATE form SET role = :role WHERE ic = :ic");
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':ic', $ic);
        $stmt->execute();

        echo "Success"; // AJAX will alert this message
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
