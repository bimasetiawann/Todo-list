<?php
session_start(); // Memulai sesi PHP

$servername = "localhost"; // Nama server database
$username = "root"; // Username untuk mengakses database
$password = ""; // Password untuk mengakses database
$db = "todo"; // Nama database

if (!isset($_SESSION['login'])) { // Memeriksa apakah pengguna sudah login
    header("location:index.php"); // Jika belum login, redirect ke halaman login
    exit; // Menghentikan eksekusi skrip
}

$conn = new mysqli($servername, $username, $password, $db); // Membuat koneksi baru ke database

if ($conn->connect_error) { // Memeriksa apakah terjadi kesalahan dalam koneksi ke database
    die("Connection failed: " . $conn->connect_error); // Jika terjadi kesalahan, hentikan skrip dan tampilkan pesan kesalahan
}

$data = json_decode(file_get_contents('php://input'), true); // Mengambil dan mendekode data JSON dari input HTTP POST

$id_task = $data['id_task']; // Mengambil ID tugas dari data JSON
$user_id = $_SESSION['user_id']; // Mengambil ID pengguna dari sesi

$sql = "DELETE FROM tasks WHERE id_task = ? AND id = ?"; // SQL query untuk menghapus tugas berdasarkan ID tugas dan ID pengguna
$stmt = $conn->prepare($sql); // Menyiapkan statement SQL
$stmt->bind_param("ii", $id_task, $user_id); // Mengikat parameter ke statement SQL: ID tugas dan ID pengguna

$response = []; // Inisialisasi array untuk menyimpan respons
if ($stmt->execute()) { // Menjalankan statement SQL
    $response['success'] = true; // Jika eksekusi berhasil, set 'success' menjadi true
} else {
    $response['success'] = false; // Jika eksekusi gagal, set 'success' menjadi false
    $response['error'] = $stmt->error; // Tambahkan pesan kesalahan ke respons
}

$stmt->close(); // Menutup statement SQL
$conn->close(); // Menutup koneksi database

header('Content-Type: application/json'); // Mengatur header respons sebagai JSON
echo json_encode($response); // Mengirimkan respons JSON kembali ke klien
?>
