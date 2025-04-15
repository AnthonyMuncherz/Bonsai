<?php
// Add new bonsai books to the database
require_once 'includes/db.php';

$db = get_db_connection();

// Define book data - based on real books found through research
$books = [
    // Beginner Category - 2 books
    [
        'title' => 'Bonsai Basics: Step-by-Step Guide',
        'author' => 'Christian Pessey & Rémy Samson',
        'description' => 'An ideal book for beginners that discusses bonsai care and training in a simple and understandable way. Covers essential techniques and daily maintenance.',
        'price' => 26.99,
        'image' => 'bonsai_basics_step.jpg',
        'category' => 'Beginner',
        'stock' => 15
    ],
    [
        'title' => 'Bonsai for Beginners',
        'author' => 'Peter Chan',
        'description' => 'A comprehensive introduction to the art of bonsai cultivation. Covers choosing the right tree, pruning, wiring, and shaping techniques for novices.',
        'price' => 28.50,
        'image' => 'bonsai_beginners_chan.jpg',
        'category' => 'Beginner',
        'stock' => 18
    ],
    
    // Guide Category - 3 books
    [
        'title' => 'The Complete Book of Bonsai',
        'author' => 'Harry Tomlinson',
        'description' => 'The ultimate reference guide that reveals every aspect of the art of bonsai, with inspirational ideas and practical advice for both beginners and experienced enthusiasts.',
        'price' => 39.95,
        'image' => 'complete_book_tomlinson.jpg',
        'category' => 'Guide',
        'stock' => 12
    ],
    [
        'title' => 'The Little Book of Bonsai',
        'author' => 'Jonas Dupuich',
        'description' => 'A modern, accessible guide to bonsai care and cultivation that makes this ancient art form approachable for anyone. Perfect introduction with detailed care for over 50 species.',
        'price' => 24.99,
        'image' => 'little_book_dupuich.jpg',
        'category' => 'Guide',
        'stock' => 20
    ],
    [
        'title' => 'Bonsai: The Art of Growing and Keeping Miniature Trees',
        'author' => 'Peter Chan',
        'description' => 'A complete reference for bonsai enthusiasts of all skill levels. Provides detailed information on styling, potting, pruning, watering, and seasonal care.',
        'price' => 32.95,
        'image' => 'art_of_growing_chan.jpg', 
        'category' => 'Guide',
        'stock' => 10
    ],
    
    // Indoor Category - 3 books
    [
        'title' => 'Indoor Bonsai for Beginners',
        'author' => 'Werner Busch',
        'description' => 'Specially focused on indoor bonsai selection, care, and training. Covers techniques to acquire a tree and maintain it indoors with detailed species showcases.',
        'price' => 22.99,
        'image' => 'indoor_bonsai_busch.jpg',
        'category' => 'Indoor',
        'stock' => 25
    ],
    [
        'title' => 'The Indoor Bonsai Handbook',
        'author' => 'David Prescott',
        'description' => 'A practical guide to growing successful indoor bonsai trees. Includes species-specific information on light, temperature, and humidity requirements for indoor environments.',
        'price' => 19.95,
        'image' => 'indoor_handbook_prescott.jpg',
        'category' => 'Indoor',
        'stock' => 18
    ],
    [
        'title' => 'Successful Indoor Bonsai',
        'author' => 'Paul Lesniewicz',
        'description' => 'Expert advice on selecting and maintaining bonsai trees that thrive indoors. Features detailed care guidelines for tropical and subtropical species suitable for home environments.',
        'price' => 24.50,
        'image' => 'successful_indoor_lesniewicz.jpg',
        'category' => 'Indoor',
        'stock' => 14
    ],
    
    // Species Category - 2 books
    [
        'title' => 'Bonsai with Japanese Maples',
        'author' => 'Peter Adams',
        'description' => 'The definitive guide to creating and maintaining Japanese maple bonsai. Explores the specific horticultural needs of Japanese maples and appropriate bonsai techniques.',
        'price' => 45.99,
        'image' => 'japanese_maples_adams.jpg',
        'category' => 'Species',
        'stock' => 8
    ],
    [
        'title' => 'Japanese Maples: The Complete Guide',
        'author' => 'J.D. Vertrees & Peter Gregory',
        'description' => 'The authoritative guide to Japanese maples with comprehensive information on growing, propagating, and caring for over 400 cultivars. Essential for bonsai practitioners working with maples.',
        'price' => 49.95,
        'image' => 'maples_guide_vertrees.jpg',
        'category' => 'Species',
        'stock' => 6
    ]
];

// Insert new books
$count = 0;
foreach ($books as $book) {
    $query = $db->prepare("
        INSERT INTO books (title, author, description, price, image, category, stock, created_at)
        VALUES (:title, :author, :description, :price, :image, :category, :stock, datetime('now'))
    ");
    
    $query->bindValue(':title', $book['title'], SQLITE3_TEXT);
    $query->bindValue(':author', $book['author'], SQLITE3_TEXT);
    $query->bindValue(':description', $book['description'], SQLITE3_TEXT);
    $query->bindValue(':price', $book['price'], SQLITE3_FLOAT);
    $query->bindValue(':image', $book['image'], SQLITE3_TEXT);
    $query->bindValue(':category', $book['category'], SQLITE3_TEXT);
    $query->bindValue(':stock', $book['stock'], SQLITE3_INTEGER);
    
    $result = $query->execute();
    
    if ($result) {
        $count++;
        echo "Added book: " . $book['title'] . "<br>";
    }
}

echo "<br>Successfully added $count new books to the database.";
?> 