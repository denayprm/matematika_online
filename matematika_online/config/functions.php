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

// Fungsi upload file
function upload_file($file, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'])
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'Upload gagal'];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['error' => 'Ukuran file terlalu besar'];
    }

    $file_info = pathinfo($file['name']);
    $file_ext = strtolower($file_info['extension']);

    if (!in_array($file_ext, $allowed_types)) {
        return ['error' => 'Tipe file tidak diizinkan'];
    }

    $new_filename = uniqid() . '.' . $file_ext;
    $upload_path = UPLOAD_PATH . '/' . $new_filename;

    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        return ['error' => 'Gagal memindahkan file'];
    }

    return ['success' => true, 'filename' => $new_filename];
}
