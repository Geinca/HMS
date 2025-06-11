<?php
// housekeeping.php
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

    $tasks = $pdo->query("SELECT ht.*, r.room_number FROM housekeeping_tasks ht 
                          LEFT JOIN rooms r ON ht.room_id = r.room_id 
                          ORDER BY task_date DESC")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Housekeeping Tasks - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
<div class="flex">
    <?php include 'sidebar.php'; ?>
    <div class="flex-1 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Housekeeping Tasks</h2>
            <a href="add_task.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Task</a>
        </div>
        <div class="bg-white shadow rounded">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Room</th>
                        <th class="px-4 py-2 text-left">Assigned To</th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Notes</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?= htmlspecialchars($task['room_number']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($task['assigned_to']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($task['task_date']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($task['status']) ?></td>
                        <td class="px-4 py-2"><?= htmlspecialchars($task['notes']) ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_task.php?id=<?= $task['task_id'] ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <a href="delete_task.php?id=<?= $task['task_id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this task?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">No tasks found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
