<?php
// login.php
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

$email = $_POST['email'];
$password = $_POST['password'];

// Find user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'active'");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'user_id' => $user['user_id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
    header("Location: dashboard.php");
} else {
    header("Location: index.php?error=Invalid credentials");
}
exit;
