<?php
function log_activity($user_id, $activity_type, $description)
{
    global $conn;

    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    try {
        $stmt = $conn->prepare("
            INSERT INTO activity_logs (user_id, activity_type, description, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $activity_type, $description, $ip_address, $user_agent]);
        return true;
    } catch (PDOException $e) {
        error_log("Error logging activity: " . $e->getMessage());
        return false;
    }
}
