<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$host = 'localhost';
$db = 'hotel_system';
$port = '3307';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $booking_id = $_GET['id'] ?? null;
    if (!$booking_id) {
        header("Location: bookings.php");
        exit;
    }

    // Fetch booking details
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        die("Booking not found.");
    }

    // Fetch guests and rooms
    $guests = $pdo->query("SELECT guest_id, name FROM guests ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    $rooms = $pdo->query("SELECT room_id, room_number FROM rooms")->fetchAll(PDO::FETCH_ASSOC);

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $guest_id = $_POST['guest_id'];
        $room_id = $_POST['room_id'];
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE bookings SET guest_id = ?, room_id = ?, check_in_date = ?, check_out_date = ?, booking_status = ? WHERE booking_id = ?");
        $stmt->execute([$guest_id, $room_id, $check_in, $check_out, $status, $booking_id]);

        header("Location: bookings.php");
        exit;
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Booking</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
  <h1 class="text-2xl font-bold mb-6 text-blue-700 flex items-center gap-2">
    <i class="fas fa-edit"></i> Edit Booking
  </h1>

  <form method="POST" class="bg-white p-6 rounded-xl shadow max-w-xl space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Guest</label>
      <select name="guest_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
        <?php foreach ($guests as $guest): ?>
          <option value="<?= $guest['guest_id'] ?>" <?= $guest['guest_id'] == $booking['guest_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($guest['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
      <select name="room_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
        <?php foreach ($rooms as $room): ?>
          <option value="<?= $room['room_id'] ?>" <?= $room['room_id'] == $booking['room_id'] ? 'selected' : '' ?>>
            Room <?= htmlspecialchars($room['room_number']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
        <input type="date" name="check_in" value="<?= $booking['check_in_date'] ?>" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
        <input type="date" name="check_out" value="<?= $booking['check_out_date'] ?>" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
      <select name="status" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
        <option value="Confirmed" <?= $booking['booking_status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
        <option value="Pending" <?= $booking['booking_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Cancelled" <?= $booking['booking_status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
      </select>
    </div>

    <div class="pt-4">
      <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
        Update Booking
      </button>
      <a href="bookings.php" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </div>
  </form>
</div>

</body>
</html>

