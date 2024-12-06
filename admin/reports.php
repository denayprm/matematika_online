<?php
require_once '../config/init.php';
require_once '../includes/auth_check.php';
check_role('admin');

// Get date range
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// Get user statistics
$stmt = $conn->prepare("
    SELECT 
        COUNT(DISTINCT u.user_id) as total_users,
        COUNT(DISTINCT CASE WHEN u.role = 'siswa' THEN u.user_id END) as total_siswa,
        COUNT(DISTINCT CASE WHEN u.created_at >= ? THEN u.user_id END) as new_users
    FROM users u
    WHERE u.is_active = 1
");
$stmt->execute([$start_date]);
$user_stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get learning statistics
$stmt = $conn->prepare("
    SELECT 
        m.judul as materi_title,
        COUNT(DISTINCT ls.user_id) as total_students,
        AVG(ls.progress) as avg_progress,
        SUM(ls.time_spent) as total_time_spent
    FROM materi m
    LEFT JOIN learning_statistics ls ON m.materi_id = ls.materi_id
    WHERE ls.created_at BETWEEN ? AND ?
    GROUP BY m.materi_id
    ORDER BY total_students DESC
    LIMIT 10
");
$stmt->execute([$start_date, $end_date . ' 23:59:59']);
$learning_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get quiz statistics
$stmt = $conn->prepare("
    SELECT 
        k.judul as kuis_title,
        COUNT(DISTINCT kh.user_id) as total_attempts,
        AVG(kh.nilai) as avg_score,
        COUNT(DISTINCT CASE WHEN kh.nilai >= k.nilai_lulus THEN kh.user_id END) as passed_students
    FROM kuis k
    LEFT JOIN kuis_hasil kh ON k.kuis_id = kh.kuis_id
    WHERE kh.created_at BETWEEN ? AND ?
    GROUP BY k.kuis_id
    ORDER BY total_attempts DESC
    LIMIT 10
");
$stmt->execute([$start_date, $end_date . ' 23:59:59']);
$quiz_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Laporan dan Statistik</h1>

                <form class="row g-3">
                    <div class="col-auto">
                        <input type="date" class="form-control" name="start_date"
                            value="<?= $start_date ?>">
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" name="end_date"
                            value="<?= $end_date ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Overview Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Pengguna</h5>
                            <h2 class="card-text"><?= $user_stats['total_users'] ?></h2>
                            <p class="text-muted">Pengguna baru: <?= $user_stats['new_users'] ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Siswa</h5>
                            <h2 class="card-text"><?= $user_stats['total_siswa'] ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Learning Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Pembelajaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Materi</th>
                                    <th>Total Siswa</th>
                                    <th>Rata-rata Progress</th>
                                    <th>Total Waktu Belajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($learning_stats as $stat): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($stat['materi_title']) ?></td>
                                        <td><?= $stat['total_students'] ?></td>
                                        <td><?= number_format($stat['avg_progress'], 1) ?>%</td>
                                        <td><?= round($stat['total_time_spent'] / 60) ?> menit</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quiz Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Statistik Kuis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kuis</th>
                                    <th>Total Percobaan</th>
                                    <th>Rata-rata Nilai</th>
                                    <th>Siswa Lulus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quiz_stats as $stat): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($stat['kuis_title']) ?></td>
                                        <td><?= $stat['total_attempts'] ?></td>
                                        <td><?= number_format($stat['avg_score'], 1) ?></td>
                                        <td><?= $stat['passed_students'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>