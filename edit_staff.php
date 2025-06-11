<?php
// edit_staff.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: staff.php");
    exit;
}

$staff_id = $_GET['id'];

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM staff_profiles WHERE staff_id = ?");
    $stmt->execute([$staff_id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        header("Location: staff.php");
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
    <title>Edit Staff - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex">
    <?php include 'sidebar.php'; ?>
    <div class="flex-1 p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Staff</h2>
        <form action="edit_staff_action.php" method="POST" class="bg-white p-6 rounded shadow max-w-xl space-y-4">
            <input type="hidden" name="staff_id" value="<?= $staff['staff_id'] ?>">
            <div>
                <label class="block mb-1 font-semibold">Address</label>
                <input type="text" name="address" value="<?= htmlspecialchars($staff['address']) ?>" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Joining Date</label>
                <input type="date" name="joining_date" value="<?= $staff['joining_date'] ?>" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Salary</label>
                <input type="number" name="salary" value="<?= $staff['salary'] ?>" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Shift</label>
                <select name="shift" required class="w-full border border-gray-300 p-2 rounded">
                    <option value="Morning" <?= $staff['shift'] == 'Morning' ? 'selected' : '' ?>>Morning</option>
                    <option value="Evening" <?= $staff['shift'] == 'Evening' ? 'selected' : '' ?>>Evening</option>
                    <option value="Night" <?= $staff['shift'] == 'Night' ? 'selected' : '' ?>>Night</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Staff</button>
                <a href="staff.php" class="ml-4 text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

