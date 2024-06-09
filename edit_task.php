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

$data = json_decode(file_get_contents('php://input'), true);

$id_task = $data['id_task'];
$task = $data['task'];
$due_date = $data['due_date'];
$description = $data['description'];
$user_id = $_SESSION['user_id']; // Mengambil ID pengguna dari sesi

$sql = "UPDATE tasks SET task = ?, due_date = ?, description = ? WHERE id_task = ? AND id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $task, $due_date, $description, $id_task, $user_id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

echo json_encode($response);
?>
