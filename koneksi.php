<?php

$hostname = "localhost"; // Nama host MySQL server
$user = "root"; // Nama pengguna MySQL
$password = ""; // Kata sandi MySQL
$db_name = "todo"; // Nama basis data

$koneksi = mysqli_connect($hostname, $user, $password, $db_name) or die(mysqli_error($koneksi)); // Membuka koneksi ke server MySQL dan memilih basis data 'todo'

?>