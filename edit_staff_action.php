<?php
// edit_staff_action.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $address = $_POST['address'];
    $joining_date = $_POST['joining_date'];
    $salary = $_POST['salary'];
    $shift = $_POST['shift'];

    $host = 'localhost';
    $db = 'hotelsystem';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE staff_profiles SET address = ?, joining_date = ?, salary = ?, shift = ? WHERE staff_id = ?");
        $stmt->execute([$address, $joining_date, $salary, $shift, $staff_id]);

        header("Location: staff.php");
        exit;

    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
} else {
    header("Location: staff.php");
    exit;
}
