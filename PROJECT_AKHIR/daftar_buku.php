<?php
include __DIR__ . '/Config/koneksi.php';

// CRUD
if (isset($_POST['tambah'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $bahasa = mysqli_real_escape_string($conn, $_POST['bahasa']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $jumlah_halaman = intval($_POST['jumlah_halaman']);
    $cover = "";

    if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION));
        $namaFile = preg_replace('/[^A-Za-z0-9_\-]/', '_', strtolower($judul)) . '.' . $ext;
        $tujuan = "assets/" . $namaFile;
        if (move_uploaded_file($_FILES['cover_file']['tmp_name'], __DIR__ . '/' . $tujuan)) {
            $cover = $namaFile;
        }
    }

    $sql = "INSERT INTO Buku (judul, penulis, genre, tahun_terbit, bahasa, penerbit, jumlah_halaman, cover)
            VALUES ('$judul', '$penulis', '$genre', '$tahun_terbit', '$bahasa', '$penerbit', '$jumlah_halaman', '$cover')";
    mysqli_query($conn, $sql);
    header("Location: daftar_buku.php");
    exit;
}

if (isset($_POST['edit'])) {
    $id = intval($_POST['kode_buku']);
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $bahasa = mysqli_real_escape_string($conn, $_POST['bahasa']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $jumlah_halaman = intval($_POST['jumlah_halaman']);

    // Ambil cover lama
    $cover = "";
    if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['cover_file']['name'], PATHINFO_EXTENSION));
        $namaFile = preg_replace('/[^A-Za-z0-9_\-]/', '_', strtolower($judul)) . '.' . $ext;
        $tujuan = "assets/" . $namaFile;
        if (move_uploaded_file($_FILES['cover_file']['tmp_name'], __DIR__ . '/' . $tujuan)) {
            $cover = $namaFile;
        }
    } else {
        $q = mysqli_query($conn, "SELECT cover FROM Buku WHERE kode_buku='$id'");
        $old = mysqli_fetch_assoc($q);
        $cover = $old['cover'];
    }

    $sql = "UPDATE Buku SET judul='$judul', penulis='$penulis', genre='$genre', tahun_terbit='$tahun_terbit',
            bahasa='$bahasa', penerbit='$penerbit', jumlah_halaman='$jumlah_halaman', cover='$cover'
            WHERE kode_buku='$id'";
    mysqli_query($conn, $sql);
    header("Location: daftar_buku.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $sql = "DELETE FROM Buku WHERE kode_buku='$id'";
    mysqli_query($conn, $sql);
    header("Location: daftar_buku.php");
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $q = mysqli_query($conn, "SELECT * FROM Buku WHERE kode_buku='$id'");
    $editData = mysqli_fetch_assoc($q);
}

$sql = "SELECT * FROM Buku";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Buku | BookCollect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .btn-action {
      background-color: #2a4365;
      border: none;
      color: white;
      font-weight: 600;
      padding: 6px 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s;
      font-size: 14px;
      margin-right: 8px;
      margin-bottom: 4px;
      display: inline-block;
    }
    .btn-action:hover {
      background-color: #4c6eb1;
    }
    .btn-danger {
      background-color: #c0392b;
    }
    .btn-danger:hover {
      background-color: #a93226;
    }
    .btn-tambah {
      background-color: #2a4365;
      color: white;
      font-weight: 700;
      padding: 12px 24px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
      border: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .btn-tambah:hover {
      background-color: #4c6eb1;
    }
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.5);
      z-index: 999;
      justify-content: center;
      align-items: center;
    }
    .modal.show {
      display: flex;
    }
    .modal-content {
      background: white;
      padding: 20px 18px;
      border-radius: 12px;
      width: 95vw;
      max-width: 350px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      font-size: 14px;
    }
  </style>
</head>
<body class="bg-lightbg text-mediumslate font-sans">
  <header class="sticky top-0 z-50 bg-gradient-to-r from-lightbg via-softsky to-lightblue backdrop-blur shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <div>
        <a href="dashboard.php" class="btn-action" style="background:#e38e49;">Kembali</a>
      </div>
      <h1 class="text-2xl font-bold font-heading text-mediumslate">📚 BookCollect</h1>
      <div></div>
    </div>
  </header>
  <section class="py-14 px-6">
    <div class="max-w-6xl mx-auto">
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-deepblue">📘 Daftar Buku</h2>
        <button id="btn-tambah" class="btn-tambah">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path fill="white" d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z"/></svg>
          Tambah Buku
        </button>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <?php while($buku = mysqli_fetch_assoc($result)):
    $coverFile = isset($buku['cover']) ? $buku['cover'] : '';
    $coverFullPath = __DIR__ . '/assets/' . $coverFile;
    $coverPath = (!empty($coverFile) && file_exists($coverFullPath)) ? 'assets/' . $coverFile : 'img/sample-book.jpg';
?>
<div class="bg-white rounded-xl shadow border border-lightblue flex flex-col items-center p-4 hover:shadow-lg transition">
  <div class="h-40 w-full bg-softsky rounded mb-3 flex items-center justify-center overflow-hidden">
    <img src="<?= $coverPath ?>" alt="<?= htmlspecialchars($buku['judul']) ?>" class="max-h-full max-w-full object-contain" style="background:#e5eaf7; width:100%; height:100%; border-radius:8px;" />
  </div>
  <p class="font-bold text-deepblue text-center"><?= htmlspecialchars($buku['judul']) ?></p>
  <p class="text-sm text-mediumslate text-center"><?= htmlspecialchars($buku['penulis'] ?? '') ?></p>
  <p class="text-xs text-royalblue text-center"><?= htmlspecialchars($buku['genre'] ?? '') ?></p>
  <p class="text-xs text-orangegold text-center"><?= htmlspecialchars($buku['tahun_terbit'] ?? '') ?></p>
  <p class="text-xs text-gray-500 text-center"><?= htmlspecialchars($buku['bahasa'] ?? '') ?></p>
  <p class="text-xs text-gray-500 text-center"><?= htmlspecialchars($buku['penerbit'] ?? '') ?></p>
  <p class="text-xs text-gray-500 text-center"><?= htmlspecialchars($buku['jumlah_halaman'] ?? '') ?> halaman</p>
  <div class="flex gap-2 mt-3">
    <button class="btn-action" onclick="showEdit(<?= $buku['kode_buku'] ?>)">Edit</button>
    <a href="daftar_buku.php?hapus=<?= $buku['kode_buku'] ?>" class="btn-action btn-danger" onclick="return confirm('Hapus buku ini?')">Hapus</a>
  </div>
</div>
<?php endwhile; ?>
      </div>
    </div>
  </section>

  <!-- Modal Form Tambah/Edit -->
  <div id="modal-form" class="modal<?php if ($editData) echo ' show'; ?>">
    <div class="modal-content">
      <h2 id="form-title" style="color:#2a4365;margin-top:0;">
        <?= $editData ? 'Edit Buku' : 'Tambah Buku' ?>
      </h2>
      <form id="form-buku" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="kode_buku" id="kode_buku" value="<?= $editData['kode_buku'] ?? '' ?>">
        <div style="margin-bottom:12px;">
          <label>Judul</label><br/>
          <input type="text" name="judul" id="judul" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['judul'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Penulis</label><br/>
          <input type="text" name="penulis" id="penulis" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['penulis'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Genre</label><br/>
          <input type="text" name="genre" id="genre" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['genre'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Tahun Terbit</label><br/>
          <input type="number" name="tahun_terbit" id="tahun_terbit" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['tahun_terbit'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Bahasa</label><br/>
          <input type="text" name="bahasa" id="bahasa" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['bahasa'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Penerbit</label><br/>
          <input type="text" name="penerbit" id="penerbit" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['penerbit'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Jumlah Halaman</label><br/>
          <input type="number" name="jumlah_halaman" id="jumlah_halaman" min="1" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" value="<?= htmlspecialchars($editData['jumlah_halaman'] ?? '') ?>">
        </div>
        <div style="margin-bottom:12px;">
          <label>Cover (upload gambar, jpg/png)</label><br/>
          <input type="file" name="cover_file" accept="image/*" style="margin-top:4px;">
          <?php if (!empty($editData['cover']) && file_exists(__DIR__ . '/assets/' . $editData['cover'])): ?>
            <div style="margin-top:8px;">
              <img src="assets/<?= $editData['cover'] ?>" alt="cover" style="max-width:80px;max-height:100px;border-radius:6px;">
            </div>
          <?php endif; ?>
        </div>
        <div style="text-align:right;">
          <button type="button" id="btn-batal" class="btn-action" style="background:#ccc;color:#333;">Batal</button>
          <button type="submit" id="btn-submit" name="<?= $editData ? 'edit' : 'tambah' ?>" class="btn-action"><?= $editData ? 'Simpan' : 'Tambah' ?></button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Modal logic
    const modal = document.getElementById('modal-form');
    const btnTambah = document.getElementById('btn-tambah');
    const btnBatal = document.getElementById('btn-batal');
    const formTitle = document.getElementById('form-title');
    const kodeBukuInput = document.getElementById('kode_buku');
    const judulInput = document.getElementById('judul');
    const penulisInput = document.getElementById('penulis');
    const tahunInput = document.getElementById('tahun_terbit');
    const genreInput = document.getElementById('genre');
    const btnSubmit = document.getElementById('btn-submit');

    // Tambah Buku
    if (btnTambah) {
      btnTambah.onclick = function() {
        formTitle.innerText = 'Tambah Buku';
        kodeBukuInput.value = '';
        judulInput.value = '';
        penulisInput.value = '';
        tahunInput.value = '';
        genreInput.value = '';
        btnSubmit.name = 'tambah';
        btnSubmit.innerText = 'Tambah';
        modal.classList.add('show');
      };
    }
    if (btnBatal) {
      btnBatal.onclick = function() {
        modal.classList.remove('show');
        // Jika sedang edit, kembali ke halaman tanpa parameter edit
        if (window.location.search.includes('edit=')) {
          window.location.href = 'daftar_buku.php';
        }
      };
    }

    // Edit Buku
    window.showEdit = function(id) {
      window.location.href = 'daftar_buku.php?edit=' + id;
    };
  </script>
</body>
</html>