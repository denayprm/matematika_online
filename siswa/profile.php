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
        <h1 align="center">Profil Pengguna</h1>
        <div class="card">
            <div class="card-body text-center">
                <?php
                // Mengatur path gambar default
                $defaultImage = 'https://cdn.pixabay.com/photo/2018/11/13/21/43/avatar-3814049_640.png';
                $imagePath = $defaultImage; // Default image

                // Memeriksa apakah gambar profil ada
                if (isset($user['profile_picture']) && preg_match('/<img.*?src=["\'](.*?)["\']/', $user['profile_picture'], $matches)) {
                    // Memastikan path gambar diupdate
                    $imagePath = '../' . htmlspecialchars($matches[1]);
                }

                // Menampilkan gambar profil
                echo '<img src="' . htmlspecialchars($imagePath) . '" class="card-img-top" alt="Profil Pengguna" style="width: 150px; height: auto;">';
                ?>

                <h3 class="mt-3"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
                <p><strong>Deskripsi:</strong></p>
                <p>
                    <?php
                    // Memeriksa apakah deskripsi ada
                    if (empty($user['description'])) {
                        echo "Pengguna Baru"; // Deskripsi default
                    } else {
                        echo htmlspecialchars($user['description']);
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>