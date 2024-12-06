<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';
check_role(['admin', 'guru']);

$materi_id = isset($_GET['id']) ? $_GET['id'] : null;
$materi = null;
$is_edit = false;

if ($materi_id) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM materi WHERE materi_id = ?");
    $stmt->execute([$materi_id]);
    $materi = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$materi) {
        set_flash_message('error', 'Materi tidak ditemukan');
        redirect('/guru/materi.php');
    }

    // Check if user has permission to edit
    if ($_SESSION['user']['role'] === 'guru' && $materi['created_by'] !== $_SESSION['user']['user_id']) {
        set_flash_message('error', 'Anda tidak memiliki akses untuk mengedit materi ini');
        redirect('/guru/materi.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = sanitize_input($_POST['judul']);
    $deskripsi = sanitize_input($_POST['deskripsi']);
    $konten = $_POST['konten'];
    $status = $_POST['status'];

    try {
        if ($is_edit) {
            $stmt = $conn->prepare("
                UPDATE materi 
                SET judul = ?, deskripsi = ?, konten = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE materi_id = ?
            ");
            $stmt->execute([$judul, $deskripsi, $konten, $status, $materi_id]);
            set_flash_message('success', 'Materi berhasil diperbarui');
        } else {
            $stmt = $conn->prepare("
                INSERT INTO materi (judul, deskripsi, konten, status, created_by) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$judul, $deskripsi, $konten, $status, $_SESSION['user']['user_id']]);
            set_flash_message('success', 'Materi berhasil ditambahkan');
        }

        redirect('/guru/materi.php');
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
                <h1 class="h2"><?= $is_edit ? 'Edit' : 'Tambah' ?> Materi</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Materi</label>
                            <input type="text" class="form-control" id="judul" name="judul" required
                                value="<?= $materi ? htmlspecialchars($materi['judul']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?=
                                                                                                                $materi ? htmlspecialchars($materi['deskripsi']) : ''
                                                                                                                ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="konten" class="form-label">Konten Materi</label>
                            <textarea class="form-control" id="konten" name="konten" rows="10" required><?=
                                                                                                        $materi ? htmlspecialchars($materi['konten']) : ''
                                                                                                        ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft" <?= ($materi && $materi['status'] === 'draft') ? 'selected' : '' ?>>
                                    Draft
                                </option>
                                <option value="published" <?= ($materi && $materi['status'] === 'published') ? 'selected' : '' ?>>
                                    Published
                                </option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/guru/materi.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Include TinyMCE -->
<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#konten',
        height: 500,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent | removeformat | help'
    });
</script>

<?php include '../includes/footer.php'; ?>