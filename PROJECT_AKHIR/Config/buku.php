<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include __DIR__ . '/koneksi.php';

$sql = "SELECT * FROM Buku ORDER BY kode_buku DESC";
$result = mysqli_query($conn, $sql);
$semuaBuku = [];
while ($row = mysqli_fetch_assoc($result)) {
    $semuaBuku[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku - BookCollect</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
            margin: 0;
            color: #2a4365;
        }
        header {
            display: flex;
            align-items: center;
            background-color: #2a4365;
            padding: 15px 30px;
            color: white;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1.2px;
            justify-content: space-between;
        }
        .logo-book { margin-right: 12px; user-select: none;}
        .logout-btn {
            background: #2a4365;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-left: 24px;
        }
        .logout-btn:hover { background: #4c6eb1;}
        h1 {
            margin: 32px 0 16px 0;
            text-align: center;
            color: #2c5282;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .buku-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 32px;
            padding: 32px 48px;
            max-width: 1200px;
            margin: 0 auto 40px auto;
        }
        .buku-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(42,67,101,0.10);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 18px 18px 18px;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
        }
        .buku-card:hover {
            box-shadow: 0 8px 24px rgba(76,110,177,0.18);
            transform: translateY(-4px) scale(1.03);
        }
        .buku-card img {
            width: 140px;
            height: 210px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 18px;
            background: #e2e8f0;
            box-shadow: 0 2px 8px rgba(44,82,130,0.08);
        }
        .buku-card h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #2a4365;
            font-weight: 700;
            text-align: center;
            min-height: 48px;
            line-height: 1.2;
        }
        .buku-info {
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
        }
        .buku-info span {
            display: block;
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 2px;
        }
        .buku-genre {
            display: inline-block;
            background: #e2e8f0;
            color: #2a4365;
            border-radius: 6px;
            padding: 3px 12px;
            font-size: 13px;
            margin-top: 6px;
            font-weight: 600;
        }
        @media (max-width: 700px) {
            .buku-grid { padding: 16px 4px; gap: 16px; }
            .buku-card { padding: 12px 6px; }
            .buku-card img { width: 100px; height: 150px; }
        }
    </style>
</head>
<body>
    <header>
        <div style="display:flex;align-items:center;">
            <div class="logo-book">📚</div>
            <div>BookCollect</div>
        </div>
        <a href="index.html" class="logout-btn">Logout</a>
    </header>
    <h1>Daftar Semua Buku</h1>
    <div class="buku-grid">
        <?php foreach ($semuaBuku as $buku): ?>
            <div class="buku-card">
                <img src="<?php echo htmlspecialchars($buku['cover'] ?: 'img/sample-book.jpg'); ?>" alt="Cover Buku">
                <h3><?php echo htmlspecialchars($buku['judul']); ?></h3>
                <div class="buku-info">
                    <span>Penulis: <?php echo htmlspecialchars($buku['penulis']); ?></span>
                    <span class="buku-genre"><?php echo htmlspecialchars($buku['genre']); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>