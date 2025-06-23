<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Database config
$host = 'localhost';
$db = 'hotel_system';
$port = '3307';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all rooms
    $rooms = $pdo->query("SELECT room_id, room_number FROM rooms ORDER BY room_number")->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all services
    $services = $pdo->query("SELECT service_id, service_name FROM services ORDER BY service_name")->fetchAll(PDO::FETCH_ASSOC);

    // Handle assignment
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $room_id = $_POST['room_id'];
        $service_id = $_POST['service_id'];
        $notes = $_POST['notes'];

        $stmt = $pdo->prepare("INSERT INTO room_services (room_id, service_id, assigned_date, notes) VALUES (?, ?, NOW(), ?)");
        $stmt->execute([$room_id, $service_id, $notes]);

        $success = "Service assigned successfully!";
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Services - Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
    <h1 class="text-2xl font-bold mb-4">Assign Service to Room</h1>

    <?php if (isset($success)): ?>
        <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white p-6 rounded shadow max-w-xl">
        <div class="mb-4">
            <label class="block font-medium mb-1">Select Room</label>
            <select name="room_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Select Room --</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['room_id'] ?>"><?= htmlspecialchars($room['room_number']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Select Service</label>
            <select name="service_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">-- Select Service --</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service['service_id'] ?>"><?= htmlspecialchars($service['service_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Notes (optional)</label>
            <textarea name="notes" class="w-full border border-gray-300 rounded px-3 py-2" rows="3"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Assign Service
        </button>
    </form>
</div>

</body>
</html>
