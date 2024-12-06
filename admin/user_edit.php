<?php
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya admin yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);

    $check_username = $conn->query("SELECT * FROM users WHERE username = '$username' AND id != $user_id");
    if ($check_username->num_rows > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $check_email = $conn->query("SELECT * FROM users WHERE email = '$email' AND id != $user_id");
        if ($check_email->num_rows > 0) {
            $error = "Email sudah digunakan!";
        } else {
            $query = "UPDATE users
            SET username = '$username', email = '$email', role = '$role'
            WHERE id = $user_id";

            if ($conn->query($query) === TRUE) {
                $success = "User berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui user: " . $conn->error;
            }
        }
    }
}

$user_id = $conn->real_escape_string($_GET['id']);
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit User</div>
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="siswa" <?php if ($user['role'] == 'siswa') echo 'selected'; ?>>Siswa</option>
                            <option value="guru" <?php if ($user['role'] == 'guru') echo 'selected'; ?>>Guru</option>
                            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui User</button>
                    <a href="user_list.php" class="btn btn-link">Kembali ke Daftar User</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>