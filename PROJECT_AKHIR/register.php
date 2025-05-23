<?php
session_start();
include __DIR__ . '/Config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $kata_sandi = $_POST['kata_sandi'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi sederhana
    if ($kata_sandi !== $konfirmasi) {
        $error = 'Konfirmasi kata sandi tidak cocok!';
    } else {
        // Cek email sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM Pengguna WHERE email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Email sudah terdaftar!';
        } else {
            // Simpan user baru
            $sql = "INSERT INTO Pengguna (nama, email, kata_sandi) VALUES ('$nama', '$email', '$kata_sandi')";
            if (mysqli_query($conn, $sql)) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Registrasi gagal. Coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BookCollect • Daftar</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #EAF4FC, #1F509A, #0A3981);
      color: #333;
    }
    .form-container {
      background-color: white;
      border-radius: 1rem;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h1 {
      color: #2c5282;
      font-weight: 700;
      margin-top: 0;
      margin-bottom: 20px;
    }
    .input-label {
      color: #2a4365;
    }
    .input-field {
      border: 1px solid #e2e8f0;
      border-radius: 0.375rem;
      padding: 0.5rem;
      width: 100%;
      transition: border-color 0.2s;
    }
    .input-field:focus {
      border-color: #2a4365;
      outline: none;
    }
    .btn-submit {
      background-color: #2a4365;
      color: white;
      padding: 0.5rem;
      border-radius: 0.375rem;
      width: 100%;
      transition: background-color 0.2s;
    }
    .btn-submit:hover {
      background-color: #4c6eb1;
    }
    .link {
      color: #2a4365;
      text-align: center;
      display: block;
      margin-top: 1rem;
      border: 1px solid #2a4365;
      padding: 0.5rem;
      border-radius: 0.375rem;
      transition: background-color 0.2s;
    }
    .link:hover {
      background-color: #bee3f8;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md form-container p-8">
    <h1 class="text-2xl font-semibold text-center mb-6">Daftar di BookCollect</h1>
    <?php if ($error): ?>
      <div class="mb-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="mb-4 text-green-600 text-center"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-4">
      <div>
        <label for="nama" class="block text-sm font-medium input-label">Nama Lengkap</label>
        <input id="nama" name="nama" type="text" required class="input-field"/>
      </div>
      <div>
        <label for="email" class="block text-sm font-medium input-label">Email</label>
        <input id="email" name="email" type="email" required class="input-field"/>
      </div>
      <div>
        <label for="kata_sandi" class="block text-sm font-medium input-label">Kata Sandi</label>
        <input id="kata_sandi" name="kata_sandi" type="password" required minlength="3" class="input-field"/>
      </div>
      <div>
        <label for="konfirmasi" class="block text-sm font-medium input-label">Konfirmasi Kata Sandi</label>
        <input id="konfirmasi" name="konfirmasi" type="password" required minlength="3" class="input-field"/>
      </div>
      <button type="submit" class="btn-submit">
        Daftar
      </button>
    </form>
    <div class="my-6 flex items-center gap-3 text-sm text-mediumslate">
      <span class="flex-1 border-t"></span> atau <span class="flex-1 border-t"></span>
    </div>
    <a href="login.php" class="link">
      Sudah Punya Akun? Masuk
    </a>
  </div>
</body>
</html>
