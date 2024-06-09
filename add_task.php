<?php
// Memulai sesi pengguna
session_start();

// Informasi koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$db = "todo";

// Memeriksa apakah pengguna sudah login; jika tidak, arahkan ke halaman login
if (!isset($_SESSION['login'])) {
    header("location:index.php");
    exit;
}

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $db);

// Memeriksa apakah koneksi berhasil; jika gagal, tampilkan pesan kesalahan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data yang dikirimkan dalam bentuk JSON dari body request
$data = json_decode(file_get_contents('php://input'), true);

// Mengambil nilai 'task', 'due_date', dan 'description' dari data yang diterima
$task = $data['task'];
$due_date = $data['due_date'];
$description = $data['description'];
// Mengambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Menyiapkan query SQL untuk memasukkan data tugas baru ke dalam tabel 'tasks'
$sql = "INSERT INTO tasks (task, due_date, description, id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
// Mengikat parameter ke dalam statement SQL
$stmt->bind_param("sssi", $task, $due_date, $description, $user_id);

// Menyiapkan array untuk menyimpan respons
$response = [];
// Menjalankan statement SQL dan memeriksa apakah berhasil
if ($stmt->execute()) {
    // Jika berhasil, set 'success' menjadi true dan tambahkan ID tugas yang baru dimasukkan
    $response['success'] = true;
    $response['id_task'] = $stmt->insert_id;
} else {
    // Jika gagal, set 'success' menjadi false dan tambahkan pesan kesalahan
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

// Menutup statement dan koneksi database
$stmt->close();
$conn->close();

// Mengatur header respons menjadi JSON
header('Content-Type: application/json');
// Mengirimkan respons dalam format JSON
echo json_encode($response);
?>
