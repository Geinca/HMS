<?php
// edit_booking_action.php
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

    $booking_id = $_POST['booking_id'];
    $guest_id = $_POST['guest_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in_date'];
    $check_out = $_POST['check_out_date'];
    $status = $_POST['booking_status'];

    // Fetch the old booking data to check if the room is changed
    $stmtOld = $pdo->prepare("SELECT room_id FROM bookings WHERE booking_id = ?");
    $stmtOld->execute([$booking_id]);
    $oldBooking = $stmtOld->fetch(PDO::FETCH_ASSOC);
    $oldRoomId = $oldBooking['room_id'];

    // Update booking
    $stmt = $pdo->prepare("UPDATE bookings SET guest_id = ?, room_id = ?, check_in_date = ?, check_out_date = ?, booking_status = ? WHERE booking_id = ?");
    $stmt->execute([$guest_id, $room_id, $check_in, $check_out, $status, $booking_id]);

    // If room changed, update room statuses
    if ($oldRoomId != $room_id) {
        $pdo->prepare("UPDATE rooms SET status = 'Available' WHERE room_id = ?")->execute([$oldRoomId]);
        $pdo->prepare("UPDATE rooms SET status = 'Booked' WHERE room_id = ?")->execute([$room_id]);
    }

    header("Location: bookings.php");

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
