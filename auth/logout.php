<?php
require_once '../config/init.php';
require_once '../config/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/matematika_online/');
}

if (isset($_SESSION['user_id'])) {
    log_activity($_SESSION['user_id'], 'logout');
}

session_destroy();
redirect('index.php');
