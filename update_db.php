<?php
require_once 'includes/db.php';

// Get database connection
$db = get_db_connection();

echo "<h1>Updating Database</h1>";

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

// Check if publisher, ISBN, pages, published_year columns exist in books table
$result = $db->query("PRAGMA table_info(books)");
$columns = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $columns[] = $row['name'];
}

// Add new columns if they don't exist
if (!in_array('publisher', $columns)) {
    echo "<p>Adding publisher column to books table...</p>";
    $db->exec("ALTER TABLE books ADD COLUMN publisher TEXT");
}

if (!in_array('isbn', $columns)) {
    echo "<p>Adding ISBN column to books table...</p>";
    $db->exec("ALTER TABLE books ADD COLUMN isbn TEXT");
}

if (!in_array('pages', $columns)) {
    echo "<p>Adding pages column to books table...</p>";
    $db->exec("ALTER TABLE books ADD COLUMN pages INTEGER");
}

if (!in_array('published_year', $columns)) {
    echo "<p>Adding published_year column to books table...</p>";
    $db->exec("ALTER TABLE books ADD COLUMN published_year INTEGER");
}

// Update sample books with the new information if it's empty
$books = [
    [
        'id' => 1,
        'publisher' => 'DK Publishing',
        'isbn' => '978-0-7566-5636-7',
        'pages' => 192,
        'published_year' => 2015
    ],
    [
        'id' => 2,
        'publisher' => 'Sterling Publishing',
        'isbn' => '978-1-58017-595-2',
        'pages' => 224,
        'published_year' => 2019
    ],
    [
        'id' => 3,
        'publisher' => 'Mitchell Beazley',
        'isbn' => '978-1-84533-592-5',
        'pages' => 256,
        'published_year' => 2018
    ],
    [
        'id' => 4,
        'publisher' => 'Random House',
        'isbn' => '978-0-8041-3333-3',
        'pages' => 176,
        'published_year' => 2016
    ],
    [
        'id' => 5,
        'publisher' => 'Timber Press',
        'isbn' => '978-0-88192-901-3',
        'pages' => 332,
        'published_year' => 2010
    ]
];

// Update book data
echo "<p>Updating book information...</p>";
foreach ($books as $book) {
    $stmt = $db->prepare("
        UPDATE books 
        SET publisher = :publisher, isbn = :isbn, pages = :pages, published_year = :published_year 
        WHERE id = :id
    ");
    $stmt->bindValue(':publisher', $book['publisher'], SQLITE3_TEXT);
    $stmt->bindValue(':isbn', $book['isbn'], SQLITE3_TEXT);
    $stmt->bindValue(':pages', $book['pages'], SQLITE3_INTEGER);
    $stmt->bindValue(':published_year', $book['published_year'], SQLITE3_INTEGER);
    $stmt->bindValue(':id', $book['id'], SQLITE3_INTEGER);
    $stmt->execute();
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

// Check if wishlist table exists
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='wishlist'");
$wishlistTableExists = $result->fetchArray();

if (!$wishlistTableExists) {
    echo "Creating wishlist table...<br>";
    
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
    
    echo "Wishlist table created successfully!<br>";
} else {
    echo "Wishlist table already exists.<br>";
}

// Check if user_activities table exists, create if not
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='user_activities'");
if (!$result->fetchArray()) {
    echo "<p>Creating user_activities table...</p>";
    $db->exec("
        CREATE TABLE user_activities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            activity_type VARCHAR(50) NOT NULL,
            activity_description TEXT NOT NULL,
            related_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
}

// Check if orders table exists, create if not
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='orders'");
if (!$result->fetchArray()) {
    echo "<p>Creating orders table...</p>";
    $db->exec("
        CREATE TABLE orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            order_number VARCHAR(20) NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            payment_method VARCHAR(50) NOT NULL,
            shipping_address_id INTEGER,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (shipping_address_id) REFERENCES shipping_addresses(id)
        )
    ");
}

// Check if order_items table exists, create if not
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='order_items'");
if (!$result->fetchArray()) {
    echo "<p>Creating order_items table...</p>";
    $db->exec("
        CREATE TABLE order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            book_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (book_id) REFERENCES books(id)
        )
    ");
}

// Check if shipping_addresses table exists, create if not
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='shipping_addresses'");
if (!$result->fetchArray()) {
    echo "<p>Creating shipping_addresses table...</p>";
    $db->exec("
        CREATE TABLE shipping_addresses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            address_line1 TEXT NOT NULL,
            address_line2 TEXT,
            city VARCHAR(100) NOT NULL,
            postal_code VARCHAR(20) NOT NULL,
            state VARCHAR(100) NOT NULL,
            country VARCHAR(100) NOT NULL DEFAULT 'Malaysia',
            phone VARCHAR(20) NOT NULL,
            is_default BOOLEAN NOT NULL DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");
}

// After creating the user_activities table, add sample activity for existing users if empty
$result = $db->query("SELECT COUNT(*) as count FROM user_activities");
$activityCount = $result->fetchArray(SQLITE3_ASSOC)['count'];

if ($activityCount == 0) {
    echo "Adding initial activity records for existing users...<br>";
    
    // Get existing users
    $users = $db->query("SELECT id FROM users");
    while ($user = $users->fetchArray(SQLITE3_ASSOC)) {
        $user_id = $user['id'];
        
        // Add a welcome activity
        $stmt = $db->prepare("
            INSERT INTO user_activities (user_id, activity_type, activity_description, created_at) 
            VALUES (:user_id, 'account', 'Account created and activated', datetime('now'))
        ");
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    echo "Initial activities added successfully!<br>";
}

// Update database schema if needed
echo "<p>Running database optimizations...</p>";
$db->exec('PRAGMA journal_mode = WAL;');
$db->exec('PRAGMA synchronous = NORMAL;');
$db->exec('PRAGMA busy_timeout = 5000;');

echo "<p>Click <a href='catalogue.php'>here</a> to go to the catalogue.</p>";

echo "<p>Database update completed successfully!</p>";
echo "<p><a href='index.php'>Return to Home</a></p>";
?> 