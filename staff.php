<?php
// staff.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Database connection
$host = 'localhost';
$port = '3307'; // Adjust if needed (e.g., 3307 for XAMPP)
$db = 'hotel_system';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $staff = $pdo->query("SELECT * FROM staff_profiles ORDER BY staff_id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profiles - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 p-4 md:p-6 overflow-x-hidden">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user-tie mr-2 text-blue-600"></i>Staff Profiles
        </h1>
        <a href="add_staff.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm flex items-center gap-2 w-full md:w-auto justify-center">
            <i class="fas fa-plus"></i> <span>Add Staff</span>
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 hidden sm:table-cell">Address</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Join Date</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Salary</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 hidden md:table-cell">Shift</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (count($staff) > 0): ?>
                        <?php foreach ($staff as $member): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap"><?= $member['staff_id'] ?></td>
                                <td class="px-4 py-4 hidden sm:table-cell"><?= htmlspecialchars($member['address']) ?></td>
                                <td class="px-4 py-4 whitespace-nowrap"><?= date('d M Y', strtotime($member['joining_date'])) ?></td>
                                <td class="px-4 py-4 whitespace-nowrap">₹<?= number_format($member['salary']) ?></td>
                                <td class="px-4 py-4 hidden md:table-cell"><?= htmlspecialchars($member['shift']) ?></td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="edit_staff.php?id=<?= $member['staff_id'] ?>" class="text-blue-600 hover:text-blue-800" title="Edit">
                                            <i class="fas fa-edit"></i> <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <a href="delete_staff.php?id=<?= $member['staff_id'] ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this staff member?');" title="Delete">
                                            <i class="fas fa-trash-alt"></i> <span class="hidden sm:inline">Delete</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500">No staff found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Optional: Add interactive scripts here
</script>

</body>
</html>
