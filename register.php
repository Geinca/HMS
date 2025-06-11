<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

// Initialize variables
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$inputValues = [
    'name' => $_GET['name'] ?? '',
    'email' => $_GET['email'] ?? '',
    'phone' => $_GET['phone'] ?? ''
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hotel System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <i class="fas fa-hotel text-4xl text-blue-600 mb-2"></i>
            <h2 class="text-2xl font-bold text-gray-800">Create Hotel Account</h2>
            <p class="text-gray-600">Register to access the management system</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p><?= htmlspecialchars($success) ?></p>
                <a href="index.php" class="text-green-800 hover:underline mt-2 inline-block">
                    <i class="fas fa-sign-in-alt mr-1"></i> Proceed to Login
                </a>
            </div>
        <?php endif; ?>

        <form action="register_action.php" method="POST" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="name" name="name" required 
                           value="<?= htmlspecialchars($inputValues['name']) ?>"
                           class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($inputValues['email']) ?>"
                           class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input type="tel" id="phone" name="phone" required
                           value="<?= htmlspecialchars($inputValues['phone']) ?>"
                           class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" required
                           class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters with at least one number</p>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="pl-10 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-user-plus mr-2"></i> Register Account
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="index.php" class="text-blue-600 hover:underline font-medium">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login here
                </a>
            </p>
        </div>
    </div>

    <!-- Password strength validation script -->
    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        function validatePassword() {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity("Passwords don't match");
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }
        
        passwordInput.addEventListener('change', validatePassword);
        confirmPasswordInput.addEventListener('keyup', validatePassword);
    </script>
</body>
</html>