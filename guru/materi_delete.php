<?php
require_once '../includes/auth_middleware.php';
checkGuruAuth(); // Pastikan hanya guru yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $materi_id = $conn->real_escape_string($_GET['id']);

    $query = "SELECT * FROM materi WHERE id = $materi_id";
    $result = $conn->query($query);
    $materi = $result->fetch_assoc();

    if (!$materi) {
        $error = "Materi tidak ditemukan!";
    } else {
        $query = "DELETE FROM materi WHERE id = $materi_id";
        if ($conn->query($query) === TRUE) {
            $success = "Materi berhasil dihapus!";
        } else {
            $error = "Gagal menghapus materi: " . $conn->error;
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Hapus Materi</div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if (isset($materi)): ?>
                    <p>Apakah Anda yakin ingin menghapus materi <strong><?php echo $materi['judul']; ?></strong>?</p>
                    <form method="POST" action="">
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        <a href="materi_list.php" class="btn btn-link">Batal</a>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>