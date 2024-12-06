<?php
require_once '../includes/auth_middleware.php';
checkGuruAuth(); // Pastikan hanya guru yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $materi_id = $conn->real_escape_string($_POST['materi_id']);
    $judul = $conn->real_escape_string($_POST['judul']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $file = $_FILES['file'];

    $upload_dir = '../uploads/';
    $file_name = basename($file['name']);
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $query = "UPDATE materi
        SET judul = '$judul', deskripsi = '$deskripsi', file = '$file_name'
        WHERE id = $materi_id";

        if ($conn->query($query) === TRUE) {
            $success = "Materi berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui materi: " . $conn->error;
        }
    } else {
        $error = "Gagal mengunggah file.";
    }
}

$materi_id = $conn->real_escape_string($_GET['id']);
$query = "SELECT * FROM materi WHERE id = $materi_id";
$result = $conn->query($query);
$materi = $result->fetch_assoc();

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Materi</div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="materi_id" value="<?php echo $materi['id']; ?>">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul"
                            value="<?php echo $materi['judul']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                            required><?php echo $materi['deskripsi']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Materi</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui Materi</button>
                    <a href="materi_list.php" class="btn btn-link">Kembali ke Daftar Materi</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>