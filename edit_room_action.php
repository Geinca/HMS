<?php
// edit_room_action.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

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

// Get POST data
$room_id = $_POST['room_id'];
$room_number = $_POST['room_number'];
$type_id = $_POST['type_id'];
$floor = $_POST['floor'];
$status = $_POST['status'];
$is_ac = $_POST['is_ac'];
$is_smoking_allowed = $_POST['is_smoking_allowed'];

// Update room
$stmt = $pdo->prepare("UPDATE rooms SET room_number = ?, type_id = ?, floor = ?, status = ?, is_ac = ?, is_smoking_allowed = ? WHERE room_id = ?");
$stmt->execute([$room_number, $type_id, $floor, $status, $is_ac, $is_smoking_allowed, $room_id]);

header("Location: rooms.php");
exit;
