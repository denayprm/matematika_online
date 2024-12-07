<?php
require_once '../config/init.php';
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya admin yang bisa akses

// Fungsi untuk memformat tanggal
function format_datetime($datetime)
{
    return date('d-m-Y H:i:s', strtotime($datetime));
}

try {
    // Mengambil statistik untuk dashboard
    $stats = [
        'total_users' => $conn->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'total_siswa' => $conn->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn(),
        'total_materi' => $conn->query("SELECT COUNT(*) FROM materi")->fetchColumn(),
    ];

    // Mengambil aktivitas terbaru
    $stmt = $conn->query("
        SELECT a.*, u.username, u.role
        FROM activity_logs a
        JOIN users u ON a.user_id = u.user_id
        ORDER BY a.created_at DESC
        LIMIT 10
    ");
    $recent_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Terjadi kesalahan: ' . $e->getMessage());
}

?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard Admin</h1>
            </div>

            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Pengguna</h5>
                            <h2 class="card-text"><?= htmlspecialchars($stats['total_users']) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Siswa</h5>
                            <h2 class="card-text"><?= htmlspecialchars($stats['total_siswa']) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Materi</h5>
                            <h2 class="card-text"><?= htmlspecialchars($stats['total_materi']) ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Pengguna</th>
                                            <th>Role</th>
                                            <th>Aktivitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_activities as $activity): ?>
                                            <tr>
                                                <td><?= htmlspecialchars(format_datetime($activity['created_at'])) ?></td>
                                                <td><?= htmlspecialchars($activity['username'] ?? 'Unknown User') ?></td>
                                                <td><?= htmlspecialchars($activity['role'] ?? 'Unknown Role') ?></td>
                                                <td><?= htmlspecialchars($activity['action'] ?? 'No Activity') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>