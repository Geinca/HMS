<!-- add_room.php -->
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

    // Fetch room types
    $stmt = $pdo->query("SELECT * FROM room_types");
    $roomTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Room - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Add New Room</h2>
        <form action="add_room_action.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Room Number</label>
                <input type="text" name="room_number" required class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Room Type</label>
                <select name="type_id" required class="w-full border px-3 py-2 rounded">
                    <?php foreach ($roomTypes as $type): ?>
                        <option value="<?= $type['type_id'] ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Floor</label>
                <input type="number" name="floor" required class="w-full border px-3 py-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Status</label>
                <select name="status" class="w-full border px-3 py-2 rounded">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">AC</label>
                <select name="is_ac" class="w-full border px-3 py-2 rounded">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Smoking Allowed</label>
                <select name="is_smoking_allowed" class="w-full border px-3 py-2 rounded">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="flex justify-between">
                <a href="rooms.php" class="text-gray-600 hover:underline">← Back</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save Room</button>
            </div>
        </form>
    </div>
</body>
</html>
