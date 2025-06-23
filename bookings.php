<?php
// bookings.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Database config
$host = 'localhost';
$port = '3307'; // adjust if using 3307 on XAMPP
$db = 'hotel_system';
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
    <title>Bookings - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
    <div class="bg-white p-6 rounded shadow max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-calendar-check text-blue-600"></i> Bookings
            </h2>
            <a href="add_booking.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-plus"></i> <span>Add Booking</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border divide-y divide-gray-200">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Guest</th>
                        <th class="px-4 py-2">Room</th>
                        <th class="px-4 py-2">Check In</th>
                        <th class="px-4 py-2">Check Out</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach ($bookings as $booking): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?= $booking['booking_id'] ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($booking['guest_name']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($booking['room_number']) ?></td>
                            <td class="px-4 py-2"><?= date('d M Y', strtotime($booking['check_in_date'])) ?></td>
                            <td class="px-4 py-2"><?= date('d M Y', strtotime($booking['check_out_date'])) ?></td>
                            <td class="px-4 py-2">
                                <?php
                                    $status = htmlspecialchars($booking['booking_status']);
                                    $badgeColor = match (strtolower($status)) {
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                ?>
                                <span class="text-xs px-2 py-1 rounded-full font-semibold <?= $badgeColor ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <a href="edit_booking.php?id=<?= $booking['booking_id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                                <a href="delete_booking.php?id=<?= $booking['booking_id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
