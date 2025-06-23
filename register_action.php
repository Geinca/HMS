<?php
// register_action.php
session_start();
require 'db.php';

// Sanitize and collect input
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';
$role     = trim($_POST['role'] ?? ''); // <-- NEW

// Validation
if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm) || empty($role)) {
    header("Location: register.php?error=All fields are required&name=$name&email=$email&phone=$phone&role=$role");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=Invalid email address&name=$name&phone=$phone&role=$role");
    exit;
}

if (strlen($password) < 8 || !preg_match('/\d/', $password)) {
    header("Location: register.php?error=Password must be at least 8 characters and include a number&name=$name&email=$email&phone=$phone&role=$role");
    exit;
}

if ($password !== $confirm) {
    header("Location: register.php?error=Passwords do not match&name=$name&email=$email&phone=$phone&role=$role");
    exit;
}

// Only allow predefined roles
$allowed_roles = ['admin', 'manager'];
if (!in_array($role, $allowed_roles)) {
    header("Location: register.php?error=Invalid role selected&name=$name&email=$email&phone=$phone");
    exit;
}

// Check if email already exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    header("Location: register.php?error=Email already registered&name=$name&phone=$phone&role=$role");
    exit;
}

// Insert new user
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
try {
    $stmt->execute([$name, $email, $phone, $hashedPassword, $role]);
    header("Location: register.php?success=Account created successfully!");
} catch (PDOException $e) {
    error_log("Registration failed: " . $e->getMessage());
    header("Location: register.php?error=An error occurred. Please try again later.");
}
?>
