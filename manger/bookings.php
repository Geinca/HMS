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

    $stmt = $pdo->query("
        SELECT b.*, g.name AS guest_name, r.room_number 
        FROM bookings b 
        JOIN guests g ON b.guest_id = g.guest_id 
        JOIN rooms r ON b.room_id = r.room_id 
        ORDER BY b.booking_id DESC
    ");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bookings - Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
      <i class="fas fa-calendar-check"></i> Bookings
    </h1>
    <a href="add_booking.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow flex items-center gap-2">
      <i class="fas fa-plus"></i> Add Booking
    </a>
  </div>

  <!-- Bookings Table -->
  <div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-blue-50 text-gray-700">
        <tr>
          <th class="px-4 py-3 text-left font-medium">ID</th>
          <th class="px-4 py-3 text-left font-medium">Guest</th>
          <th class="px-4 py-3 text-left font-medium">Room</th>
          <th class="px-4 py-3 text-left font-medium">Check-In</th>
          <th class="px-4 py-3 text-left font-medium">Check-Out</th>
          <th class="px-4 py-3 text-left font-medium">Status</th>
          <th class="px-4 py-3 text-right font-medium">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 text-gray-800">
        <?php if ($bookings): ?>
          <?php foreach ($bookings as $b): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3"><?= $b['booking_id'] ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($b['guest_name']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($b['room_number']) ?></td>
              <td class="px-4 py-3"><?= date('d M Y', strtotime($b['check_in_date'])) ?></td>
              <td class="px-4 py-3"><?= date('d M Y', strtotime($b['check_out_date'])) ?></td>
              <td class="px-4 py-3">
                <?php
                  $statusClass = match (strtolower($b['booking_status'])) {
                    'confirmed' => 'bg-green-100 text-green-700',
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-600',
                  };
                ?>
                <span class="text-xs font-semibold px-2 py-1 rounded <?= $statusClass ?>">
                  <?= htmlspecialchars($b['booking_status']) ?>
                </span>
              </td>
              <td class="px-4 py-3 text-right space-x-2">
                <a href="edit_booking.php?id=<?= $b['booking_id'] ?>" class="text-blue-600 hover:text-blue-800">
                  <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
                </a>
                <a href="delete_booking.php?id=<?= $b['booking_id'] ?>" onclick="return confirm('Delete this booking?')" class="text-red-600 hover:text-red-800">
                  <i class="fas fa-trash"></i> <span class="hidden sm:inline">Delete</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-6 text-gray-500">No bookings found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
