<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';
check_role(['admin', 'guru']);

// Handle delete kuis
if (isset($_POST['delete_kuis'])) {
    $kuis_id = $_POST['kuis_id'];
    try {
        $stmt = $conn->prepare("UPDATE kuis SET is_active = 0 WHERE kuis_id = ?");
        $stmt->execute([$kuis_id]);
        set_flash_message('success', 'Kuis berhasil dihapus');
    } catch (PDOException $e) {
        set_flash_message('error', 'Gagal menghapus kuis');
    }
}

// Pagination dan pencarian
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_conditions = ['k.is_active = 1'];
$params = [];

if ($search) {
    $where_conditions[] = "(k.judul LIKE ? OR k.deskripsi LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param]);
}

if ($_SESSION['user']['role'] === 'guru') {
    $where_conditions[] = "k.created_by = ?";
    $params[] = $_SESSION['user']['user_id'];
}

$where_clause = implode(' AND ', $where_conditions);

// Get total kuis
$stmt = $conn->prepare("SELECT COUNT(*) FROM kuis k WHERE $where_clause");
$stmt->execute($params);
$total_kuis = $stmt->fetchColumn();
$total_pages = ceil($total_kuis / $limit);

// Get kuis list
$stmt = $conn->prepare("
    SELECT k.*, u.full_name as guru_name,
           (SELECT COUNT(*) FROM kuis_soal WHERE kuis_id = k.kuis_id) as total_soal
    FROM kuis k 
    JOIN users u ON k.created_by = u.user_id 
    WHERE $where_clause 
    ORDER BY k.created_at DESC 
    LIMIT ? OFFSET ?
");
$params[] = $limit;
$params[] = $offset;
$stmt->execute($params);
$kuises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manajemen Kuis</h1>
                <a href="kuis_form.php" class="btn btn-primary">Tambah Kuis</a>
            </div>

            <?php if ($flash = get_flash_message()): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="search" placeholder="Cari kuis..."
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
                                    <th>Jumlah Soal</th>
                                    <th>Durasi (menit)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($kuises as $kuis): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($kuis['judul']) ?></td>
                                        <td><?= htmlspecialchars($kuis['guru_name']) ?></td>
                                        <td><?= $kuis['total_soal'] ?></td>
                                        <td><?= $kuis['durasi'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $kuis['status'] === 'published' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($kuis['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="kuis_soal.php?id=<?= $kuis['kuis_id'] ?>"
                                                class="btn btn-sm btn-info">Kelola Soal</a>
                                            <a href="kuis_hasil.php?id=<?= $kuis['kuis_id'] ?>"
                                                class="btn btn-sm btn-success">Hasil</a>
                                            <a href="kuis_form.php?id=<?= $kuis['kuis_id'] ?>"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus kuis ini?')">
                                                <input type="hidden" name="kuis_id" value="<?= $kuis['kuis_id'] ?>">
                                                <button type="submit" name="delete_kuis"
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