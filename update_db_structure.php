<?php
// Update the database structure to add publisher, ISBN, pages, and published_year columns
require_once 'includes/db.php';

$db = get_db_connection();

echo "Starting database structure update...<br>";

// Check if columns exist before trying to add them
$result = $db->query("PRAGMA table_info(books)");
$columns = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $columns[] = $row['name'];
}

$columnsToAdd = [
    'publisher' => 'TEXT',
    'isbn' => 'TEXT',
    'pages' => 'INTEGER',
    'published_year' => 'INTEGER'
];

$addedColumns = 0;

foreach ($columnsToAdd as $column => $type) {
    if (!in_array($column, $columns)) {
        $db->exec("ALTER TABLE books ADD COLUMN $column $type");
        echo "Added column: $column ($type)<br>";
        $addedColumns++;
    } else {
        echo "Column already exists: $column<br>";
    }
}

if ($addedColumns > 0) {
    echo "<br>Successfully added $addedColumns new columns to the books table.";
} else {
    echo "<br>No new columns were added as they already exist.";
}

// Now update the books we added earlier with publisher, ISBN, pages, and published_year data

$booksData = [
    // Beginner books
    [
        'title' => 'Bonsai Basics: Step-by-Step Guide',
        'publisher' => 'Sterling Publishing',
        'isbn' => '978-0-8069-0327-5',
        'pages' => 120,
        'published_year' => 2001
    ],
    [
        'title' => 'Bonsai for Beginners',
        'publisher' => 'Tuttle Publishing',
        'isbn' => '978-4-8053-1466-2',
        'pages' => 144,
        'published_year' => 2019
    ],
    
    // Guide books
    [
        'title' => 'The Complete Book of Bonsai',
        'publisher' => 'DK Publishing',
        'isbn' => '978-1-4654-6850-8',
        'pages' => 224,
        'published_year' => 2018
    ],
    [
        'title' => 'The Little Book of Bonsai',
        'publisher' => 'Ten Speed Press',
        'isbn' => '978-0-399-58275-2',
        'pages' => 112,
        'published_year' => 2020
    ],
    [
        'title' => 'Bonsai: The Art of Growing and Keeping Miniature Trees',
        'publisher' => 'Skyhorse Publishing',
        'isbn' => '978-1-63220-480-5',
        'pages' => 160,
        'published_year' => 2017
    ],
    
    // Indoor books
    [
        'title' => 'Indoor Bonsai for Beginners',
        'publisher' => 'Cassell Illustrated',
        'isbn' => '978-1-8440-3350-8',
        'pages' => 112,
        'published_year' => 2005
    ],
    [
        'title' => 'The Indoor Bonsai Handbook',
        'publisher' => 'Headline Book Publishing',
        'isbn' => '978-0-7472-7602-4',
        'pages' => 128,
        'published_year' => 2008
    ],
    [
        'title' => 'Successful Indoor Bonsai',
        'publisher' => 'Blandford Press',
        'isbn' => '978-0-7137-1152-6',
        'pages' => 144,
        'published_year' => 1996
    ],
    
    // Species books
    [
        'title' => 'Bonsai with Japanese Maples',
        'publisher' => 'Timber Press',
        'isbn' => '978-0-8819-2809-3',
        'pages' => 156,
        'published_year' => 2006
    ],
    [
        'title' => 'Japanese Maples: The Complete Guide',
        'publisher' => 'Timber Press',
        'isbn' => '978-0-8819-2867-3',
        'pages' => 404,
        'published_year' => 2010
    ]
];

$updatedBooks = 0;
foreach ($booksData as $bookData) {
    $query = $db->prepare("
        UPDATE books 
        SET publisher = :publisher, isbn = :isbn, pages = :pages, published_year = :published_year
        WHERE title = :title
    ");
    
    $query->bindValue(':publisher', $bookData['publisher'], SQLITE3_TEXT);
    $query->bindValue(':isbn', $bookData['isbn'], SQLITE3_TEXT);
    $query->bindValue(':pages', $bookData['pages'], SQLITE3_INTEGER);
    $query->bindValue(':published_year', $bookData['published_year'], SQLITE3_INTEGER);
    $query->bindValue(':title', $bookData['title'], SQLITE3_TEXT);
    
    $result = $query->execute();
    
    if ($result) {
        $updatedBooks++;
        echo "Updated book data: " . $bookData['title'] . "<br>";
    }
}

echo "<br>Successfully updated $updatedBooks books with publisher, ISBN, pages, and published_year data.";
?> 