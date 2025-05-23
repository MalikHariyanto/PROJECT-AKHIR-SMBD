<?php
// Konfigurasi koneksi database BookCollect
$host = "localhost";
$user = "root";
$pass = "";
$db   = "BookCollect";

$conn = mysqli_connect($host, $user, $pass, $db);
// Tidak perlu tutup tag PHP (?>