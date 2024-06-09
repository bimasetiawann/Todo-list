<?php
// Informasi server database
$servername = "localhost";
$username = "root";
$password = "";
$db = "todo";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $db);

// Memeriksa apakah koneksi berhasil, jika tidak, tampilkan pesan error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan data JSON dari request dan mengubahnya menjadi array PHP
$data = json_decode(file_get_contents('php://input'), true);

// Mendapatkan id_task dan status dari data yang diterima
$id_task = $data['id_task'];
$status = $data['status'];

// Membuat query SQL untuk mengupdate status tugas berdasarkan id_task
$sql = "UPDATE tasks SET status = ? WHERE id_task = ?";

// Mempersiapkan statement SQL untuk dieksekusi
$stmt = $conn->prepare($sql);

// Binding parameter ke statement SQL
$stmt->bind_param("si", $status, $id_task);

// Inisialisasi variabel respons untuk menyimpan hasil eksekusi query
$response = [];

// Mengeksekusi statement SQL
if ($stmt->execute()) {
    // Jika eksekusi berhasil, set success ke true
    $response['success'] = true;
} else {
    // Jika eksekusi gagal, set success ke false dan simpan pesan error
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

// Menutup statement SQL
$stmt->close();

// Menutup koneksi database
$conn->close();

// Mengembalikan respons dalam format JSON
echo json_encode($response);
?>
