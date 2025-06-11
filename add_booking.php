<?php
// add_booking.php
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

    // Fetch guests and available rooms
    $guests = $pdo->query("SELECT guest_id, name FROM guests")->fetchAll(PDO::FETCH_ASSOC);
    $rooms = $pdo->query("SELECT room_id, room_number FROM rooms WHERE status = 'Available'")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Booking - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Add New Booking</h2>
        <form action="add_booking_action.php" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Guest</label>
                    <select name="guest_id" required class="w-full border px-3 py-2 rounded">
                        <option value="">-- Select Guest --</option>
                        <?php foreach ($guests as $guest): ?>
                            <option value="<?= $guest['guest_id'] ?>"><?= htmlspecialchars($guest['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Room</label>
                    <select name="room_id" required class="w-full border px-3 py-2 rounded">
                        <option value="">-- Select Room --</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room['room_id'] ?>">Room <?= htmlspecialchars($room['room_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">Check-in Date</label>
                    <input type="date" name="check_in_date" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Check-out Date</label>
                    <input type="date" name="check_out_date" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Status</label>
                    <select name="booking_status" class="w-full border px-3 py-2 rounded">
                        <option value="Confirmed">Confirmed</option>
                        <option value="Pending">Pending</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-between">
                <a href="bookings.php" class="text-gray-600 hover:underline">← Back</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Booking</button>
            </div>
        </form>
    </div>
</body>
</html>
