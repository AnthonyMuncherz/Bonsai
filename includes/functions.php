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
    // Use file-based logging to avoid database locks
    try {
        // Create a log entry in JSON format
        $log_entry = [
            'user_id' => $user_id,
            'activity_type' => $activity_type,
            'activity_description' => $activity_description,
            'related_id' => $related_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Create logs directory if it doesn't exist
        $log_dir = __DIR__ . '/../logs';
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        // Generate a unique filename
        $filename = $log_dir . '/activity_' . date('Ymd_His') . '_' . uniqid() . '.json';
        
        // Write to file
        file_put_contents($filename, json_encode($log_entry));
        
        // Schedule processing of activity logs (will be processed later)
        touch($log_dir . '/needs_processing');
        
        return true;
    } catch (Exception $e) {
        // Log the error
        error_log("Activity logging error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get recent user activities with proper error handling
 * 
 * @param int $user_id User ID
 * @param int $limit Number of activities to return
 * @return array Array of recent activities
 */
function get_recent_user_activities($user_id, $limit = 5) {
    try {
        // Try to process any pending activity logs first
        process_activity_logs();
        
        // Get database connection
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
        
        // Close database connection
        $db->close();
        
        return $activities;
    } catch (Exception $e) {
        error_log("Error getting user activities: " . $e->getMessage());
        return [];
    }
}

/**
 * Close database connections to free up resources
 * 
 * @param SQLite3 $db Database connection to close
 * @return void
 */
function close_db_connection($db = null) {
    if ($db instanceof SQLite3) {
        $db->close();
    }
}

/**
 * Process activity logs from files to database
 * 
 * This function should be called from a separate process/cron
 * to avoid database locks during user operations
 */
function process_activity_logs() {
    $log_dir = __DIR__ . '/../logs';
    $flag_file = $log_dir . '/needs_processing';
    
    // Check if processing is needed
    if (!file_exists($flag_file)) {
        return;
    }
    
    // Get all JSON log files
    $log_files = glob($log_dir . '/activity_*.json');
    if (empty($log_files)) {
        unlink($flag_file);
        return;
    }
    
    try {
        // Get database connection
        global $db_path;
        $db = new SQLite3($db_path);
        
        // Configure SQLite for batch processing
        $db->exec('PRAGMA synchronous = OFF');
        $db->exec('PRAGMA journal_mode = MEMORY');
        $db->exec('BEGIN TRANSACTION');
        
        // Prepare statement for inserting activities
        $stmt = $db->prepare("
            INSERT INTO user_activities 
            (user_id, activity_type, activity_description, related_id, created_at)
            VALUES 
            (:user_id, :activity_type, :activity_description, :related_id, :created_at)
        ");
        
        $processed = 0;
        
        // Process each log file
        foreach ($log_files as $file) {
            // Skip if file is being written or doesn't exist
            if (!file_exists($file) || !is_readable($file)) {
                continue;
            }
            
            // Read log entry
            $content = file_get_contents($file);
            $log_entry = json_decode($content, true);
            
            if ($log_entry) {
                // Bind parameters
                $stmt->bindValue(':user_id', $log_entry['user_id'], SQLITE3_INTEGER);
                $stmt->bindValue(':activity_type', $log_entry['activity_type'], SQLITE3_TEXT);
                $stmt->bindValue(':activity_description', $log_entry['activity_description'], SQLITE3_TEXT);
                $stmt->bindValue(':related_id', $log_entry['related_id'], 
                    $log_entry['related_id'] === null ? SQLITE3_NULL : SQLITE3_INTEGER);
                $stmt->bindValue(':created_at', $log_entry['created_at'], SQLITE3_TEXT);
                
                // Execute and track result
                $result = $stmt->execute();
                
                if ($result) {
                    // Delete processed file
                    unlink($file);
                    $processed++;
                }
            } else {
                // Invalid JSON - delete the file
                unlink($file);
            }
        }
        
        // Commit the transaction
        $db->exec('COMMIT');
        $db->close();
        
        // Remove flag file if all processed
        if (empty(glob($log_dir . '/activity_*.json'))) {
            unlink($flag_file);
        }
        
        return $processed;
    } catch (Exception $e) {
        error_log("Error processing activity logs: " . $e->getMessage());
        
        // Attempt to roll back if possible
        if (isset($db) && $db instanceof SQLite3) {
            $db->exec('ROLLBACK');
            $db->close();
        }
        
        return false;
    }
} 