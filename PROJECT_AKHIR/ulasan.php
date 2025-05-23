<?php
session_start();
include __DIR__ . '/Config/koneksi.php';

$id_pengguna = $_SESSION['id_pengguna'] ?? 1;

// CRUD
if (isset($_POST['tambah'])) {
    $kode_buku = $_POST['kode_buku'];
    $rating = $_POST['rating'];
    $isi_ulasan = $_POST['isi_ulasan'];
    $skor_keterbacaan = $_POST['skor_keterbacaan'];
    $sql = "INSERT INTO Ulasan (id_pengguna, kode_buku, rating, isi_ulasan, skor_keterbacaan)
            VALUES ('$id_pengguna', '$kode_buku', '$rating', '$isi_ulasan', '$skor_keterbacaan')";
    mysqli_query($conn, $sql);
    header("Location: ulasan.php");
    exit;
}
if (isset($_POST['edit'])) {
    $id = $_POST['id_ulasan'];
    $rating = $_POST['rating'];
    $isi_ulasan = $_POST['isi_ulasan'];
    $skor_keterbacaan = $_POST['skor_keterbacaan'];
    $sql = "UPDATE Ulasan SET rating='$rating', isi_ulasan='$isi_ulasan', skor_keterbacaan='$skor_keterbacaan' WHERE id_ulasan='$id' AND id_pengguna='$id_pengguna'";
    mysqli_query($conn, $sql);
    header("Location: ulasan.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM Ulasan WHERE id_ulasan='$id' AND id_pengguna='$id_pengguna'";
    mysqli_query($conn, $sql);
    header("Location: ulasan.php");
    exit;
}
$sql = "SELECT u.*, b.judul FROM Ulasan u JOIN Buku b ON u.kode_buku = b.kode_buku";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ulasan Buku</title>
  <style>
    body {margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background-color:#f9fafb;color:#333;}
    header {display:flex;align-items:center;background-color:#2a4365;padding:15px 30px;color:white;box-shadow:0 2px 5px rgba(0,0,0,0.1);user-select:none;cursor:pointer;font-size:28px;font-weight:700;letter-spacing:1.2px;}
    main {padding:30px 40px;min-height:80vh;}
    h1 {color:#2c5282;font-weight:700;margin-top:0;margin-bottom:20px;}
    table {width:100%;border-collapse:collapse;box-shadow:0 0 10px rgba(0,0,0,0.1);border-radius:8px;overflow:hidden;background-color:white;}
    thead {background-color:#2a4365;color:#edf2f7;}
    th,td {padding:12px 16px;border-bottom:1px solid #e2e8f0;text-align:left;vertical-align:middle;max-width:150px;word-wrap:break-word;}
    tbody tr:hover {background-color:#bee3f8;cursor:pointer;}
    tbody tr:nth-child(even) {background-color:#f7fafc;}
    .btn-action {background-color:#2a4365;border:none;color:white;font-weight:600;padding:6px 10px;margin-left:8px;border-radius:6px;cursor:pointer;transition:background-color 0.2s ease;font-size:14px;display:inline-flex;align-items:center;gap:6px;}
    .btn-action:hover {background-color:#4c6eb1;}
    .btn-tambah {position:fixed;right:40px;bottom:40px;background-color:#2a4365;border:none;color:white;font-weight:700;padding:16px 24px;border-radius:50px;cursor:pointer;box-shadow:0 6px 12px rgba(42,67,101,0.3);font-size:16px;transition:background-color 0.3s ease;user-select:none;z-index:100;display:inline-flex;align-items:center;gap:8px;}
    .btn-tambah:hover {background-color:#4c6eb1;}
    svg {width:16px;height:16px;fill:white;}
    .modal {display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:999;justify-content:center;align-items:center;}
    .modal-content {background:white;padding:24px 32px;border-radius:12px;width:90%;max-width:500px;box-shadow:0 8px 20px rgba(0,0,0,0.2);font-size:14px;}
  </style>
</head>
<body>
  <header id="header">
    <button id="btn-back" style="background:none;border:none;color:white;margin-right:18px;cursor:pointer;display:flex;align-items:center;padding:0;">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24">
        <path d="M15 19l-7-7 7-7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <span>Ulasan Buku</span>
  </header>
  <main>
    <h1>Ulasan Buku</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>ID Pengguna</th>
          <th>Judul Buku</th>
          <th>Kode Buku</th>
          <th>Rating</th>
          <th>Isi Ulasan</th>
          <th>Tanggal Ulasan</th>
          <th>Jumlah Like</th>
          <th>Skor Keterbacaan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="ulasan-body">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['id_ulasan']) ?></td>
          <td><?= htmlspecialchars($row['id_pengguna']) ?></td>
          <td><?= htmlspecialchars($row['judul']) ?></td>
          <td><?= htmlspecialchars($row['kode_buku']) ?></td>
          <td><?= htmlspecialchars($row['rating']) ?></td>
          <td><?= htmlspecialchars($row['isi_ulasan']) ?></td>
          <td><?= htmlspecialchars($row['tanggal_ulasan']) ?></td>
          <td><?= htmlspecialchars($row['jumlah_like']) ?></td>
          <td><?= htmlspecialchars($row['skor_keterbacaan']) ?></td>
          <td>
            <?php if ($row['id_pengguna'] == $id_pengguna): ?>
              <a href="ulasan.php?edit=<?= $row['id_ulasan'] ?>" class="btn-action">Edit</a>
              <a href="ulasan.php?hapus=<?= $row['id_ulasan'] ?>" class="btn-action" style="background:#c0392b;" onclick="return confirm('Hapus ulasan ini?')">Hapus</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
  <button class="btn-tambah" id="btn-tambah" title="Tambah Ulasan">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z"/>
    </svg>
    Tambah
  </button>
  <div class="modal" id="modal-form">
    <div class="modal-content">
      <h2 id="form-title" style="color:#2a4365;margin-top:0;">Tambah Ulasan</h2>
      <form id="form-ulasan" method="post">
        <?php if (isset($_GET['edit'])):
          $id = $_GET['edit'];
          $q = mysqli_query($conn, "SELECT * FROM Ulasan WHERE id_ulasan='$id' AND id_pengguna='$id_pengguna'");
          $edit = mysqli_fetch_assoc($q);
        ?>
        <input type="hidden" name="id_ulasan" value="<?= $edit['id_ulasan'] ?>">
        <div style="margin-bottom:12px;">
          <label>Rating</label><br/>
          <input type="number" name="rating" value="<?= $edit['rating'] ?>" min="1" max="5" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Skor Keterbacaan</label><br/>
          <input type="number" name="skor_keterbacaan" value="<?= $edit['skor_keterbacaan'] ?>" min="1" max="10" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Isi Ulasan</label><br/>
          <input type="text" name="isi_ulasan" value="<?= htmlspecialchars($edit['isi_ulasan']) ?>" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="text-align:right;">
          <a href="ulasan.php" class="btn-action" style="background:#ccc;color:#333;">Batal</a>
          <button type="submit" name="edit" class="btn-action">Simpan</button>
        </div>
        <?php else: ?>
        <div style="margin-bottom:12px;">
          <label>Buku</label><br/>
          <select name="kode_buku" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
            <?php
            $buku = mysqli_query($conn, "SELECT kode_buku, judul FROM Buku");
            while($b = mysqli_fetch_assoc($buku)):
            ?>
              <option value="<?= $b['kode_buku'] ?>"><?= htmlspecialchars($b['judul']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div style="margin-bottom:12px;">
          <label>Rating</label><br/>
          <input type="number" name="rating" min="1" max="5" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Skor Keterbacaan</label><br/>
          <input type="number" name="skor_keterbacaan" min="1" max="10" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Isi Ulasan</label><br/>
          <input type="text" name="isi_ulasan" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="text-align:right;">
          <a href="ulasan.php" class="btn-action" style="background:#ccc;color:#333;">Batal</a>
          <button type="submit" name="tambah" class="btn-action">Tambah</button>
        </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
  <script>
    document.getElementById('header').addEventListener('click', () => {
      window.location.href = 'dashboard.php';
    });
    document.getElementById('btn-back').onclick = function() {
      window.location.href = 'dashboard.php';
    };
    document.getElementById('btn-tambah').onclick = function() {
      document.getElementById('modal-form').style.display = 'flex';
    };
    document.querySelectorAll('.btn-action[style*="background:#ccc"]').forEach(btn => {
      btn.onclick = function(e) {
        e.preventDefault();
        document.getElementById('modal-form').style.display = 'none';
        window.location.href = 'ulasan.php';
      }
    });
    <?php if (isset($_GET['edit'])): ?>
      document.getElementById('modal-form').style.display = 'flex';
    <?php endif; ?>
  </script>
</body>
</html>
