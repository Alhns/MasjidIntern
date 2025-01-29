<?php
session_start(); // Start session at the beginning

// Include the database connection file
include('connection.php');

try {
    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Query to check for the username
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();

        // Fetch the user data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($pass, $userData['password'])) {
            // Successful login
            $_SESSION['user'] = $userData['username'];
            header("Location: test.html"); // Redirect to test.html
            exit();
        } else {
            // Invalid credentials
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: login.html"); // Redirect back to login page
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
