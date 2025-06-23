<?php
// session_start(); // Uncomment if this is a standalone include

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['user']['role'] ?? 'guest';
?>
<!-- Mobile Menu Button -->
<button id="sidebarToggle" class="md:hidden fixed top-4 left-4 z-50 p-3 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
    <i class="fas fa-bars text-lg"></i>
</button>

<!-- Sidebar -->
<div id="sidebar" class="fixed md:relative transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 w-72 bg-white shadow-xl p-6 space-y-8 min-h-screen border-r border-blue-100">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent flex items-center gap-2">
            <i class="fas fa-hotel text-indigo-600"></i> OceanView Admin
        </h2>
        <button id="closeSidebar" class="md:hidden p-1 rounded-full hover:bg-blue-100 text-gray-500">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Menu -->
    <ul class="space-y-1">
        <li>
            <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-blue-50 group">
                <div class="icon-wrap bg-blue-100 group-hover:bg-blue-600 group-hover:text-white">
                    <i class="fas fa-tachometer-alt text-sm"></i>
                </div>
                <span class="label">Dashboard</span>
            </a>
        </li>

        <!-- Admin Only -->
        <?php if ($role === 'admin'): ?>
        <li>
            <a href="rooms.php" class="menu-link"><div class="icon-wrap"><i class="fas fa-door-open text-sm"></i></div><span class="label">Rooms</span></a>
        </li>
        <li>
            <a href="staff.php" class="menu-link"><div class="icon-wrap"><i class="fas fa-user-tie text-sm"></i></div><span class="label">Staff</span></a>
        </li>
        <?php endif; ?>

        <!-- Manager Only -->
        <?php if ($role === 'manager'): ?>
        <li>
            <a href="guests.php" class="menu-link"><div class="icon-wrap"><i class="fas fa-users text-sm"></i></div><span class="label">Guests</span></a>
        </li>
        <?php endif; ?>

        <!-- Shared -->
        <?php if (in_array($role, ['admin', 'manager'])): ?>
        <li>
            <a href="bookings.php" class="menu-link"><div class="icon-wrap"><i class="fas fa-calendar-check text-sm"></i></div><span class="label">Bookings</span></a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Logout -->
    <div class="absolute bottom-6 left-0 right-0 px-6">
        <div class="border-t border-gray-200 pt-4">
            <a href="logout.php" class="flex items-center p-3 rounded-lg hover:bg-red-50 group transition-all duration-200">
                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white mr-3">
                    <i class="fas fa-sign-out-alt text-sm"></i>
                </div>
                <span class="font-medium text-gray-700 group-hover:text-red-600">Logout</span>
                <i class="fas fa-chevron-right ml-auto text-xs text-gray-400 group-hover:text-red-600"></i>
            </a>
        </div>
    </div>
</div>

<!-- Overlay for Mobile -->
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-30 z-30 md:hidden backdrop-blur-sm transition-opacity duration-300"></div>

<!-- Sidebar Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    const toggleSidebar = () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    };

    sidebarToggle?.addEventListener('click', toggleSidebar);
    closeSidebar?.addEventListener('click', toggleSidebar);
    sidebarOverlay?.addEventListener('click', toggleSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    });
});
</script>

<!-- Optional: Tailwind helpers -->
<style>
.menu-link {
    @apply flex items-center p-3 rounded-lg hover:bg-blue-50 group transition-all duration-200;
}
.icon-wrap {
    @apply w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white mr-3;
}
.label {
    @apply font-medium text-gray-700 group-hover:text-blue-600;
}
</style>
