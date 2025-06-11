<?php
// edit_booking.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: bookings.php");
    exit;
}

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $booking_id = $_GET['id'];

    // Fetch booking
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        die("Booking not found.");
    }

    // Fetch guests
    $guests = $pdo->query("SELECT guest_id, name FROM guests")->fetchAll(PDO::FETCH_ASSOC);

    // Fetch rooms (include current booked room)
    $rooms = $pdo->query("SELECT room_id, room_number FROM rooms WHERE status = 'Available' OR room_id = {$booking['room_id']}")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Booking - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Edit Booking</h2>
    <form action="edit_booking_action.php" method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Guest</label>
                <select name="guest_id" required class="w-full border px-3 py-2 rounded">
                    <?php foreach ($guests as $guest): ?>
                        <option value="<?= $guest['guest_id'] ?>" <?= $guest['guest_id'] == $booking['guest_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($guest['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Room</label>
                <select name="room_id" required class="w-full border px-3 py-2 rounded">
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['room_id'] ?>" <?= $room['room_id'] == $booking['room_id'] ? 'selected' : '' ?>>
                            Room <?= htmlspecialchars($room['room_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700">Check-in Date</label>
                <input type="date" name="check_in_date" value="<?= $booking['check_in_date'] ?>" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block text-gray-700">Check-out Date</label>
                <input type="date" name="check_out_date" value="<?= $booking['check_out_date'] ?>" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block text-gray-700">Status</label>
                <select name="booking_status" class="w-full border px-3 py-2 rounded">
                    <option value="Confirmed" <?= $booking['booking_status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="Pending" <?= $booking['booking_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Cancelled" <?= $booking['booking_status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <a href="bookings.php" class="text-gray-600 hover:underline">← Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Booking</button>
        </div>
    </form>
</div>
</body>
</html>
