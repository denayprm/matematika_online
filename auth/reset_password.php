<?php
require_once '../config/init.php';
require_once '../config/database.php';

if (!isset($_GET['token'])) {
    redirect('auth/login.php');
}

$token = $_GET['token'];
$stmt = $conn->prepare("
    SELECT r.*, u.email
    FROM reset_password r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.token = ? AND r.expires_at > NOW()
    ORDER BY r.created_at DESC
    LIMIT 1
");
$stmt->execute([$token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    set_flash_message('error', 'Token tidak valid atau sudah kadaluarsa');
    redirect('auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn->beginTransaction();

            // Update password
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->execute([$hashed_password, $reset['user_id']]);

            // Delete used token
            $stmt = $conn->prepare("DELETE FROM reset_password WHERE token = ?");
            $stmt->execute([$token]);

            $conn->commit();

            set_flash_message('success', 'Password berhasil diubah');
            redirect('auth/login.php');
        } catch (PDOException $e) {
            $conn->rollBack();
            set_flash_message('error', 'Terjadi kesalahan dalam mengubah password');
        }
    } else {
        set_flash_message('error', 'Password tidak cocok');
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Pembelajaran Matematika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Reset Password</h3>

                        <?php if ($flash = get_flash_message()): ?>
                            <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>