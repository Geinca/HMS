<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// DB Connection
$host = 'localhost';
$db = 'hotel_system';
$port = '3307';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch payments with guest and booking info
    $payments = $pdo->query("
        SELECT p.*, g.name AS guest_name, b.booking_id 
        FROM payments p
        JOIN bookings b ON p.booking_id = b.booking_id
        JOIN guests g ON b.guest_id = g.guest_id
        ORDER BY p.payment_id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payments - Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
  <h1 class="text-2xl font-bold mb-6 text-blue-700 flex items-center gap-2">
    <i class="fas fa-credit-card"></i> Payment Records
  </h1>

  <div class="bg-white shadow rounded-lg overflow-x-auto">
    <table class="min-w-full text-sm text-left divide-y divide-gray-200">
      <thead class="bg-blue-50 text-gray-700">
        <tr>
          <th class="px-4 py-3">#</th>
          <th class="px-4 py-3">Guest</th>
          <th class="px-4 py-3">Booking ID</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">Method</th>
          <th class="px-4 py-3">Date</th>
          <th class="px-4 py-3">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if ($payments): ?>
          <?php foreach ($payments as $payment): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3"><?= $payment['payment_id'] ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($payment['guest_name']) ?></td>
              <td class="px-4 py-3">#<?= $payment['booking_id'] ?></td>
              <td class="px-4 py-3 font-semibold text-green-700">₹<?= number_format($payment['amount']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($payment['payment_method']) ?></td>
              <td class="px-4 py-3"><?= date('d M Y', strtotime($payment['payment_date'])) ?></td>
              <td class="px-4 py-3">
                <?php
                  $status = $payment['status'];
                  $statusClass = match ($status) {
                      'Completed' => 'bg-green-100 text-green-800',
                      'Pending' => 'bg-yellow-100 text-yellow-800',
                      'Failed' => 'bg-red-100 text-red-800',
                      default => 'bg-gray-100 text-gray-700',
                  };
                ?>
                <span class="px-2 py-1 rounded text-xs font-medium <?= $statusClass ?>">
                  <?= $status ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center py-6 text-gray-500">No payment records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
