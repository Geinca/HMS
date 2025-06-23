<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// DB connection
$host = 'localhost';
$db = 'hotel_system';
$port = '3307';
$charset = 'utf8mb4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Search
    $search = $_GET['search'] ?? '';
    $stmt = $pdo->prepare("
        SELECT * FROM staff_profiles 
        WHERE name LIKE :search OR address LIKE :search 
        ORDER BY staff_id DESC
    ");
    $stmt->execute(['search' => "%$search%"]);
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

// Export logic
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=staff_list.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, ['ID', 'Name', 'Address', 'Join Date', 'Salary', 'Shift']);
    foreach ($staff as $member) {
        fputcsv($output, [
            $member['staff_id'],
            $member['name'],
            $member['address'],
            $member['joining_date'],
            $member['salary'],
            $member['shift']
        ]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff List - Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

<?php include 'sidebar.php'; ?>

<div class="flex-1 p-6">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
    <h1 class="text-2xl font-bold flex items-center gap-2">
      <i class="fas fa-users text-blue-600"></i> Staff Members
    </h1>
    <form class="flex gap-2" method="GET">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search staff..." class="border px-3 py-1 rounded w-48 text-sm">
      <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Search</button>
      <a href="?export=csv&search=<?= urlencode($search) ?>" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">Export CSV</a>
    </form>
  </div>

  <div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
      <thead class="bg-blue-50 text-gray-700">
        <tr>
          <th class="px-4 py-3">ID</th>
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Address</th>
          <th class="px-4 py-3">Join Date</th>
          <th class="px-4 py-3">Salary</th>
          <th class="px-4 py-3">Shift</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if (count($staff) > 0): ?>
          <?php foreach ($staff as $member): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3"><?= $member['staff_id'] ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($member['name']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($member['address']) ?></td>
              <td class="px-4 py-3"><?= date('d M Y', strtotime($member['joining_date'])) ?></td>
              <td class="px-4 py-3">₹<?= number_format($member['salary']) ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($member['shift']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-gray-500 py-6">No staff found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
