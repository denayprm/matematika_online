<?php
require_once '../config/init.php';
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    log_activity($_SESSION['user_id'], 'logout');
}

session_destroy();
redirect('auth/login.php');
