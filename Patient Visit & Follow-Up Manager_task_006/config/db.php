<?php
// config/db.php - Database configuration with PDO for security
session_start();

$host = 'localhost';  // Database server location.
$port = '3308';       
$dbname = 'healthcare';
$username = 'root';
$password = ''; 

try {

    // PDO = PHP Data Objects -  A secure database connection method.
    // Add port number to the DSN (Data Source Name) - It tells PDO: Which database type,host, port, database, character encoding
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // If database error happens: Throw exception immediately.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // When fetching data:Return associative arrays by default.
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  // Use REAL prepared statements from MySQL.
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Security helper functions
// trim
// → remove tags
// → convert special chars
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));   // Converts special characters, Removes HTML tags, Removes extra spaces.
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}