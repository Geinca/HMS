<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// DB config
$host = 'localhost';
$db = 'hotel_system';
$port = '3307';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $totalRooms = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
    $occupiedRooms = $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Occupied'")->fetchColumn();
    $availableRooms = $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Available'")->fetchColumn();
    $todayBookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE DATE(check_in_date) = CURDATE()")->fetchColumn();

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
  <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
    <i class="fas fa-chart-line text-blue-600"></i> Manager Dashboard
  </h1>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded shadow border-l-4 border-blue-600">
      <h3 class="text-gray-500 text-sm">Total Rooms</h3>
      <p class="text-2xl font-bold"><?= $totalRooms ?></p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-red-600">
      <h3 class="text-gray-500 text-sm">Occupied Rooms</h3>
      <p class="text-2xl font-bold"><?= $occupiedRooms ?></p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-green-600">
      <h3 class="text-gray-500 text-sm">Available Rooms</h3>
      <p class="text-2xl font-bold"><?= $availableRooms ?></p>
    </div>
    <div class="bg-white p-4 rounded shadow border-l-4 border-yellow-500">
      <h3 class="text-gray-500 text-sm">Today's Bookings</h3>
      <p class="text-2xl font-bold"><?= $todayBookings ?></p>
    </div>
  </div>

  <!-- Charts -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-semibold mb-2">Revenue (Demo)</h2>
      <canvas id="revenueChart" height="200"></canvas>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-lg font-semibold mb-2">Occupancy Trend</h2>
      <canvas id="trendChart" height="200"></canvas>
    </div>
  </div>
</div>

<!-- Chart Scripts -->
<script>
  const revenueChart = new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
      labels: ['Deluxe', 'Suite', 'Standard', 'Economy'],
      datasets: [{
        label: 'Revenue (₹)',
        data: [40000, 30000, 20000, 10000],
        backgroundColor: ['#2563eb', '#059669', '#f59e0b', '#ef4444'],
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });

  const trendChart = new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: 'Occupied Rooms',
        data: [20, 25, 30, 28, 32, 29, 35],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        fill: true,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: true } }
    }
  });
</script>

</body>
</html>
