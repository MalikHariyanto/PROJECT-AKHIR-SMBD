<?php
include __DIR__ . '/Config/koneksi.php';

// 5 Buku Populer (misal: paling banyak dipinjam, di sini contoh pakai random)
$sql = "SELECT * FROM Buku ORDER BY RAND() LIMIT 5";
$result = mysqli_query($conn, $sql);
$bukuPopuler = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bukuPopuler[] = $row;
}

// 5 Buku dengan Rating Tertinggi (misal: rating di tabel Buku)
$sql = "SELECT b.*, v.rata_rata_rating 
        FROM view_buku_rating_tertinggi v
        JOIN Buku b ON b.kode_buku = v.kode_buku
        ORDER BY v.rata_rata_rating DESC
        LIMIT 5";
$result = mysqli_query($conn, $sql);
$bukuRatingTertinggi = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bukuRatingTertinggi[] = $row;
}

// Semua Buku untuk Section Buku
$sql = "SELECT * FROM Buku";
$result = mysqli_query($conn, $sql);
$semuaBuku = [];
while ($row = mysqli_fetch_assoc($result)) {
    $semuaBuku[] = $row;
}

// Semua Genre unik
$sql = "SELECT DISTINCT genre FROM Buku";
$result = mysqli_query($conn, $sql);
$semuaGenre = [];
while ($row = mysqli_fetch_assoc($result)) {
    $semuaGenre[] = $row['genre'];
}

// Semua Penulis unik
$sql = "SELECT DISTINCT penulis FROM Buku";
$result = mysqli_query($conn, $sql);
$semuaPenulis = [];
while ($row = mysqli_fetch_assoc($result)) {
    $semuaPenulis[] = $row['penulis'];
}

// Query Best Seller dari view_buku_populer (ambil 5 teratas)
$sql = "SELECT b.*, v.jumlah_transaksi 
        FROM view_buku_populer v
        JOIN Buku b ON b.kode_buku = v.kode_buku
        ORDER BY v.jumlah_transaksi DESC
        LIMIT 5";
$result = mysqli_query($conn, $sql);
$bestSeller = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bestSeller[] = $row;
}

// Ambil 5 buku dari tabel Buku untuk section Buku
$sql = "SELECT * FROM Buku ORDER BY kode_buku DESC LIMIT 5";
$result = mysqli_query($conn, $sql);
$daftarBuku = [];
while ($row = mysqli_fetch_assoc($result)) {
    $daftarBuku[] = $row;
}

// Ambil data dari view buku_urut_genre
$sql = "SELECT * FROM buku_urut_genre";
$result = mysqli_query($conn, $sql);
$bukuUrutGenre = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bukuUrutGenre[] = $row;
}

// Ambil data dari tabel buku_penulis
$sql = "SELECT * FROM buku_penulis";
$result = mysqli_query($conn, $sql);
$bukuPenulis = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bukuPenulis[] = $row;
}

// Ambil data dari tabel rangking_pengguna
$sql = "SELECT
  p.id_pengguna,
  p.nama,
  p.email,
  COUNT(bp.id_bukupengguna) AS jumlah_buku_selesai
FROM 
  Pengguna p
LEFT JOIN 
  Buku_pengguna bp ON p.id_pengguna = bp.id_pengguna AND bp.status_baca = 'Selesai'
GROUP BY 
  p.id_pengguna, p.nama, p.email
ORDER BY 
  jumlah_buku_selesai DESC";
$result = mysqli_query($conn, $sql);
$rankingPengguna = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rankingPengguna[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BookCollect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            lightbg: '#F8FAFC',
            softsky: '#D9EAFD',
            lightslate: '#BCCCDC',
            mediumslate: '#5C6C7D',
            royalblue: '#1F509A',
            deepblue: '#0A3981',
            lightblue: '#EAF4FC',
            orangegold: '#E38E49'
          },
          fontFamily: {
            heading: ['Inter', 'sans-serif']
          }
        }
      }
    };
  </script>
  <style>
  /* Sembunyikan scrollbar horizontal di semua bar scroll buku */
  .hide-scrollbar {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE 10+ */
  }
  .hide-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome/Safari/Webkit */
  }
</style>
</head>
<body class="bg-lightbg text-mediumslate font-sans">

<!-- Header -->
<header class="sticky top-0 z-50 bg-gradient-to-r from-lightbg via-softsky to-lightblue backdrop-blur shadow">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold font-heading text-mediumslate">📚 BookCollect</h1>
    <div class="hidden md:flex items-center gap-6">
      <nav class="flex gap-6 text-sm text-lightslate font-medium">
        <a href="ulasan.php" class="hover:text-mediumslate">Ulasan</a>
        <a href="koleksi.php" class="hover:text-mediumslate">Koleksi</a>
        <a href="transaksi.php" class="hover:text-mediumslate">Transaksi</a>
        <a href="wishlist.php" class="hover:text-mediumslate">Wishlist</a>
      </nav>
      <a href="login.php" class="ml-4 bg-orangegold text-white px-4 py-2 rounded-full text-xs font-semibold hover:bg-[#cf7939] transition">Logout</a>
    </div>
  </div>
</header>

<!-- Bagian Populer -->
<section id="populer" class="py-14 px-6">
  <div class="max-w-6xl mx-auto">
    
    <!-- Best Seller -->
    <h2 class="text-3xl font-bold text-deepblue mb-4">🔥 Best Seller</h2>
    <div class="flex space-x-4 overflow-x-auto pb-4 hide-scrollbar">
      <?php foreach ($bestSeller as $buku): 
        $coverFile = isset($buku['cover']) ? $buku['cover'] : '';
        $coverFullPath = __DIR__ . '/assets/' . $coverFile;
        $coverPath = (!empty($coverFile) && file_exists($coverFullPath)) ? 'assets/' . $coverFile : 'img/sample-book.jpg';
      ?>
        <div class="w-[220px] min-w-[220px] max-w-[220px] bg-lightblue rounded-xl p-4 shadow border border-white shrink-0 flex flex-col items-center">
          <div class="h-40 w-full bg-white rounded mb-3 flex items-center justify-center overflow-hidden">
            <img src="<?= $coverPath ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" class="max-h-full max-w-full object-contain" style="background:#e5eaf7; width:100%; height:100%; border-radius:8px;" />
          </div>
          <p class="font-bold text-deepblue text-center"><?= htmlspecialchars($buku['judul']) ?></p>
          <p class="text-sm text-mediumslate text-center"><?= htmlspecialchars($buku['penulis'] ?? '') ?></p>
          <p class="text-xs text-royalblue text-center"><?= htmlspecialchars($buku['genre'] ?? '') ?></p>
          <p class="text-xs text-orangegold text-center"><?= htmlspecialchars($buku['tahun_terbit'] ?? '') ?></p>
          <p class="text-xs text-gray-500 text-center mt-2">Transaksi: <span class="font-semibold"><?= $buku['jumlah_transaksi'] ?></span></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Rating Tertinggi -->
    <h2 class="text-3xl font-bold text-orangegold mt-8 mb-4">⭐ Rating Tertinggi</h2>
    <div class="flex space-x-4 overflow-x-auto pb-4 hide-scrollbar">
      <?php foreach ($bukuRatingTertinggi as $buku): 
        $coverFile = isset($buku['cover']) ? $buku['cover'] : '';
        $coverFullPath = __DIR__ . '/assets/' . $coverFile;
        $coverPath = (!empty($coverFile) && file_exists($coverFullPath)) ? 'assets/' . $coverFile : 'img/sample-book.jpg';
      ?>
        <div class="min-w-[220px] bg-lightblue rounded-xl p-4 shadow border border-white shrink-0 flex flex-col items-center">
          <div class="h-40 bg-white rounded mb-3 flex items-center justify-center overflow-hidden">
            <img src="<?= $coverPath ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" class="max-h-full max-w-full object-contain" style="background:#e5eaf7; width:100%; height:100%; border-radius:8px;" />
          </div>
          <p class="font-bold text-deepblue text-center"><?= htmlspecialchars($buku['judul']) ?></p>
          <p class="text-sm text-mediumslate text-center"><?= htmlspecialchars($buku['penulis'] ?? '') ?></p>
          <p class="text-xs text-royalblue text-center"><?= htmlspecialchars($buku['genre'] ?? '') ?></p>
          <p class="text-xs text-orangegold text-center"><?= htmlspecialchars($buku['tahun_terbit'] ?? '') ?></p>        </div>
      <?php endforeach; ?>
    </div>

    <!-- Section Buku Dinamis -->
    <div class="flex items-center justify-between mt-8 mb-4">
      <h2 class="text-3xl font-bold text-deepblue">📘 Buku</h2>
      <a href="daftar_buku.php" class="bg-orangegold text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-[#cf7939] transition">View All</a>
    </div>
    <div class="flex space-x-4 overflow-x-auto pb-4 hide-scrollbar" id="buku-bar">
      <?php foreach ($daftarBuku as $buku): 
        $coverFile = isset($buku['cover']) ? $buku['cover'] : '';
        $coverFullPath = __DIR__ . '/assets/' . $coverFile;
        $coverPath = (!empty($coverFile) && file_exists($coverFullPath)) ? 'assets/' . $coverFile : 'img/sample-book.jpg';
      ?>
        <div class="min-w-[200px] bg-lightblue rounded-xl p-4 shadow border border-white shrink-0 flex flex-col items-center">
          <div class="h-40 w-full bg-white rounded mb-3 flex items-center justify-center overflow-hidden">
            <img src="<?= $coverPath ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" class="max-h-full max-w-full object-contain" style="background:#e5eaf7; width:100%; height:100%; border-radius:8px;" />
          </div>
          <p class="font-bold text-deepblue text-center"><?= htmlspecialchars($buku['judul']) ?></p>
          <p class="text-sm text-mediumslate text-center"><?= htmlspecialchars($buku['penulis'] ?? '') ?></p>
          <p class="text-xs text-royalblue text-center"><?= htmlspecialchars($buku['genre'] ?? '') ?></p>
          <p class="text-xs text-orangegold text-center"><?= htmlspecialchars($buku['tahun_terbit'] ?? '') ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Tabel Buku (hidden awal) -->
    <div id="buku-table" class="hidden mt-6">
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-xl shadow border border-lightblue">
          <thead class="bg-softsky text-deepblue">
            <tr>
              <th class="px-4 py-2 text-left">No</th>
              <th class="px-4 py-2 text-left">Cover</th>
              <th class="px-4 py-2 text-left">Judul</th>
              <th class="px-4 py-2 text-left">Penulis</th>
              <th class="px-4 py-2 text-left">Tahun</th>
              <th class="px-4 py-2 text-left">Genre</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($semuaBuku as $i => $buku): ?>
            <tr class="border-b hover:bg-softsky/50">
              <td class="px-4 py-2"><?= $i+1 ?></td>
              <td class="px-4 py-2">
                <img src="<?= $buku['cover'] ?? 'img/sample-book.jpg' ?>" alt="Cover Buku" class="h-16 w-12 object-cover rounded" />
              </td>
              <td class="px-4 py-2 font-semibold"><?= $buku['judul'] ?></td>
              <td class="px-4 py-2"><?= $buku['penulis'] ?? '' ?></td>
              <td class="px-4 py-2"><?= $buku['tahun_terbit'] ?? '' ?></td>
              <td class="px-4 py-2"><?= $buku['genre'] ?? '' ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <button id="showLessBuku" class="mt-4 bg-orangegold text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-[#cf7939] transition">Tutup Daftar Buku</button>
    </div>
  </div>
</section>

<script>
document.getElementById('viewAllBuku').onclick = function() {
  document.getElementById('buku-bar').classList.add('hidden');
  document.getElementById('buku-table').classList.remove('hidden');
  window.scrollTo({top: document.getElementById('buku-table').offsetTop - 80, behavior: 'smooth'});
};
document.getElementById('showLessBuku').onclick = function() {
  document.getElementById('buku-table').classList.add('hidden');
  document.getElementById('buku-bar').classList.remove('hidden');
  window.scrollTo({top: document.getElementById('buku-bar').offsetTop - 80, behavior: 'smooth'});
};
</script>

<!-- Genre Buku -->
<section id="genre" class="py-14 px-6 bg-gradient-to-r from-lightblue via-royalblue to-deepblue text-white">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-6">📖 Berdasarkan Genre</h2>
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
      <?php
      $genreIndex = 0;
      foreach ($semuaGenre as $genre):
        $genreId = 'genre-list-' . $genreIndex;
      ?>
        <div>
          <button type="button"
            class="bg-white rounded-lg shadow border border-white flex items-center justify-center min-h-[48px] px-2 py-2 w-full text-xs sm:text-sm font-semibold text-orangegold text-center hover:bg-orangegold hover:text-white transition"
            onclick="openOnlyGenre('<?= $genreId ?>')">
            <?= htmlspecialchars($genre ?: 'Tanpa Genre') ?>
          </button>
          <div id="<?= $genreId ?>" class="hidden mt-2">
            <ul class="bg-white rounded-lg shadow p-2 text-deepblue text-xs max-h-40 overflow-y-auto">
              <?php foreach ($bukuUrutGenre as $buku): ?>
                <?php if (($buku['genre'] ?? '') === $genre): ?>
                  <li class="py-1 border-b last:border-b-0">
                    <span class="font-semibold"><?= htmlspecialchars($buku['judul']) ?></span>
                    <span class="ml-1 text-[10px] text-mediumslate">(<?= htmlspecialchars($buku['penulis']) ?>, <?= htmlspecialchars($buku['tahun_terbit']) ?>)</span>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php $genreIndex++; endforeach; ?>
    </div>
  </div>
</section>

<script>
function openOnlyGenre(id) {
  // Tutup semua genre list
  document.querySelectorAll('[id^="genre-list-"]').forEach(function(el) {
    if (el.id !== id) el.classList.add('hidden');
  });
  // Toggle genre yang diklik
  var el = document.getElementById(id);
  el.classList.toggle('hidden');
}
</script>

<!-- Berdasarkan Penulis -->
<section id="penulis" class="py-14 px-6 bg-softsky">
  <div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-deepblue">✍️ Berdasarkan Penulis</h2>
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
      <?php
      $penulisIndex = 0;
      $penulisUnik = [];
      foreach ($bukuPenulis as $buku) {
        if (!in_array($buku['penulis'], $penulisUnik)) {
          $penulisUnik[] = $buku['penulis'];
        }
      }
      foreach ($penulisUnik as $penulis):
        $penulisId = 'penulis-list-' . $penulisIndex;
      ?>
        <div>
          <button type="button"
            class="bg-white rounded-lg shadow border border-white flex items-center justify-center min-h-[48px] px-2 py-2 w-full text-xs sm:text-sm font-semibold text-orangegold text-center hover:bg-orangegold hover:text-white transition"
            onclick="openOnlyPenulis('<?= $penulisId ?>')">
            <?= htmlspecialchars($penulis ?: 'Tanpa Penulis') ?>
          </button>
          <div id="<?= $penulisId ?>" class="hidden mt-2">
            <ul class="bg-white rounded-lg shadow p-2 text-deepblue text-xs max-h-40 overflow-y-auto">
              <?php foreach ($bukuPenulis as $buku): ?>
                <?php if (($buku['penulis'] ?? '') === $penulis): ?>
                  <li class="py-1 border-b last:border-b-0">
                    <span class="font-semibold"><?= htmlspecialchars($buku['judul']) ?></span>
                    <span class="ml-1 text-[10px] text-mediumslate">(<?= htmlspecialchars($buku['genre']) ?>, <?= htmlspecialchars($buku['tahun_terbit']) ?>)</span>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php $penulisIndex++; endforeach; ?>
    </div>
  </div>
</section>

<script>
function openOnlyPenulis(id) {
  document.querySelectorAll('[id^="penulis-list-"]').forEach(function(el) {
    if (el.id !== id) el.classList.add('hidden');
  });
  var el = document.getElementById(id);
  el.classList.toggle('hidden');
}
</script>

<!-- Ranking User Aktif -->
<section class="py-14 px-6 bg-lightslate/10">
  <div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-center">🏆 Ranking User Aktif</h2>
    <table class="w-full text-sm text-left text-mediumslate">
      <thead class="bg-softsky">
        <tr>
          <th class="px-4 py-3">Posisi</th>
          <th class="px-4 py-3">Nama User</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Jumlah Buku Selesai</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rankingPengguna as $i => $user): ?>
        <tr class="border-b">
          <td class="px-4 py-2 font-semibold"><?= $i+1 ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars(isset($user['nama']) ? $user['nama'] : '') ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars(isset($user['email']) ? $user['email'] : '') ?></td>
          <td class="px-4 py-2"><?= htmlspecialchars(isset($user['jumlah_buku_selesai']) ? $user['jumlah_buku_selesai'] : 0) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Tentang -->
<section class="py-14 px-6 bg-white/70">
  <div class="max-w-5xl mx-auto text-center">
    <h2 class="text-3xl font-bold mb-4">📚 Tentang BookCollect</h2>
    <p class="text-base leading-relaxed text-darkblue/80 max-w-3xl mx-auto">
      BookCollect adalah platform koleksi dan rekomendasi buku yang dirancang untuk para pecinta literasi. Dengan antarmuka yang lembut dan navigasi intuitif, kami memudahkan pengguna untuk menemukan buku-buku terbaik dari berbagai genre dan penulis favorit. Temukan bacaan baru, simpan ke wishlist, dan lacak transaksi peminjaman Anda dengan mudah.
    </p>
  </div>
</section>

<!-- Footer -->
<footer class="bg-softsky text-center text-sm py-6 text-mediumslate mt-12">
  © 2025 BookCollect. Dibuat dengan 💙 oleh ZORC
</footer>

</body>
</html>
