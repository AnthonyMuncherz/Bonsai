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
            
            // Create books table for catalogue
            $db->exec("
                CREATE TABLE books (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT NOT NULL,
                    author TEXT NOT NULL,
                    description TEXT,
                    price REAL NOT NULL,
                    image TEXT,
                    category TEXT,
                    stock INTEGER DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            // Create cart table
            $db->exec("
                CREATE TABLE cart (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    book_id INTEGER NOT NULL,
                    quantity INTEGER NOT NULL DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id),
                    FOREIGN KEY (book_id) REFERENCES books(id)
                )
            ");
            
            // Create wishlist table
            $db->exec("
                CREATE TABLE wishlist (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_id INTEGER NOT NULL,
                    book_id INTEGER NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id),
                    FOREIGN KEY (book_id) REFERENCES books(id),
                    UNIQUE(user_id, book_id)
                )
            ");
            
            // Insert sample books data
            $db->exec("
                INSERT INTO books (title, author, description, price, image, category, stock) 
                VALUES 
                ('The Complete Book of Bonsai', 'Harry Tomlinson', 'A comprehensive guide to the art and practice of Bonsai.', 35.99, 'bonsai-book-1.jpg', 'Guide', 15),
                ('Bonsai Basics', 'Colin Lewis', 'A beginners guide to growing and maintaining bonsai trees.', 24.50, 'bonsai-book-2.jpg', 'Beginner', 20),
                ('The Bonsai Bible', 'Peter Chan', 'The definitive guide to choosing and growing bonsai.', 29.99, 'bonsai-book-3.jpg', 'Reference', 10),
                ('Indoor Bonsai', 'Paul Lesniewicz', 'A complete guide to caring for bonsai trees indoors.', 19.95, 'bonsai-book-4.jpg', 'Indoor', 25),
                ('Japanese Maples', 'J. D. Vertrees', 'The definitive guide to Japanese maples for bonsai and landscape use.', 42.99, 'bonsai-book-5.jpg', 'Species', 8)
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