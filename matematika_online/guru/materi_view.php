<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';

$materi_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$materi_id) {
    set_flash_message('error', 'Materi tidak ditemukan');
    redirect('/guru/materi.php');
}

// Get materi detail
$stmt = $conn->prepare("
    SELECT m.*, u.full_name as guru_name 
    FROM materi m 
    JOIN users u ON m.created_by = u.user_id 
    WHERE m.materi_id = ? AND m.is_active = 1
");
$stmt->execute([$materi_id]);
$materi = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$materi) {
    set_flash_message('error', 'Materi tidak ditemukan');
    redirect('/guru/materi.php');
}

// Check access permissions
if ($_SESSION['user']['role'] === 'guru' && $materi['created_by'] !== $_SESSION['user']['user_id']) {
    if ($materi['status'] !== 'published') {
        set_flash_message('error', 'Anda tidak memiliki akses ke materi ini');
        redirect('/guru/materi.php');
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Detail Materi</h1>
                <?php if (
                    $_SESSION['user']['role'] === 'admin' ||
                    ($_SESSION['user']['role'] === 'guru' &&
                        $materi['created_by'] === $_SESSION['user']['user_id'])
                ): ?>
                    <div>
                        <a href="materi_form.php?id=<?= $materi['materi_id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="materi.php" class="btn btn-secondary">Kembali</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0"><?= htmlspecialchars($materi['judul']) ?></h3>
                        <span class="badge bg-<?= $materi['status'] === 'published' ? 'success' : 'warning' ?>">
                            <?= ucfirst($materi['status']) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <strong>Deskripsi:</strong>
                        <p class="mt-2"><?= htmlspecialchars($materi['deskripsi']) ?></p>
                    </div>

                    <div class="mb-4">
                        <strong>Konten:</strong>
                        <div class="mt-2">
                            <?= $materi['konten'] ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Dibuat oleh:</strong> <?= htmlspecialchars($materi['guru_name']) ?></p>
                            <p><strong>Tanggal dibuat:</strong> <?= format_datetime($materi['created_at']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Terakhir diupdate:</strong> <?= format_datetime($materi['updated_at']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>