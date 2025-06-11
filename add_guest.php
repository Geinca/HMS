<?php
// add_guest.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Guest - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1 p-6 flex justify-center items-start">
        <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Add Guest</h2>
                <a href="guests.php" class="text-sm text-blue-600 hover:underline">← Back to Guest List</a>
            </div>

            <form action="add_guest_action.php" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
                        <input type="text" name="nationality" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="address" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Proof Type</label>
                        <select name="id_proof_type" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select</option>
                            <option value="Aadhar">Aadhar</option>
                            <option value="Passport">Passport</option>
                            <option value="Voter ID">Voter ID</option>
                            <option value="Driving License">Driving License</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Proof Number</label>
                        <input type="text" name="id_proof_number" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload ID Proof (PDF/Image)</label>
                        <input type="file" name="id_proof_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-300 px-4 py-2 rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                        Save Guest
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
