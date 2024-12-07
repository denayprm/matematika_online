<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function checkAuth($allowed_roles = [])
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
        // Redirect ke halaman tidak diizinkan
        header("Location: ../unauthorized.php");
        exit();
    }
}

// Admin dashboard
function checkAdminAuth()
{
    checkAuth(['admin']);
}

// Siswa dashboard
function checkSiswaAuth()
{
    checkAuth(['siswa']);
}
