<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';
check_role(['admin', 'guru']);

$kuis_id = isset($_GET['id']) ? $_GET['id'] : null;
$kuis = null;
$is_edit = false;

if ($kuis_id) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM kuis WHERE kuis_id = ?");
    $stmt->execute([$kuis_id]);
    $kuis = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kuis) {
        set_flash_message('error', 'Kuis tidak ditemukan');
        redirect('/guru/kuis.php');
    }

    if ($_SESSION['user']['role'] === 'guru' && $kuis['created_by'] !== $_SESSION['user']['user_id']) {
        set_flash_message('error', 'Anda tidak memiliki akses untuk mengedit kuis ini');
        redirect('/guru/kuis.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = sanitize_input($_POST['judul']);
    $deskripsi = sanitize_input($_POST['deskripsi']);
    $durasi = (int)$_POST['durasi'];
    $nilai_lulus = (int)$_POST['nilai_lulus'];
    $status = $_POST['status'];

    try {
        if ($is_edit) {
            $stmt = $conn->prepare("
                UPDATE kuis 
                SET judul = ?, deskripsi = ?, durasi = ?, nilai_lulus = ?, status = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE kuis_id = ?
            ");
            $stmt->execute([$judul, $deskripsi, $durasi, $nilai_lulus, $status, $kuis_id]);
            set_flash_message('success', 'Kuis berhasil diperbarui');
        } else {
            $stmt = $conn->prepare("
                INSERT INTO kuis (judul, deskripsi, durasi, nilai_lulus, status, created_by) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$judul, $deskripsi, $durasi, $nilai_lulus, $status, $_SESSION['user']['user_id']]);
            $kuis_id = $conn->lastInsertId();
            set_flash_message('success', 'Kuis berhasil ditambahkan');
        }

        redirect('/guru/kuis_soal.php?id=' . $kuis_id);
    } catch (PDOException $e) {
        set_flash_message('error', 'Terjadi kesalahan dalam memproses data');
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= $is_edit ? 'Edit' : 'Tambah' ?> Kuis</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Kuis</label>
                            <input type="text" class="form-control" id="judul" name="judul" required
                                value="<?= $kuis ? htmlspecialchars($kuis['judul']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?=
                                                                                                                $kuis ? htmlspecialchars($kuis['deskripsi']) : ''
                                                                                                                ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (menit)</label>
                            <input type="number" class="form-control" id="durasi" name="durasi" required min="1"
                                value="<?= $kuis ? $kuis['durasi'] : '30' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="nilai_lulus" class="form-label">Nilai Minimum Kelulusan</label>
                            <input type="number" class="form-control" id="nilai_lulus" name="nilai_lulus"
                                required min="0" max="100"
                                value="<?= $kuis ? $kuis['nilai_lulus'] : '70' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft" <?= ($kuis && $kuis['status'] === 'draft') ? 'selected' : '' ?>>
                                    Draft
                                </option>
                                <option value="published" <?= ($kuis && $kuis['status'] === 'published') ? 'selected' : '' ?>>
                                    Published
                                </option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/guru/kuis.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">
                                <?= $is_edit ? 'Update' : 'Buat' ?> Kuis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>