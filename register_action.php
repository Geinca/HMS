<?php
// register_action.php
session_start();
$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = 'admin'; // You can change or allow dynamic role assignment

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    header("Location: register.php?error=Email already registered");
    exit;
}

// Insert user
$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
$stmt->execute([$name, $email, $phone, $password, $role]);

$_SESSION['user'] = [
    'name' => $name,
    'email' => $email,
    'role' => $role
];

header("Location: dashboard.php");
exit;
