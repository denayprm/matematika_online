<?php
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya admin yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM users";
$result = $conn->query($query);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1>Daftar User</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="user_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>