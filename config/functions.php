<?php
// Fungsi keamanan
function check_login()
{
    if (!isset($_SESSION['user_id'])) {
        redirect('/login.php');
    }
}

function check_role($allowed_roles)
{
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        redirect('/unauthorized.php');
    }
}

// Fungsi log aktivitas
function log_activity($user_id, $action)
{
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, action, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $action, $ip, $user_agent]);
}

// Fungsi redirect
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit();
}
