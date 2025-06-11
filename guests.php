<?php
// guests.php
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

    $stmt = $pdo->query("SELECT * FROM guests ORDER BY created_at DESC");
    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest List - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-users mr-2 text-blue-600"></i>Guest List</h1>
                <a href="add_guest.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Add Guest
                </a>
            </div>

            <div class="overflow-x-auto rounded border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
                    <thead class="bg-blue-50 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Nationality</th>
                            <th class="px-4 py-3">ID Proof</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($guests)): ?>
                            <?php foreach ($guests as $guest): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3"><?= htmlspecialchars($guest['name']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($guest['phone']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($guest['email']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($guest['nationality']) ?></td>
                                    <td class="px-4 py-3">
                                        <?= htmlspecialchars($guest['id_proof_type']) ?>
                                        <br class="md:hidden"/>
                                        <span class="text-xs text-gray-500">(<?= htmlspecialchars($guest['id_proof_number']) ?>)</span>
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <a href="edit_guest.php?id=<?= $guest['guest_id'] ?>" class="text-blue-600 hover:underline">
                                            <i class="fas fa-edit"></i> 
                                        </a>
                                        <a href="delete_guest.php?id=<?= $guest['guest_id'] ?>" onclick="return confirm('Delete this guest?')" class="text-red-600 hover:underline">
                                            <i class="fas fa-trash-alt"></i> 
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center px-4 py-6 text-gray-500">No guests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
