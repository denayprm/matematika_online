<?php
require_once '../includes/auth_middleware.php';
checkGuruAuth(); // Pastikan hanya guru yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM materi";
$result = $conn->query($query);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1>Daftar Materi</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($materi = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $materi['id']; ?></td>
                    <td><?php echo $materi['judul']; ?></td>
                    <td><?php echo $materi['deskripsi']; ?></td>
                    <td>
                        <a href="materi_edit.php?id=<?php echo $materi['id']; ?>"
                            class="btn btn-primary btn-sm">Edit</a>
                        <a href="materi_delete.php?id=<?php echo $materi['id']; ?>"
                            class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="materi_create.php" class="btn btn-success">Tambah Materi Baru</a>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>