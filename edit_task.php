<?php
// edit_task.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: housekeeping.php");
    exit;
}

$task_id = $_GET['id'];

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $rooms = $pdo->query("SELECT room_id, room_number FROM rooms")->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM housekeeping_tasks WHERE task_id = ?");
    $stmt->execute([$task_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header("Location: housekeeping.php");
        exit;
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Task - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex">
    <?php include 'sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Housekeeping Task</h2>
        <form action="edit_task_action.php" method="POST" class="bg-white p-6 rounded shadow max-w-xl space-y-4">
            <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
            <div>
                <label class="block mb-1 font-semibold">Room</label>
                <select name="room_id" required class="w-full border border-gray-300 p-2 rounded">
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['room_id'] ?>" <?= $room['room_id'] == $task['room_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($room['room_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Assigned To</label>
                <input type="text" name="assigned_to" value="<?= htmlspecialchars($task['assigned_to']) ?>" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Task Date</label>
                <input type="date" name="task_date" value="<?= $task['task_date'] ?>" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Status</label>
                <select name="status" required class="w-full border border-gray-300 p-2 rounded">
                    <option value="Pending" <?= $task['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= $task['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Notes</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 p-2 rounded"><?= htmlspecialchars($task['notes']) ?></textarea>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Task</button>
                <a href="housekeeping.php" class="ml-4 text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
