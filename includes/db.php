<?php
/**
 * Database connection handler using SQLite3
 */

// Set the database file path
$db_path = __DIR__ . '/../db/sejuta_ranting.db';

// Create the database connection
function get_db_connection() {
    global $db_path;
    
    try {
        // Create database file if it doesn't exist
        if (!file_exists($db_path)) {
            $db = new SQLite3($db_path);
            
            // Create tables
            $db->exec("
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    username TEXT NOT NULL UNIQUE,
                    email TEXT NOT NULL UNIQUE,
                    password TEXT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    is_admin INTEGER DEFAULT 0
                )
            ");
            
            // Create admin user
            $default_admin = 'admin';
            $default_email = 'admin@sejutaranting.my';
            $default_password = password_hash('admin123', PASSWORD_DEFAULT);
            
            $db->exec("
                INSERT INTO users (username, email, password, is_admin) 
                VALUES ('$default_admin', '$default_email', '$default_password', 1)
            ");
            
            return $db;
        } else {
            return new SQLite3($db_path);
        }
    } catch (Exception $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Helper function to check if a user exists by email or username
function user_exists($email, $username) {
    $db = get_db_connection();
    
    $query = $db->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
    $query->bindValue(':email', $email, SQLITE3_TEXT);
    $query->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $query->execute();
    
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    return $user !== false;
}

// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 