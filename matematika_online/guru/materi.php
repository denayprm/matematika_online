<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';
check_role(['admin', 'guru']);

// Handle delete materi
if (isset($_POST['delete_materi'])) {
    $materi_id = $_POST['materi_id'];
    try {
        $stmt = $conn->prepare("UPDATE materi SET is_active = 0 WHERE materi_id = ?");
        $stmt->execute([$materi_id]);
        set_flash_message('success', 'Materi berhasil dihapus');
    } catch (PDOException $e) {
        set_flash_message('error', 'Gagal menghapus materi');
    }
}

// Pagination dan pencarian
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_conditions = ['is_active = 1'];
$params = [];

if ($search) {
    $where_conditions[] = "(judul LIKE ? OR deskripsi LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param]);
}

// Jika role guru, hanya tampilkan materi miliknya
if ($_SESSION['user']['role'] === 'guru') {
    $where_conditions[] = "created_by = ?";
    $params[] = $_SESSION['user']['user_id'];
}

$where_clause = implode(' AND ', $where_conditions);

// Get total materi
$stmt = $conn->prepare("SELECT COUNT(*) FROM materi WHERE $where_clause");
$stmt->execute($params);
$total_materi = $stmt->fetchColumn();
$total_pages = ceil($total_materi / $limit);

// Get materi list
$stmt = $conn->prepare("
    SELECT m.*, u.full_name as guru_name 
    FROM materi m 
    JOIN users u ON m.created_by = u.user_id 
    WHERE $where_clause 
    ORDER BY m.created_at DESC 
    LIMIT ? OFFSET ?
");
$params[] = $limit;
$params[] = $offset;
$stmt->execute($params);
$materis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manajemen Materi</h1>
                <a href="materi_form.php" class="btn btn-primary">Tambah Materi</a>
            </div>

            <?php if ($flash = get_flash_message()): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="search" placeholder="Cari materi..."
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Guru</th>
                                    <th>Tanggal Upload</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materis as $materi): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($materi['judul']) ?></td>
                                        <td><?= htmlspecialchars($materi['guru_name']) ?></td>
                                        <td><?= format_datetime($materi['created_at']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $materi['status'] === 'published' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($materi['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="materi_view.php?id=<?= $materi['materi_id'] ?>"
                                                class="btn btn-sm btn-info">Lihat</a>
                                            <a href="materi_form.php?id=<?= $materi['materi_id'] ?>"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                                <input type="hidden" name="materi_id" value="<?= $materi['materi_id'] ?>">
                                                <button type="submit" name="delete_materi"
                                                    class="btn btn-sm btn-danger">Hapus</button>
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
                                        <a class="page-link"
                                            href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
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