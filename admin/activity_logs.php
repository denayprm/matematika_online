<?php
require_once '../config/init.php';
require_once '../includes/auth_middleware.php';
checkAdminAuth(); // Pastikan hanya admin yang bisa akses

try {
    // Mengambil aktivitas dari database dengan kolom action dan full_name
    $stmt = $conn->query("
        SELECT a.*, u.full_name
        FROM activity_logs a
        JOIN users u ON a.user_id = u.user_id
        ORDER BY a.created_at DESC
    ");
    $activity_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Terjadi kesalahan: ' . $e->getMessage());
}

include '../includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Logs Aktivitas</h1>
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
                                            <th>Aktivitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($activity_logs as $log): ?>
                                            <tr>
                                                <td><?= htmlspecialchars(time_elapsed_string($log['created_at'])) ?></td>
                                                <td><?= htmlspecialchars($log['full_name'] ?? 'Unknown User') ?></td>
                                                <td><?= htmlspecialchars($log['action'] ?? 'No Activity') ?></td>
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