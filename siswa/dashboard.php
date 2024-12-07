<?php
require_once '../includes/auth_middleware.php';
checkSiswaAuth(); // Pastikan hanya siswa yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

include '../includes/header.php';

// Mengambil daftar bacaan dari tabel materi
$stmt = $conn->query("SELECT * FROM materi WHERE is_active = 1");
$materi_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-12">
        <h1>Dashboard Siswa</h1>
        <div class="card">
            <div class="card-body">
                <h5>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h5>
                <h6>Daftar Bacaan</h6>
                <div class="row">
                    <?php foreach ($materi_list as $materi): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($materi['judul']) ?></h5>
                                    <?php
                                    // Menampilkan gambar jika ada dalam konten
                                    if (preg_match('/<img.*?src=["\'](.*?)["\']/', $materi['konten'], $matches)) {
                                        echo '<img src="' . htmlspecialchars($matches[1]) . '" class="card-img-top" alt="' . htmlspecialchars($materi['judul']) . '" style="max-width:100%; height:auto;">';
                                    }
                                    ?>
                                    <p class="card-text"><?= htmlspecialchars($materi['deskripsi']) ?></p>
                                    <a href="materi_detail.php?id=<?= $materi['materi_id'] ?>" class="btn btn-primary">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>