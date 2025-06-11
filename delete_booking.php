<?php
// delete_booking.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit;
}

$booking_id = $_GET['id'];

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the room_id before deleting to free it up
    $stmt = $pdo->prepare("SELECT room_id FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($booking) {
        $room_id = $booking['room_id'];

        // Delete the booking
        $deleteStmt = $pdo->prepare("DELETE FROM bookings WHERE booking_id = ?");
        $deleteStmt->execute([$booking_id]);

        // Set room status to 'Available'
        $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE room_id = ?")->execute([$room_id]);
    }

    header("Location: bookings.php");

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
