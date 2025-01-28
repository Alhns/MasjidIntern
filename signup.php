<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_db_user';
$password = 'your_db_password';

try {
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
        $newUsername = $_POST['username'];
        $newPassword = $_POST['password'];

        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $newUsername);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Username already exists
            echo "<script>alert('Username already taken. Please choose another one.'); window.location.href='../frontend/signup.html';</script>";
        } else {
            // Insert new user into the database with a hashed password
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $insertStmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $insertStmt->bindParam(':username', $newUsername);
            $insertStmt->bindParam(':password', $hashedPassword);
            $insertStmt->execute();

            // Redirect to login page after successful sign-up
            echo "<script>alert('Account created successfully!'); window.location.href='../frontend/login.html';</script>";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
