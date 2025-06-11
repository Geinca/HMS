<?php
// add_booking_action.php
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

    $guest_id = $_POST['guest_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in_date'];
    $check_out = $_POST['check_out_date'];
    $status = $_POST['booking_status'];
    $booked_by = $_SESSION['user']['user_id']; // Changed from 'name' to 'user_id'

    // Insert booking
    $stmt = $pdo->prepare("INSERT INTO bookings (guest_id, room_id, check_in_date, check_out_date, booking_status, booked_by, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$guest_id, $room_id, $check_in, $check_out, $status, $booked_by]);

    // Update room status to 'Booked'
    $updateRoom = $pdo->prepare("UPDATE rooms SET status = 'Booked' WHERE room_id = ?");
    $updateRoom->execute([$room_id]);

    header("Location: bookings.php");
    exit; // Always include exit after header redirect

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}