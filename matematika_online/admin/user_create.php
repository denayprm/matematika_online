<?php
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya admin yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $conn->real_escape_string($_POST['role']);

    if ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $check_username = $conn->query("SELECT * FROM users WHERE username = '$username'");
        if ($check_username->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $check_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
            if ($check_email->num_rows > 0) {
                $error = "Email sudah digunakan!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (username, email, password, role)
                VALUES ('$username', '$email', '$hashed_password', '$role')";
                if ($conn->query($query) === TRUE) {
                    $success = "User baru berhasil ditambahkan!";
                } else {
                    $error = "Gagal menambahkan user: " . $conn->error;
                }
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Tambah User Baru</div>
            <div class="card-body">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
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