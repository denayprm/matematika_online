<?php
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya admin yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $user_id = $conn->real_escape_string($_GET['id']);

    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();

    if (!$user) {
        $error = "User tidak ditemukan!";
    } else {
        $query = "DELETE FROM users WHERE id = $user_id";
        if ($conn->query($query) === TRUE) {
            $success = "User berhasil dihapus!";
        } else {
            $error = "Gagal menghapus user: " . $conn->error;
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Hapus User</div>
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if (isset($user)): ?>
                <p>Apakah Anda yakin ingin menghapus user <strong><?php echo $user['username']; ?></strong>?</p>
                <form method="POST" action="">
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    <a href="user_list.php" class="btn btn-link">Batal</a>
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