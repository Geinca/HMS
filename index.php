<!-- index.php -->
<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="login.php" method="POST" class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Hotel Management Login</h2>
        <?php if ($error): ?>
            <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Login</button>
        <p class="text-sm mt-4 text-center">No account? <a href="register.php" class="text-blue-600 hover:underline">Register</a></p>
    </form>
</body>
</html>
