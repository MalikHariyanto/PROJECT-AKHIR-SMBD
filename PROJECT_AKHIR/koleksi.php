<?php
session_start();
include __DIR__ . '/Config/koneksi.php';

$id_pengguna = $_SESSION['id_pengguna'] ?? 1;

// CRUD
if (isset($_POST['tambah'])) {
    $kode_buku = $_POST['kode_buku'];
    $status_baca = $_POST['status_baca'];
    $progres = $_POST['progres'];
    $catatan = $_POST['catatan'];
    $tanggal_mulai = $_POST['tanggal_mulai'] ?: null;
    $tanggal_selesai = $_POST['tanggal_selesai'] ?: null;
    $sql = "INSERT INTO Buku_pengguna (id_pengguna, kode_buku, status_baca, progres, tanggal_mulai, tanggal_selesai, catatan)
            VALUES ('$id_pengguna', '$kode_buku', '$status_baca', '$progres', " . ($tanggal_mulai ? "'$tanggal_mulai'" : "NULL") . ", " . ($tanggal_selesai ? "'$tanggal_selesai'" : "NULL") . ", '$catatan')";
    mysqli_query($conn, $sql);
    header("Location: koleksi.php");
    exit;
}
if (isset($_POST['edit'])) {
    $id = $_POST['id_bukupengguna'];
    $status_baca = $_POST['status_baca'];
    $progres = $_POST['progres'];
    $catatan = $_POST['catatan'];
    $tanggal_mulai = $_POST['tanggal_mulai'] ?: null;
    $tanggal_selesai = $_POST['tanggal_selesai'] ?: null;
    $sql = "UPDATE Buku_pengguna SET status_baca='$status_baca', progres='$progres', tanggal_mulai=" . ($tanggal_mulai ? "'$tanggal_mulai'" : "NULL") . ", tanggal_selesai=" . ($tanggal_selesai ? "'$tanggal_selesai'" : "NULL") . ", catatan='$catatan' WHERE id_bukupengguna='$id' AND id_pengguna='$id_pengguna'";
    mysqli_query($conn, $sql);
    header("Location: koleksi.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $sql = "DELETE FROM Buku_pengguna WHERE id_bukupengguna='$id' AND id_pengguna='$id_pengguna'";
    mysqli_query($conn, $sql);
    header("Location: koleksi.php");
    exit;
}
$sql = "SELECT bp.*, b.judul FROM Buku_pengguna bp JOIN Buku b ON bp.kode_buku = b.kode_buku WHERE bp.id_pengguna='$id_pengguna'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Koleksi Buku Pengguna</title>
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
    <span>Koleksi Buku</span>
  </header>
  <main>
    <h1>Koleksi Buku</h1>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>ID Pengguna</th>
          <th>Judul Buku</th>
          <th>Kode Buku</th>
          <th>Status Baca</th>
          <th>Progres</th>
          <th>Tanggal Mulai</th>
          <th>Tanggal Selesai</th>
          <th>Catatan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="koleksi-body">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['id_bukupengguna']) ?></td>
          <td><?= htmlspecialchars($row['id_pengguna']) ?></td>
          <td><?= htmlspecialchars($row['judul']) ?></td>
          <td><?= htmlspecialchars($row['kode_buku']) ?></td>
          <td><?= htmlspecialchars($row['status_baca']) ?></td>
          <td><?= htmlspecialchars($row['progres']) ?>%</td>
          <td><?= htmlspecialchars($row['tanggal_mulai']) ?></td>
          <td><?= htmlspecialchars($row['tanggal_selesai']) ?></td>
          <td><?= htmlspecialchars($row['catatan']) ?></td>
          <td>
            <a href="koleksi.php?edit=<?= $row['id_bukupengguna'] ?>" class="btn-action">Edit</a>
            <a href="koleksi.php?hapus=<?= $row['id_bukupengguna'] ?>" class="btn-action" style="background:#c0392b;" onclick="return confirm('Hapus koleksi ini?')">Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </main>
  <button class="btn-tambah" id="btn-tambah" title="Tambah Koleksi">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
      <path d="M19 11H13V5h-2v6H5v2h6v6h2v-6h6z"/>
    </svg>
    Tambah
  </button>
  <div class="modal" id="modal-form">
    <div class="modal-content">
      <h2 id="form-title" style="color:#2a4365;margin-top:0;">Tambah Koleksi</h2>
      <form id="form-koleksi" method="post">
        <?php if (isset($_GET['edit'])):
          $id = $_GET['edit'];
          $q = mysqli_query($conn, "SELECT * FROM Buku_pengguna WHERE id_bukupengguna='$id' AND id_pengguna='$id_pengguna'");
          $edit = mysqli_fetch_assoc($q);
        ?>
        <input type="hidden" name="id_bukupengguna" value="<?= $edit['id_bukupengguna'] ?>">
        <div style="margin-bottom:12px;">
          <label>Status Baca</label><br/>
          <select name="status_baca" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
            <option <?= $edit['status_baca']=='Belum Dibaca'?'selected':'' ?>>Belum Dibaca</option>
            <option <?= $edit['status_baca']=='Sedang Dibaca'?'selected':'' ?>>Sedang Dibaca</option>
            <option <?= $edit['status_baca']=='Selesai'?'selected':'' ?>>Selesai</option>
          </select>
        </div>
        <div style="margin-bottom:12px;">
          <label>Progres (%)</label><br/>
          <input type="number" name="progres" value="<?= $edit['progres'] ?>" min="0" max="100" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Tanggal Mulai</label><br/>
          <input type="date" name="tanggal_mulai" value="<?= $edit['tanggal_mulai'] ?>" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Tanggal Selesai</label><br/>
          <input type="date" name="tanggal_selesai" value="<?= $edit['tanggal_selesai'] ?>" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Catatan</label><br/>
          <input type="text" name="catatan" value="<?= htmlspecialchars($edit['catatan']) ?>" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="text-align:right;">
          <a href="koleksi.php" class="btn-action" style="background:#ccc;color:#333;">Batal</a>
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
          <label>Status Baca</label><br/>
          <select name="status_baca" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
            <option>Belum Dibaca</option>
            <option>Sedang Dibaca</option>
            <option>Selesai</option>
          </select>
        </div>
        <div style="margin-bottom:12px;">
          <label>Progres (%)</label><br/>
          <input type="number" name="progres" min="0" max="100" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Tanggal Mulai</label><br/>
          <input type="date" name="tanggal_mulai" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Tanggal Selesai</label><br/>
          <input type="date" name="tanggal_selesai" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="margin-bottom:12px;">
          <label>Catatan</label><br/>
          <input type="text" name="catatan" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
        </div>
        <div style="text-align:right;">
          <a href="koleksi.php" class="btn-action" style="background:#ccc;color:#333;">Batal</a>
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
        window.location.href = 'koleksi.php';
      }
    });
    <?php if (isset($_GET['edit'])): ?>
      document.getElementById('modal-form').style.display = 'flex';
    <?php endif; ?>
  </script>
</body>
</html>
