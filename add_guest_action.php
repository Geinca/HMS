<?php
// add_guest_action.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$host = 'localhost';
$db = 'hotelsystem';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$nationality = $_POST['nationality'];
$address = $_POST['address'];
$id_proof_type = $_POST['id_proof_type'];
$id_proof_number = $_POST['id_proof_number'];

// Handle file upload
$id_proof_file = '';
if (isset($_FILES['id_proof_file']) && $_FILES['id_proof_file']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/ids/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = uniqid() . '_' . basename($_FILES['id_proof_file']['name']);
    $targetPath = $uploadDir . $filename;
    move_uploaded_file($_FILES['id_proof_file']['tmp_name'], $targetPath);
    $id_proof_file = $filename;
}

// Insert into database
$stmt = $pdo->prepare("INSERT INTO guests (name, phone, email, nationality, address, id_proof_type, id_proof_number, id_proof_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$name, $phone, $email, $nationality, $address, $id_proof_type, $id_proof_number, $id_proof_file]);

header("Location: guests.php");
exit;
