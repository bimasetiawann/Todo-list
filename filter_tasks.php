<?php
$servername = "localhost"; // Nama server database
$username = "root"; // Username untuk mengakses database
$password = ""; // Password untuk mengakses database
$db = "todo"; // Nama database

$conn = new mysqli($servername, $username, $password, $db); // Membuat koneksi baru ke database

if ($conn->connect_error) { // Memeriksa apakah terjadi kesalahan dalam koneksi ke database
    die("Connection failed: " . $conn->connect_error); // Jika terjadi kesalahan, hentikan skrip dan tampilkan pesan kesalahan
}

$data = json_decode(file_get_contents('php://input'), true); // Mengambil dan mendekode data JSON dari input HTTP POST
$filter = $data['filter']; // Mengambil nilai filter dari data JSON

$sql = "SELECT * FROM tasks"; // Query SQL untuk memilih semua tugas dari tabel tasks
if ($filter === 'completed') { // Jika filter adalah 'completed'
    $sql .= " WHERE status = 'completed'"; // Tambahkan klausa WHERE untuk hanya memilih tugas yang sudah selesai
} elseif ($filter === 'not_completed') { // Jika filter adalah 'not_completed'
    $sql .= " WHERE status = 'pending'"; // Tambahkan klausa WHERE untuk hanya memilih tugas yang belum selesai
}

$result = $conn->query($sql); // Menjalankan query SQL
$tasks = []; // Inisialisasi array untuk menyimpan tugas

if ($result->num_rows > 0) { // Jika ada baris hasil dari query
    while($row = $result->fetch_assoc()) { // Iterasi melalui setiap baris hasil
        $tasks[] = $row; // Tambahkan baris ke array tugas
    }
}

$response = []; // Inisialisasi array untuk menyimpan respons
$response['tasks'] = $tasks; // Menambahkan array tugas ke respons

$conn->close(); // Menutup koneksi database

echo json_encode($response); // Mengirimkan respons JSON kembali ke klien
?>