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
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch rooms
$stmt = $pdo->query("SELECT rooms.*, room_types.type_name FROM rooms JOIN room_types ON rooms.type_id = room_types.type_id ORDER BY room_id DESC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate room statistics
$statusCounts = ['Available' => 0, 'Occupied' => 0, 'Maintenance' => 0];
$totalRooms = count($rooms);
$occupiedPercentage = 0;

foreach ($rooms as $room) {
    if (isset($statusCounts[$room['status']])) {
        $statusCounts[$room['status']]++;
    }
}

if ($totalRooms > 0) {
    $occupiedPercentage = round(($statusCounts['Occupied'] / $totalRooms) * 100);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .status-available {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-occupied {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .status-maintenance {
            background-color: #fef3c7;
            color: #92400e;
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .occupancy-high {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }
    </style>
</head>
<body class="flex bg-gray-50 min-h-screen">

<?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="flex-1 p-4 md:p-6">
        <!-- Page Header with Occupancy Info -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Room Management</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-gray-600"><?= $statusCounts['Occupied'] ?> of <?= $totalRooms ?> rooms occupied</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full 
                        <?= $occupiedPercentage > 80 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' ?>">
                        <?= $occupiedPercentage ?>% occupancy
                    </span>
                </div>
            </div>
            <a href="add_room.php" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors shadow-md">
                <i class="fas fa-plus"></i>
                <span>Add New Room</span>
            </a>
        </div>

        <!-- High Occupancy Alert -->
        <?php if ($occupiedPercentage > 80): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded occupancy-high">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <span class="font-bold">High Occupancy Warning!</span> 
                        <?= $occupiedPercentage ?>% of rooms are occupied. 
                        Only <?= $statusCounts['Available'] ?> room<?= $statusCounts['Available'] != 1 ? 's' : '' ?> available.
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Rooms Card -->
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500 card-hover transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total Rooms</p>
                        <h3 class="text-2xl font-bold"><?= $totalRooms ?></h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-door-open text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Occupied Rooms Card - Highlighted -->
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-red-500 card-hover transition-all 
                <?= $occupiedPercentage > 80 ? 'ring-2 ring-red-300' : '' ?>">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Occupied Rooms</p>
                        <h3 class="text-2xl font-bold"><?= $statusCounts['Occupied'] ?></h3>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-red-500 h-1.5 rounded-full" style="width: <?= $occupiedPercentage ?>%"></div>
                        </div>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <i class="fas fa-bed text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Available Rooms Card -->
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500 card-hover transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Available</p>
                        <h3 class="text-2xl font-bold"><?= $statusCounts['Available'] ?></h3>
                        <p class="text-xs text-gray-500 mt-1"><?= round(($statusCounts['Available'] / $totalRooms) * 100) ?>% availability</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Maintenance Card -->
            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-yellow-500 card-hover transition-all">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Maintenance</p>
                        <h3 class="text-2xl font-bold"><?= $statusCounts['Maintenance'] ?></h3>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-tools text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rooms Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room No.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Floor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Smoking</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($rooms as $room): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-800 font-medium"><?= htmlspecialchars($room['room_number']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($room['type_name']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">Floor <?= htmlspecialchars($room['floor']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $statusClass = strtolower($room['status']) === 'available' ? 'status-available' : 
                                                  (strtolower($room['status']) === 'occupied' ? 'status-occupied' : 'status-maintenance');
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                        <?= htmlspecialchars($room['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $room['is_ac'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= $room['is_ac'] ? 'Yes' : 'No' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $room['is_smoking_allowed'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                        <?= $room['is_smoking_allowed'] ? 'Yes' : 'No' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="edit_room.php?id=<?= $room['room_id'] ?>" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_room.php?id=<?= $room['room_id'] ?>" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm('Are you sure you want to delete this room?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <?php if ($room['status'] === 'Occupied'): ?>
                                        <span class="text-purple-600" title="Currently Occupied">
                                            <i class="fas fa-user-clock"></i>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($rooms)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <i class="fas fa-door-closed text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-gray-500">No rooms available</p>
                                        <a href="add_room.php" class="mt-2 text-blue-600 hover:underline">Add your first room</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle functionality would go here
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Rooms management system loaded');
            
            // You can add any additional interactive functionality here
            // For example, tooltips for the status indicators
            const statusIndicators = document.querySelectorAll('[title]');
            statusIndicators.forEach(indicator => {
                indicator.addEventListener('mouseover', function() {
                    // You could implement a custom tooltip here if needed
                });
            });
        });
    </script>
</body>
</html>