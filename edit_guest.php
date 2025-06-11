<?php
// edit_guest.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: guests.php");
    exit;
}

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM guests WHERE guest_id = ?");
    $stmt->execute([$_GET['id']]);
    $guest = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$guest) {
        header("Location: guests.php");
        exit;
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Guest - Hotel Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Guest</h2>
        <form action="edit_guest_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="guest_id" value="<?= $guest['guest_id'] ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($guest['name']) ?>" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($guest['phone']) ?>" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($guest['email']) ?>" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Nationality</label>
                    <input type="text" name="nationality" value="<?= htmlspecialchars($guest['nationality']) ?>" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($guest['address']) ?>" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">ID Proof Type</label>
                    <select name="id_proof_type" class="w-full border px-3 py-2 rounded">
                        <?php
                        $options = ["Aadhar", "Passport", "Voter ID", "Driving License"];
                        foreach ($options as $option) {
                            $selected = ($guest['id_proof_type'] == $option) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700">ID Proof Number</label>
                    <input type="text" name="id_proof_number" value="<?= htmlspecialchars($guest['id_proof_number']) ?>" required class="w-full border px-3 py-2 rounded">
                </div>
                <div>
                    <label class="block text-gray-700">ID Proof File (Upload to Replace)</label>
                    <input type="file" name="id_proof_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full border px-3 py-2 rounded">
                </div>
            </div>
            <div class="mt-6 flex justify-between">
                <a href="guests.php" class="text-gray-600 hover:underline">← Back</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Guest</button>
            </div>
        </form>
    </div>
</body>
</html>
