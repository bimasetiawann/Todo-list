<?php
session_start(); // Memulai sesi PHP
session_unset(); // Menghapus semua data yang disimpan dalam sesi
session_destroy(); // Menghapus sesi

header("location:index.php"); // Mengarahkan pengguna kembali ke halaman index.php setelah sesi dihapus
exit; // Menghentikan eksekusi skrip setelah mengarahkan pengguna
?>