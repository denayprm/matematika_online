<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';
check_role('admin');

// Handle delete user
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    try {
        $stmt = $conn->prepare("UPDATE users SET is_active = 0 WHERE user_id = ?");
        $stmt->execute([$user_id]);
        set_flash_message('success', 'Pengguna berhasil dinonaktifkan');
    } catch (PDOException $e) {
        set_flash_message('error', 'Gagal menonaktifkan pengguna');
    }
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

$where_conditions = ['is_active = 1'];
$params = [];

if ($search) {
    $where_conditions[] = "(username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
}

if ($role_filter) {
    $where_conditions[] = "role = ?";
    $params[] = $role_filter;
}

$where_clause = implode(' AND ', $where_conditions);

// Get total users for pagination
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE $where_clause");
$stmt->execute($params);
$total_users = $stmt->fetchColumn();
$total_pages = ceil($total_users / $limit);

// Get users
$stmt = $conn->prepare("
    SELECT * FROM users 
    WHERE $where_clause 
    ORDER BY created_at DESC 
    LIMIT ? OFFSET ?
");
$params[] = $limit;
$params[] = $offset;
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manajemen Pengguna</h1>
                <a href="user_form.php" class="btn btn-primary">Tambah Pengguna</a>
            </div>

            <?php if ($flash = get_flash_message()): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="role" class="form-select">
                                <option value="">Semua Role</option>
                                <option value="admin" <?= $role_filter === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="guru" <?= $role_filter === 'guru' ? 'selected' : '' ?>>Guru</option>
                                <option value="siswa" <?= $role_filter === 'siswa' ? 'selected' : '' ?>>Siswa</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Terakhir Login</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= ucfirst($user['role']) ?></td>
                                        <td><?= $user['last_login'] ? format_datetime($user['last_login']) : '-' ?></td>
                                        <td>
                                            <a href="user_form.php?id=<?= $user['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menonaktifkan pengguna ini?')">
                                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Nonaktifkan</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role_filter) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>