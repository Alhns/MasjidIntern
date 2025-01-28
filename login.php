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

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Query to check for the username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();

        // Fetch the user data
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($pass, $userData['password'])) {
            // Successful login
            session_start();
            $_SESSION['user'] = $userData['username'];
            echo "Login successful! Welcome, " . htmlspecialchars($userData['username']) . ".";
        } else {
            // Invalid credentials
            echo "Invalid username or password.";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
