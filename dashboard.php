<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user'];

$host = 'localhost';
$db = 'hotelsystem';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch counts
    $roomCount = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
    $guestCount = $pdo->query("SELECT COUNT(*) FROM guests")->fetchColumn();
    $bookingCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'Confirmed'")->fetchColumn();

    // Recent bookings
    $stmt = $pdo->query("SELECT b.*, g.name AS guest_name, r.room_number 
                         FROM bookings b 
                         JOIN guests g ON b.guest_id = g.guest_id 
                         JOIN rooms r ON b.room_id = r.room_id 
                         ORDER BY b.created_at DESC LIMIT 5");
    $recentBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Hotel System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>

</head>
<body class="flex bg-gray-100 min-h-screen">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-tachometer-alt text-indigo-600"></i> Welcome, <?= htmlspecialchars($user['name']) ?> 👋
    </h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Rooms</p>
                    <h2 class="text-3xl font-bold text-blue-600"><?= $roomCount ?></h2>
                </div>
                <i class="fas fa-bed text-4xl text-blue-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Guests</p>
                    <h2 class="text-3xl font-bold text-green-600"><?= $guestCount ?></h2>
                </div>
                <i class="fas fa-user-friends text-4xl text-green-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-6 shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Active Bookings</p>
                    <h2 class="text-3xl font-bold text-indigo-600"><?= $bookingCount ?></h2>
                </div>
                <i class="fas fa-calendar-check text-4xl text-indigo-400"></i>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl p-6 shadow mb-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-clock text-gray-500"></i> Recent Bookings
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">Booking ID</th>
                        <th class="px-4 py-2 border">Guest</th>
                        <th class="px-4 py-2 border">Room</th>
                        <th class="px-4 py-2 border">Check In</th>
                        <th class="px-4 py-2 border">Check Out</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBookings as $booking): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border"><?= $booking['booking_id'] ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($booking['guest_name']) ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($booking['room_number']) ?></td>
                            <td class="px-4 py-2 border"><?= $booking['check_in_date'] ?></td>
                            <td class="px-4 py-2 border"><?= $booking['check_out_date'] ?></td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?= $booking['booking_status'] === 'Confirmed' ? 'bg-green-100 text-green-600' : 
                                        ($booking['booking_status'] === 'Pending' ? 'bg-yellow-100 text-yellow-600' : 
                                        'bg-red-100 text-red-600') ?>">
                                    <?= $booking['booking_status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentBookings)): ?>
                        <tr><td colspan="6" class="text-center p-4">No recent bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl p-6 shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i> System Summary
            </h2>
            <ul class="text-gray-600 space-y-2 pl-4 list-disc">
                <li>System Date: <strong><?= date('Y-m-d') ?></strong></li>
                <li>Login Time: <strong><?= date('H:i:s') ?></strong></li>
                <li>Logged in as: <strong><?= htmlspecialchars($user['role'] ?? 'Admin') ?></strong></li>
            </ul>
        </div>
        <div class="bg-white rounded-xl p-6 shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">
                <i class="fas fa-link mr-2 text-purple-500"></i> Quick Links
            </h2>
            <ul class="space-y-2">
                <li><a href="rooms.php" class="text-blue-600 hover:underline"><i class="fas fa-door-open mr-2"></i> Manage Rooms</a></li>
                <li><a href="guests.php" class="text-blue-600 hover:underline"><i class="fas fa-users mr-2"></i> Manage Guests</a></li>
                <li><a href="bookings.php" class="text-blue-600 hover:underline"><i class="fas fa-calendar-alt mr-2"></i> Manage Bookings</a></li>
                <li><a href="staff.php" class="text-blue-600 hover:underline"><i class="fas fa-user-cog mr-2"></i> Manage Staff</a></li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
