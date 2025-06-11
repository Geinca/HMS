<?php
// delete_guest.php
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

    // Optional: fetch file to delete
    $stmt = $pdo->prepare("SELECT id_proof_file FROM guests WHERE guest_id = ?");
    $stmt->execute([$_GET['id']]);
    $guest = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete the file if exists
    if ($guest && !empty($guest['id_proof_file'])) {
        $filePath = 'uploads/ids/' . $guest['id_proof_file'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Delete from DB
    $stmt = $pdo->prepare("DELETE FROM guests WHERE guest_id = ?");
    $stmt->execute([$_GET['id']]);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

header("Location: guests.php");
exit;
