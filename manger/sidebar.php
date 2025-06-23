<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-64 min-h-screen bg-white shadow-md hidden md:block">
  <div class="p-4 border-b border-gray-200">
    <h2 class="text-lg font-bold text-blue-700 flex items-center gap-2">
      <i class="fas fa-hotel"></i> Hotel Manager
    </h2>
  </div>
  <nav class="p-4">
    <ul class="space-y-2 text-sm font-medium">
      
      <li>
        <a href="../dashboard.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'dashboard.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-chart-line"></i> Dashboard
        </a>
      </li>

      <li>
        <a href="rooms.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'rooms.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-door-open"></i> Rooms
        </a>
      </li>

      <li>
        <a href="bookings.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'bookings.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-calendar-check"></i> Bookings
        </a>
      </li>

      <li>
        <a href="payments.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'payments.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-credit-card"></i> Payments
        </a>
      </li>

      <li>
        <a href="assign_services.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'assign_services.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-concierge-bell"></i> Services
        </a>
      </li>

      <li>
        <a href="staff_list.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'staff_list.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-user-tie"></i> Staff
        </a>
      </li>

      <li>
        <a href="../housekeeping/housekeeping.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'housekeeping.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-broom"></i> Housekeeping
        </a>
      </li>

      <li>
        <a href="../reports/occupancy_report.php" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-100 <?= $currentPage === 'occupancy_report.php' ? 'bg-blue-50 text-blue-700' : 'text-gray-700' ?>">
          <i class="fas fa-chart-pie"></i> Reports
        </a>
      </li>

      <li>
        <a href="../auth/logout.php" class="flex items-center gap-2 px-3 py-2 rounded text-red-600 hover:bg-red-50">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>

    </ul>
  </nav>
</aside>
