<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';

// Pagination dan pencarian
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$where_conditions = ['t.is_active = 1'];
$params = [];

if ($search) {
    $where_conditions[] = "(t.judul LIKE ? OR t.konten LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param]);
}

if ($kategori) {
    $where_conditions[] = "t.kategori = ?";
    $params[] = $kategori;
}

$where_clause = implode(' AND ', $where_conditions);

// Get total topik
$stmt = $conn->prepare("SELECT COUNT(*) FROM forum_topik t WHERE $where_clause");
$stmt->execute($params);
$total_topik = $stmt->fetchColumn();
$total_pages = ceil($total_topik / $limit);

// Get topik list
$stmt = $conn->prepare("
    SELECT t.*, u.full_name as author_name,
    (SELECT COUNT(*) FROM forum_komentar WHERE topik_id = t.topik_id AND is_active = 1) as total_komentar
    FROM forum_topik t
    JOIN users u ON t.user_id = u.user_id
    WHERE $where_clause
    ORDER BY t.is_sticky DESC, t.created_at DESC
    LIMIT ? OFFSET ?
");
$params[] = $limit;
$params[] = $offset;
$stmt->execute($params);
$topiks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Forum Diskusi</h1>
                <a href="topik_form.php" class="btn btn-primary">Buat Topik Baru</a>
            </div>

            <?php if ($flash = get_flash_message()): ?>
                <div class="alert alert-<?= $flash['type'] ?>"><?= $flash['message'] ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="search" placeholder="Cari topik..."
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="kategori">
                                <option value="">Semua Kategori</option>
                                <option value="umum" <?= $kategori === 'umum' ? 'selected' : '' ?>>Umum</option>
                                <option value="pelajaran" <?= $kategori === 'pelajaran' ? 'selected' : '' ?>>Pelajaran</option>
                                <option value="pengumuman" <?= $kategori === 'pengumuman' ? 'selected' : '' ?>>Pengumuman</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </form>

                    <div class="list-group">
                        <?php foreach ($topiks as $topik): ?>
                            <a href="topik.php?id=<?= $topik['topik_id'] ?>"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">
                                        <?php if ($topik['is_sticky']): ?>
                                            <i class="fas fa-thumbtack text-warning"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($topik['judul']) ?>
                                    </h5>
                                    <small class="text-muted">
                                        <?= time_elapsed_string($topik['created_at']) ?>
                                    </small>
                                </div>
                                <p class="mb-1"><?= substr(strip_tags($topik['konten']), 0, 200) ?>...</p>
                                <small class="text-muted">
                                    Oleh: <?= htmlspecialchars($topik['author_name']) ?> |
                                    Kategori: <?= ucfirst($topik['kategori']) ?> |
                                    <?= $topik['total_komentar'] ?> komentar |
                                    <?= $topik['view_count'] ?> dilihat
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&kategori=<?= urlencode($kategori) ?>">
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