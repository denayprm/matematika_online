<?php
require_once '../config/init.php';
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

// Mencatat aktivitas membaca materi
log_activity($_SESSION['user_id'], 'Membaca materi: ' . $materi['judul']);

if (!$materi) {
    die('Materi tidak ditemukan.');
}

// Mengupdate jumlah yang dibaca
$conn->prepare("UPDATE materi SET jumlah_dibaca = jumlah_dibaca + 1 WHERE materi_id = ?")->execute([$materi_id]);

// Mengambil daftar materi lainnya
$other_stmt = $conn->query("SELECT materi_id, judul FROM materi WHERE materi_id != $materi_id AND is_active = 1");
$other_materi_list = $other_stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <h1><?= htmlspecialchars($materi['judul']) ?></h1>
        <!-- Menampilkan konten lengkap termasuk gambar -->
        <?php
        // Cek apakah ada gambar di dalam konten
        if (preg_match('/<img.*?src=["\'](.*?)["\']/', $materi['konten'], $matches)) {
            // Pastikan path gambar diupdate
            $imagePath = '../' . htmlspecialchars($matches[1]);
            echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($materi['judul']) . '" style="custom-img">';
        }
        ?>
        <p><strong>Keterangan:</strong> <?= htmlspecialchars($materi['keterangan']) ?></p>
        <p><strong>Jumlah Dibaca:</strong> <?= htmlspecialchars($materi['jumlah_dibaca']) ?></p>
        <p><strong>Ditambahkan pada:</strong> <?= htmlspecialchars(date('d-m-Y', strtotime($materi['created_at']))), " : " ?> <?= htmlspecialchars(time_elapsed_string($materi['created_at'])) ?></p>
    </div>

    <div class="col-md-4">
        <h3>Materi Lainnya</h3>
        <ul class="list-group">
            <?php foreach ($other_materi_list as $other_materi): ?>
                <li class="list-group-item">
                    <a href="materi_detail.php?id=<?= $other_materi['materi_id'] ?>">
                        <?= htmlspecialchars($other_materi['judul']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>