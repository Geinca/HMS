<?php
// edit_task_action.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
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

        $stmt = $pdo->prepare("
            UPDATE housekeeping_tasks 
            SET room_id = :room_id, 
                assigned_to = :assigned_to, 
                task_date = :task_date, 
                status = :status, 
                notes = :notes 
            WHERE task_id = :task_id
        ");

        $stmt->execute([
            ':room_id' => $room_id,
            ':assigned_to' => $assigned_to,
            ':task_date' => $task_date,
            ':status' => $status,
            ':notes' => $notes,
            ':task_id' => $task_id
        ]);

        $_SESSION['success'] = "Task updated successfully.";
        header("Location: tasks.php");
        exit;

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }

} else {
    header("Location: tasks.php");
    exit;
}
?>
