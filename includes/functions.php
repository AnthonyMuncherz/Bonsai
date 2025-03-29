<?php
// functions.php - Common functions used across the site

/**
 * Record user activity
 * 
 * @param int $user_id User ID
 * @param string $activity_type Type of activity (cart, wishlist, account, etc.)
 * @param string $activity_description Description of the activity
 * @param int|null $related_id Related ID (book_id, etc.) if applicable
 * @return bool Whether the activity was successfully recorded
 */
function record_user_activity($user_id, $activity_type, $activity_description, $related_id = null) {
    $db = get_db_connection();
    
    // Set pragmas to help prevent database locking issues
    $db->exec('PRAGMA journal_mode = WAL;');
    $db->exec('PRAGMA busy_timeout = 5000;');
    
    $query = $db->prepare("
        INSERT INTO user_activities (user_id, activity_type, activity_description, related_id)
        VALUES (:user_id, :activity_type, :activity_description, :related_id)
    ");
    
    $query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $query->bindValue(':activity_type', $activity_type, SQLITE3_TEXT);
    $query->bindValue(':activity_description', $activity_description, SQLITE3_TEXT);
    $query->bindValue(':related_id', $related_id, $related_id === null ? SQLITE3_NULL : SQLITE3_INTEGER);
    
    // Try to execute with retries for database locks
    $max_retries = 5;
    $retry_count = 0;
    $success = false;
    
    while ($retry_count < $max_retries && !$success) {
        try {
            $success = $query->execute() ? true : false;
            if ($success) {
                break;
            }
        } catch (Exception $e) {
            // If database is locked, wait and retry
            if (strpos($e->getMessage(), 'database is locked') !== false) {
                $retry_count++;
                if ($retry_count < $max_retries) {
                    // Wait with increasing backoff time
                    usleep(($retry_count * 100000)); // 100ms, 200ms, 300ms, etc.
                    continue;
                }
            }
            // If it's not a locking issue or we've exceeded retries, log it
            error_log("Activity logging error: " . $e->getMessage());
            return false;
        }
        $retry_count++;
    }
    
    return $success;
}

/**
 * Get recent user activities
 * 
 * @param int $user_id User ID
 * @param int $limit Number of activities to return
 * @return array Array of recent activities
 */
function get_recent_user_activities($user_id, $limit = 5) {
    $db = get_db_connection();
    
    $query = $db->prepare("
        SELECT * FROM user_activities 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC 
        LIMIT :limit
    ");
    
    $query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $query->bindValue(':limit', $limit, SQLITE3_INTEGER);
    
    $result = $query->execute();
    
    $activities = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $activities[] = $row;
    }
    
    return $activities;
} 