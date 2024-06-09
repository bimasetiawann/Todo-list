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

// Mengambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Menyiapkan query SQL untuk mengambil semua tugas yang belum selesai untuk pengguna tersebut
$sql = "SELECT * FROM tasks WHERE id = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
// Mengikat parameter ID pengguna ke statement SQL
$stmt->bind_param("i", $user_id);
// Menjalankan statement SQL
$stmt->execute();
// Mengambil hasil query
$result = $stmt->get_result();

// Menyimpan tugas-tugas yang diambil dalam array
$tasks = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Memuat stylesheet Bootstrap dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Memuat stylesheet kustom -->
    <link rel="stylesheet" href="style.css">
    <title>To Do List</title>
    <!-- Preconnect ke Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- Memuat font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<style>
/* Mengatur box-sizing, margin, dan padding untuk semua elemen */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

/* Mengatur font, warna, dan latar belakang untuk body */
body {
  font-family: "Poppins", sans-serif;
  font-weight: 300;
  font-style: normal;
  line-height: 1.6;
  color: #333;
  background-color: #F3F7EC;
}

/* Mengatur margin dan warna untuk heading */
h1, h2, h3, h4, h5, h6 {
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

/* Menghapus padding dan margin untuk ul */
ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

/* Mengatur padding dan border untuk li */
li {
  padding: 10px;
  border-bottom: 1px solid #ccc;
}

/* Mengatur gaya link */
a {
  text-decoration: none;
  color: #337ab7;
  transition: color 0.2s ease;
}

a:hover {
  color: #23527c;
}

/* Gaya sidebar */
.sidebar {
  background: #337ab7;
  width: 250px;
  padding: 20px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
  border-right: 1px solid #34495e;
  text-align: center;
  font-size: 1.5rem;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
}

.sidebar .logo {
  width: 120px;
  height: auto;
  margin-bottom: 20px;
}

.sidebar h2 {
  margin-top: 0;
  color: #ecf0f1;
}

.nav-link {
  color: #ecf0f1;
  text-decoration: none;
  margin: 10px 0;
  transition: all 0.3s ease;
  font-size: 20px;
}

.nav-link:hover {
  color: #4ca1af;
  transform: scale(1.1);
}

/* Gaya konten */
.content {
  flex: 1;
  margin-left: 250px;
  padding: 20px;
  overflow-y: auto;
}

.content h1 {
  margin-top: 0;
  font-size: 24px;
  font-weight: bold;
  color: #333;
  font-size: 2rem;
  text-transform: uppercase;
}

.content h1 {
  margin-top: 0;
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

.list-group-item {
  padding: 10px;
  border-bottom: 1px solid #ccc;
  transition: background-color 0.2s ease;
}

.list-group-item:hover {
  background-color: #f5f5f5;
}

strong {
  font-size: 18px;
  font-weight: bold;
  color: #333;
}

span {
  font-size: 14px;
  color: #666;
}

p {
  font-size: 14px;
  color: #666;
  margin-bottom: 10px;
}
  </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <!-- Logo -->
    <img src="todoo.png" alt="Logo" class="logo">
    <!-- Navigation links -->
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="dashboard.php">To Do</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Belum Selesai</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="selesai.php">Selesai</a>
      </li>
    </ul>
</div>

<!-- Konten utama -->
<div class="content">
  <!-- Daftar tugas -->
  <ul id="todo-list" class="list-group">
    <!-- Loop melalui setiap tugas dan tampilkan -->
    <?php foreach ($tasks as $task): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong><?php echo htmlspecialchars($task['task']); ?></strong><br>
                <span><?php echo htmlspecialchars($task['due_date']); ?></span>
                <p><?php echo htmlspecialchars($task['description']); ?></p>
            </div>
        </li>
    <?php endforeach; ?>
  </ul>
</div>
</body>
