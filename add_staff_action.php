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


$address = $_POST['address'];
$joiningDate = $_POST['joining_date'];
$salary = $_POST['salary'];
$shift = $_POST['shift'];
$staffID = $_POST['staff_id'];



// Insert into database
$stmt = $pdo->prepare("INSERT INTO `staff_profiles`(`address`, `joining_date`, `salary`, `shift`, `staff_id`) VALUES (?,?,?,?,?)");
$stmt->execute([$address, $joiningDate, $salary, $shift,$staffID]);

header("Location: staff.php");
exit;
