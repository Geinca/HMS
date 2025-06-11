<?php
// add_staff.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Staff - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">Add New Staff</h2>
            <form action="add_staff_action.php" method="POST" class="bg-white p-6 rounded shadow max-w-xl space-y-4">
                <div>
                    <label class="block mb-1 font-semibold">Address</label>
                    <input type="text" name="address" required class="w-full border border-gray-300 p-2 rounded" />
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Joining Date</label>
                    <input type="date" name="joining_date" required class="w-full border border-gray-300 p-2 rounded" />
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Salary</label>
                    <input type="number" name="salary" required class="w-full border border-gray-300 p-2 rounded" />
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Shift</label>
                    <select name="shift" required class="w-full border border-gray-300 p-2 rounded">
                        <option value="">-- Select Shift --</option>
                        <option value="morning">Morning</option>
                        <option value="evening">Evening</option>
                        <option value="night">Night</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Staff</button>
                    <a href="staff.php" class="ml-4 text-gray-600 hover:underline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
