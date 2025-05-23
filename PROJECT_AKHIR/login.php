<?php
session_start();
include __DIR__ . '/Config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = (isset($_POST['email']) && $_POST['email'] !== null) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = (isset($_POST['password']) && $_POST['password'] !== null) ? $_POST['password'] : '';

    if ($email !== '' && $password !== '') {
        $sql = "SELECT * FROM Pengguna WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);

        if ($user && $password == $user['kata_sandi']) {
            $_SESSION['user'] = $user['email'];
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BookCollect • Masuk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <h1 class="text-2xl font-semibold text-center mb-6">Masuk ke BookCollect</h1>
    <?php if ($error): ?>
      <div class="mb-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="space-y-4">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" required
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"/>
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
        <input id="password" name="password" type="password" required minlength="3"
               class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"/>
      </div>
      <button type="submit"
              class="w-full py-2 px-4 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">
        Masuk
      </button>
    </form>
    <div class="my-6 flex items-center gap-3 text-sm text-gray-500">
      <span class="flex-1 border-t"></span> atau <span class="flex-1 border-t"></span>
    </div>
    <a href="register.php"
       class="block text-center py-2 px-4 rounded-md border border-indigo-600 text-indigo-600 hover:bg-indigo-50 transition">
      Buat Akun Baru
    </a>
  </div>
</body>
</html>