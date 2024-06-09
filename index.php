<?php
session_start(); // Mulai sesi PHP untuk menyimpan data sesi pengguna

include 'koneksi.php'; // Sertakan file koneksi.php untuk menghubungkan ke database

if (isset($_POST['login'])) { // Periksa apakah tombol login telah ditekan
  $username = $_POST['user']; // Ambil nilai username dari formulir login
  $password = $_POST['pass']; // Ambil nilai password dari formulir login

  $_SESSION['login'] = true; // Setel status login menjadi true

  $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?"); // Persiapkan pernyataan SQL untuk memilih data pengguna berdasarkan username
  $stmt->bind_param("s", $username); // Ikat parameter ke pernyataan SQL
  $stmt->execute(); // Jalankan pernyataan SQL
  $result = $stmt->get_result(); // Dapatkan hasil dari pernyataan SQL

  if ($result->num_rows === 1) { // Jika ada satu baris hasil
    $data = $result->fetch_assoc(); // Ambil data pengguna dari hasil query

    if (password_verify($password, $data['password'])) { // Periksa apakah password yang dimasukkan cocok dengan hash yang disimpan di database
      $_SESSION['nama'] = $data['nama']; // Simpan nama pengguna dalam sesi
      $_SESSION['user_id'] = $data['id']; // Simpan ID pengguna dalam sesi

      header("location:dashboard.php"); // Redirect pengguna ke halaman dashboard
      exit(); // Hentikan skrip
    } else { // Jika password tidak cocok
      echo "<script>
      alert('username atau password salah'); // Tampilkan pesan kesalahan
      window.location = 'index.php'; // Redirect pengguna kembali ke halaman login
      </script>";
    }
  } else { // Jika tidak ada hasil atau username tidak ditemukan
    echo "<script>
    alert('username atau password salah'); // Tampilkan pesan kesalahan
    window.location = 'index.php'; // Redirect pengguna kembali ke halaman login
    </script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Log-in</title>
  <link rel="stylesheet" href="css/stylee.css" media="screen" type="text/css" />
  <style>
  /* Add this to your stylee.css file */

.login-card {
  background-color: #fff;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 400px; /* adjust the width as needed */
  margin: 0 auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.login-card h1 {
  text-align: center;
  margin-bottom: 20px;
}

.login-card form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.login-card input[type="text"], .login-card input[type="password"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
}

.login-card input[type="submit"] {
  width: 100%;
  padding: 10px;
  background-color: #337ab7;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.login-card input[type="submit"]:hover {
  background-color: #3e8e41;
}

.login-help {
  text-align: center;
  margin-top: 20px;
}

.login-help a {
  text-decoration: none;
  color: #337ab7;
}

.login-help a:hover {
  color: #23527c;
}
</style>
</head>

<body>

  <div class="login-card">
    <h1>Login</h1><br>
  <form action="" method="POST">
    <input type="text" name="user" placeholder="Username">
    <input type="password" name="pass" placeholder="Password">
    <input type="submit" name="login" class="login login-submit" value="login">
  </form>

  <div class="login-help">
    <a href="registrasi.php">Register</a> 
  </div>
</div>

</body>

</html>
