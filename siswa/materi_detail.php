<?php
require_once '../includes/auth_middleware.php';
checkSiswaAuth();

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

if (!isset($_GET['id'])) {
    die('Materi tidak ditemukan.');
}

$materi_id = intval($_GET['id']);

// Mengambil detail materi
$stmt = $conn->prepare("SELECT * FROM materi WHERE materi_id = ? AND is_active = 1");
$stmt->execute([$materi_id]);
$materi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$materi) {
    die('Materi tidak ditemukan.');
}

// Mengupdate jumlah yang dibaca
$conn->prepare("UPDATE materi SET jumlah_dibaca = jumlah_dibaca + 1 WHERE materi_id = ?")->execute([$materi_id]);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1><?= htmlspecialchars($materi['judul']) ?></h1>
        <!-- Menampilkan konten lengkap termasuk gambar -->
        <?php
        // Menampilkan gambar jika ada dalam konten
        if (preg_match('/<img.*?src=["\'](.*?)["\']/', $materi['konten'], $matches)) {
            // Pastikan path gambar diupdate
            $imagePath = '../' . htmlspecialchars($matches[1]);
            echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($materi['judul']) . '" style="custom-img">';
        }
        ?>
        <p><strong>Keterangan:</strong> <?= htmlspecialchars($materi['keterangan']) ?></p>
        <p><strong>Jumlah Dibaca:</strong> <?= htmlspecialchars($materi['jumlah_dibaca']) ?></p>
        <p><strong>Ditambahkan pada:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($materi['created_at']))) ?></p>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>