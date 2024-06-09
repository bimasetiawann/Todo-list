<?php
// Menggunakan koneksi.php yang berisi informasi koneksi ke database
include 'koneksi.php';

// Memeriksa apakah tombol submit telah ditekan
if (isset($_POST['submit'])){
    // Mengambil data dari form registrasi
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password1 = $_POST['password1']; 
    $password2 = $_POST['password2'];

    // Memeriksa apakah username sudah terdaftar
    $cek_user = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
    $cek_login = mysqli_num_rows($cek_user);

    // Jika username telah terdaftar, munculkan pesan alert
    if ($cek_login > 0) {
        echo "<script>
            alert('username telah terdaftar');
            window.location = 'registrasi.php';
        </script>";
    } else {
        // Memeriksa apakah konfirmasi password sesuai dengan password yang dimasukkan
        if ($password1 != $password2) {
            echo "<script>
                alert('konfirmasi password tidak sesuai');
                window.location = 'registrasi.php';
            </script>";
        } else {
			// Jika password cocok, hash password dan masukkan data ke database
			$password = password_hash($password1,PASSWORD_DEFAULT);
            mysqli_query($koneksi, "INSERT INTO user VALUES('', '$nama', '$username', '$password')");
            // Setelah data dimasukkan, munculkan pesan alert dan arahkan ke halaman login
            echo "<script>
                alert('data berhasil dikirim');
                window.location = 'index.php';
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>SignUp</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

<link href="css/register.css" rel="stylesheet" type="text/css" media="all" />

<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">

<style>
    .main-w3layouts {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f5f5f5; /* optional */
}

.main-agileinfo {
  background-color: #fff;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 400px; /* adjust the width as needed */
  margin: 0 auto;
}

.agileits-top {
  padding: 20px;
}

.agileits-top form {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.agileits-top input[type="text"],.agileits-top input[type="password"] {
  width: 100%;
  padding: 10px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
}

.agileits-top input[type="submit"] {
  width: 100%;
  padding: 10px;
  background-color: #337ab7;
  color: #fff;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.agileits-top input[type="submit"]:hover {
  background-color: #3e8e41;
}

.agileits-top p {
  text-align: center;
  margin-top: 20px;
}

.agileits-top a {
  text-decoration: none;
  color: #337ab7;
}

.agileits-top a:hover {
  color: #23527c;
}
</style>
</head>
<body>
<div class="main-w3layouts wrapper">
    <div class="main-agileinfo">
        <h1 style="text-align: center">Sign Up</h1>
        <div class="agileits-top">
            <form method="POST" action="">
                <input class="text" type="text" name="nama" placeholder="Nama Lengkap" required="yes">
                <input class="text email" type="text" name="username" placeholder="username" required="yes">
                <input class="text" type="password" name="password1" placeholder="Password" required="">
                <input class="text w3lpass" type="password" name="password2" placeholder="Confirm Password" required="">
                <input type="submit" value="SIGNUP" name="submit">
            </form>
            <p>Have an Account? <a href="index.php"> Login Now!</a></p>
        </div>
    </div>
</div>
    
</body>
</html>
