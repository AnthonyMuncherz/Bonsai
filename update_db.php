<?php
require_once 'includes/db.php';

// Get database connection
$db = get_db_connection();

// Check if books table already exists
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='books'");
$tableExists = $result->fetchArray();

if (!$tableExists) {
    echo "Creating books table...<br>";
    
    // Create books table
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
    
    echo "Books table created and populated successfully!<br>";
} else {
    echo "Books table already exists.<br>";
}

// Check if cart table exists
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='cart'");
$cartTableExists = $result->fetchArray();

if (!$cartTableExists) {
    echo "Creating cart table...<br>";
    
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
    
    echo "Cart table created successfully!<br>";
} else {
    echo "Cart table already exists.<br>";
}

echo "<p>Click <a href='catalogue.php'>here</a> to go to the catalogue.</p>";
?> 