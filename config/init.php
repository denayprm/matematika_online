<?php
require_once 'database.php';
require_once 'constants.php';

if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Create a new database connection
$db = new Database();
$conn = $db->getConnection(); // Store the connection in a global variable

// Fungsi untuk sanitasi input
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk redirect
function redirect($path)
{
    header("Location: " . BASE_URL . $path);
    exit();
}

// Fungsi untuk flash messages
function set_flash_message($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Fungsi untuk mencatat aktivitas pengguna
function log_activity($user_id, $action)
{
    global $conn; // Gunakan koneksi global

    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->execute([$user_id, $action]);
}

function get_flash_message()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Custom error handler
function custom_error_handler($errno, $errstr, $errfile, $errline)
{
    $message = date('[Y-m-d H:i:s]') . " Error ($errno): $errstr in $errfile on line $errline\n";
    error_log($message);

    if (ENVIRONMENT === 'development') {
        echo "<div style='color:red;'><pre>$message</pre></div>";
    }

    return true;
}
set_error_handler('custom_error_handler');

// Custom exception handler
function custom_exception_handler($exception)
{
    $message = date('[Y-m-d H:i:s]') . " Exception: " . $exception->getMessage() .
        " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
    error_log($message);

    if (ENVIRONMENT === 'development') {
        echo "<div style='color:red;'><pre>$message</pre></div>";
    } else {
        // Redirect to error page in production
        header('Location: /error.php');
    }
}
set_exception_handler('custom_exception_handler');

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}
