<?php
/**
 * Background script to process activity logs
 * This is called asynchronously after page loads
 */

// Don't show any errors or output to the browser
error_reporting(0);
ini_set('display_errors', 0);

// Set max execution time to 30 seconds
set_time_limit(30);

// Send a response header immediately to prevent browser waiting
header('Content-Type: application/json');
echo json_encode(['success' => true]);
flush();

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

// Add a safety lock to prevent multiple simultaneous processing
$lock_file = __DIR__ . '/logs/processing.lock';
if (file_exists($lock_file) && (time() - filemtime($lock_file)) < 60) {
    // Another process is already handling this or a lock was left (less than 1 minute old)
    error_log("Activity log processing already in progress or locked");
    exit;
}

// Create the lock file
touch($lock_file);

try {
    // Include database connection
    require_once 'includes/db.php';

    // Process the activity logs
    $processed = process_activity_logs();

    // Log the result but don't output to browser
    if ($processed) {
        error_log("Processed {$processed} activity log entries");
    }
} catch (Exception $e) {
    error_log("Error in process_activity_logs.php: " . $e->getMessage());
} finally {
    // Always remove the lock file
    if (file_exists($lock_file)) {
        unlink($lock_file);
    }
} 