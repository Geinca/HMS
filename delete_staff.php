<?php
// delete_staff.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: staff.php");
    exit;
}

$staff_id = $_GET['id'];

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("DELETE FROM staff_profiles WHERE staff_id = ?");
    $stmt->execute([$staff_id]);

    header("Location: staff.php");
    exit;

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
