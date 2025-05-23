<?php
<?php
include __DIR__ . '/koneksi.php';

$limit = isset($_GET['all']) ? '' : 'LIMIT 5';
$sql = "SELECT * FROM Buku ORDER BY kode_buku DESC $limit";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode($data);