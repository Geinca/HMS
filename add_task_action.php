<?php
// add_task_action.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $assigned_to = $_POST['assigned_to'];
    $task_date = $_POST['task_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $host = 'localhost';
    $db = 'hotelsystem';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO housekeeping_tasks (room_id, assigned_to, task_date, status, notes)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$room_id, $assigned_to, $task_date, $status, $notes]);

        header("Location: housekeeping.php");
        exit;

    } catch (PDOException $e) {
        die("DB Error: " . $e->getMessage());
    }
} else {
    header("Location: housekeeping.php");
    exit;
}
