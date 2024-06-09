<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db = "todo";

if (!isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_task = $_GET['id_task'];
$user_id = $_SESSION['user_id']; // Mengambil ID pengguna dari sesi

$sql = "SELECT * FROM tasks WHERE id_task = ? AND id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_task, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
if ($result->num_rows > 0) {
    $response['success'] = true;
    $response['task'] = $result->fetch_assoc();
} else {
    $response['success'] = false;
    $response['error'] = "Task not found";
}

echo json_encode($response);
?>