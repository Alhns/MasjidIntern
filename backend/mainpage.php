<?php
session_start(); // Start session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../frontend/login.html"); // Redirect to login if not logged in
    exit();
}

// Load the HTML content
$html = file_get_contents('mainpage.html');

// Replace placeholder with the actual username
$html = str_replace("<!--USERNAME-->", $_SESSION['username'], $html);

// Display the modified HTML
echo $html;
?>
