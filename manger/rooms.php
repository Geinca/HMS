<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// DB connection
$host = 'localhost';
$db = 'hotel_system';
$port = '3307';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $rooms = $pdo->query("
        SELECT r.*, t.type_name 
        FROM rooms r 
        JOIN room_types t ON r.type_id = t.type_id 
        ORDER BY r.room_id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rooms - Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
  <h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-blue-700">
    <i class="fas fa-door-open"></i> Rooms Overview
  </h1>

  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto text-sm text-gray-800">
        <thead class="bg-blue-100 sticky top-0">
          <tr>
            <th class="px-6 py-4 text-left font-semibold">Room No</th>
            <th class="px-6 py-4 text-left font-semibold">Type</th>
            <th class="px-6 py-4 text-left font-semibold">Floor</th>
            <th class="px-6 py-4 text-left font-semibold">Status</th>
            <th class="px-6 py-4 text-left font-semibold">AC</th>
            <th class="px-6 py-4 text-left font-semibold">Smoking</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if ($rooms): ?>
            <?php foreach ($rooms as $room): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium"><?= htmlspecialchars($room['room_number']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($room['type_name']) ?></td>
                <td class="px-6 py-4">Floor <?= htmlspecialchars($room['floor']) ?></td>
                <td class="px-6 py-4">
                  <?php
                    $statusColor = match ($room['status']) {
                      'Available' => 'bg-green-100 text-green-700',
                      'Occupied' => 'bg-red-100 text-red-700',
                      'Maintenance' => 'bg-yellow-100 text-yellow-700',
                      default => 'bg-gray-100 text-gray-700',
                    };
                  ?>
                  <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?= $statusColor ?>">
                    <?= $room['status'] ?>
                  </span>
                </td>
                <td class="px-6 py-4">
                  <?= $room['is_ac'] ? '<i class="fas fa-snowflake text-blue-500"></i> Yes' : '<i class="fas fa-ban text-gray-400"></i> No' ?>
                </td>
                <td class="px-6 py-4">
                  <?= $room['is_smoking_allowed'] ? '<i class="fas fa-smoking text-red-500"></i> Yes' : '<i class="fas fa-smoking-ban text-gray-400"></i> No' ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-6 py-8 text-center text-gray-500">No rooms found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
