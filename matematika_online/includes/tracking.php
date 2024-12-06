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

function update_learning_progress($user_id, $materi_id, $time_spent, $progress)
{
    global $conn;

    try {
        $stmt = $conn->prepare("
            INSERT INTO learning_statistics (user_id, materi_id, time_spent, progress, last_accessed)
            VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE
            time_spent = time_spent + VALUES(time_spent),
            progress = VALUES(progress),
            last_accessed = CURRENT_TIMESTAMP
        ");
        $stmt->execute([$user_id, $materi_id, $time_spent, $progress]);
        return true;
    } catch (PDOException $e) {
        error_log("Error updating learning progress: " . $e->getMessage());
        return false;
    }
}
