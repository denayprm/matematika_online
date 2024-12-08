<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include 'config/database.php';
require_once 'includes/header.php';
?>

<div class="jumbotron text-center">
    <img src="assets/img/math_online.png" alt="Matematika Online Siti Maesaroh" class="img-fluid mb-4" style="max-height: 200px;">
    <h1 class="display-4">Selamat Datang di Matematika Online</h1>
    <p class="lead">Platform pembelajaran matematika</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <hr class="my-4">
        <p>Mulai belajar sekarang dengan mendaftar atau masuk ke akun Anda.</p>
        <a class="btn btn-primary btn-lg" href="auth/register.php" role="button">Daftar</a>
        <a class="btn btn-secondary btn-lg" href="auth/login.php" role="button">Masuk</a>
        <p>Akses akun anda untuk melihat lebih detail tentang fitur</p>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card materi-card">
                    <div class="card-body">
                        <h5 class="card-title">Materi Terstruktur</h5>
                        <a class="" href="auth/login.php" role="button">Login untuk akses lebih</a>
                        <p class="card-text">Materi matematika yang disusun secara sistematis.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card materi-card">
                    <div class="card-body">
                        <h5 class="card-title">Sistem Guest</h5>
                        <a class="" href="guest/materi.php" role="button">Lihat Materi Guest</a>
                        <p class="card-text">Pelajari matematika secara gratis tanpa perlu mendaftar.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="container">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <p align="justify">Platform Matematika Online adalah platform pembelajaran matematika yang menyediakan materi pembelajaran matematika secara gratis. Dengan tujuan untuk meningkatkan pemahaman dan keterampilan siswa dalam matematika, platform ini menawarkan berbagai jenis materi yang mencakup berbagai topik, mulai dari aljabar, geometri, hingga statistika.</p>
                <p align="justify">Melalui Platform Matematika Online, diharapkan dapat membantu siswa mengembangkan minat dan kecintaan terhadap matematika, serta mempersiapkan mereka untuk menghadapi tantangan akademis di masa depan. Dengan demikian, platform ini tidak hanya berfungsi sebagai sumber belajar, tetapi juga sebagai komunitas pendukung bagi para pembelajar matematika.</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>