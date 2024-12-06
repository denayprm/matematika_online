<?php
require_once '../includes/auth_middleware.php';
checkGuruAuth(); // Pastikan hanya guru yang bisa akses

require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

include '../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <h1>Dashboard Guru</h1>
        <div class="card">
            <div class="card-body">
                <h5>Selamat datang, <?php echo $_SESSION['username']; ?></h5>
                <p>Anda login sebagai Guru.</p>

                <!-- Tambahkan menu dan fitur guru di sini -->
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$db->closeConnection();
?>