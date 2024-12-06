<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include 'config/database.php';
require_once 'includes/header.php';
?>

<div class="jumbotron text-center">
    <h1 class="display-4">Selamat Datang di Matematika Online</h1>
    <p class="lead">Platform pembelajaran matematika interaktif untuk semua tingkatan</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <hr class="my-4">
        <p>Mulai belajar sekarang dengan mendaftar atau masuk ke akun Anda.</p>
        <a class="btn btn-primary btn-lg" href="auth/register.php" role="button">Daftar</a>
        <a class="btn btn-secondary btn-lg" href="auth/login.php" role="button">Masuk</a>
    <?php endif; ?>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Materi Terstruktur</h5>
                <p class="card-text">Pelajari matematika dengan materi yang disusun secara sistematis.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Latihan Interaktif</h5>
                <p class="card-text">Uji pemahaman Anda dengan kuis dan latihan soal interaktif.</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>