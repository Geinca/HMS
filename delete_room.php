<?php
// delete_room.php
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

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    // Delete the room
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->execute([$room_id]);
}

header("Location: rooms.php");
exit;
