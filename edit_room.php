<!-- edit_room.php -->
<?php
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

    // Fetch room data
    $room_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM rooms WHERE room_id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        die("Room not found.");
    }

    // Fetch room types
    $typesStmt = $pdo->query("SELECT * FROM room_types");
    $roomTypes = $typesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Room</h2>
        <form action="edit_room_action.php" method="POST">
            <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">

            <div class="mb-4">
                <label class="block text-gray-700">Room Number</label>
                <input type="text" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Room Type</label>
                <select name="type_id" class="w-full border px-3 py-2 rounded">
                    <?php foreach ($roomTypes as $type): ?>
                        <option value="<?= $type['type_id'] ?>" <?= $type['type_id'] == $room['type_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['type_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Floor</label>
                <input type="number" name="floor" value="<?= $room['floor'] ?>" required class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Status</label>
                <select name="status" class="w-full border px-3 py-2 rounded">
                    <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="occupied" <?= $room['status'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                    <option value="maintenance" <?= $room['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">AC</label>
                <select name="is_ac" class="w-full border px-3 py-2 rounded">
                    <option value="1" <?= $room['is_ac'] ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= !$room['is_ac'] ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Smoking Allowed</label>
                <select name="is_smoking_allowed" class="w-full border px-3 py-2 rounded">
                    <option value="1" <?= $room['is_smoking_allowed'] ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= !$room['is_smoking_allowed'] ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="flex justify-between">
                <a href="rooms.php" class="text-gray-600 hover:underline">← Back</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Room</button>
            </div>
        </form>
    </div>
</body>
</html>
