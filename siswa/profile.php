<?php
require_once '../includes/auth_middleware.php';
checkSiswaAuth(); // Pastikan hanya pengguna yang terautentikasi yang bisa mengakses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Mengambil informasi pengguna dari tabel users
$user_id = $_SESSION['user_id']; // Menggunakan ID pengguna dari session
$stmt = $conn->prepare("SELECT full_name, email, role, profile_picture, description FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Pengguna tidak ditemukan.');
}

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1>Profil Pengguna</h1>
        <div class="card">
            <div class="card-body text-center">
                <?php
                // Cek apakah ada gambar di dalam konten
                if (preg_match('/<img.*?src=["\'](.*?)["\']/', $user['profile_picture'], $matches)) {
                    // Pastikan path gambar diupdate
                    $imagePath = '../' . htmlspecialchars($matches[1]);
                    echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($user['profile_picture']) . '" style="custom-img">';
                }
                ?>
                <h3 class="mt-3"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
                <p><strong>Deskripsi:</strong></p>
                <p><?= htmlspecialchars($user['description']) ?></p>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>