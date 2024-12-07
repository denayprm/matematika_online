<?php
require_once '../config/init.php';
require_once '../includes/auth_middleware.php';
checkAdminAuth(); //

$user_id = isset($_GET['id']) ? $_GET['id'] : null;
$user = null;
$is_edit = false;

if ($user_id) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        set_flash_message('error', 'Pengguna tidak ditemukan');
        redirect('/admin/users.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $full_name = sanitize_input($_POST['full_name']);
    $role = $_POST['role'];
    $password = $_POST['password'] ?? null;

    $errors = [];

    // Validasi username dan email unik
    if (!$is_edit || ($is_edit && $username !== $user['username'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username sudah digunakan";
        }
    }

    if (!$is_edit || ($is_edit && $email !== $user['email'])) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email sudah digunakan";
        }
    }

    if (empty($errors)) {
        try {
            if ($is_edit) {
                $sql = "UPDATE users SET username = ?, email = ?, full_name = ?, role = ?";
                $params = [$username, $email, $full_name, $role];

                if ($password) {
                    $sql .= ", password = ?";
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }

                $sql .= " WHERE user_id = ?";
                $params[] = $user_id;

                $stmt = $conn->prepare($sql);
                $stmt->execute($params);

                set_flash_message('success', 'Pengguna berhasil diperbarui');
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $username,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $full_name,
                    $role
                ]);

                set_flash_message('success', 'Pengguna berhasil ditambahkan');
            }

            redirect('/admin/users.php');
        } catch (PDOException $e) {
            $errors[] = "Terjadi kesalahan dalam memproses data";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= $is_edit ? 'Edit' : 'Tambah' ?> Pengguna</h1>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required
                                value="<?= $user ? htmlspecialchars($user['username']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="<?= $user ? htmlspecialchars($user['email']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required
                                value="<?= $user ? htmlspecialchars($user['full_name']) : '' ?>">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?= ($user && $user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                <option value="guru" <?= ($user && $user['role'] === 'guru') ? 'selected' : '' ?>>Guru</option>
                                <option value="siswa" <?= ($user && $user['role'] === 'siswa') ? 'selected' : '' ?>>Siswa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <?= $is_edit ? 'Password (kosongkan jika tidak ingin mengubah)' : 'Password' ?>
                            </label>
                            <input type="password" class="form-control" id="password" name="password"
                                <?= $is_edit ? '' : 'required' ?>>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/admin/users.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>