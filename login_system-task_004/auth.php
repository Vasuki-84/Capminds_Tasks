<?php
session_start();
require "includes/validation.php";

// Get POST data
$username = $_POST['username'] ?? "";
$email = $_POST['email'] ?? "";
$password = $_POST['password'] ?? "";
$remember = isset($_POST['remember']);

// Validation
$errors = [];

if ($msg = validateUsername($username)) $errors[] = $msg;
if ($msg = validateEmail($email)) $errors[] = $msg;
if ($msg = validatePassword($password)) $errors[] = $msg;

if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: login.php");
    exit;
}

// Dummy Authentication
if ($username === "admin" && $email === "admin@example.com" && $password === "Admin@123") {

    // Theme logic
    if ($username === "user1") {
        $theme = "dark";
    } elseif ($username === "user2") {
        $theme = "warm";
    } else {
        $theme = "light";
    }

    // Session
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['theme'] = $theme;

    // Cookies (60 sec for testing)
    if ($remember) {
        setcookie("remember_username", $username, time() + 60);
        setcookie("user_theme", $theme, time() + 60);
    } else {
        setcookie("remember_username", "", time() - 3600);
    }

    header("Location: dashboard.php");
    exit;

} else {
    $_SESSION['error'] = "Invalid credentials";
    header("Location: login.php");
    exit;
}