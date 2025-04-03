<?php
// Verify book data updates
require_once 'includes/db.php';

$db = get_db_connection();

echo "<h1>Book Data Verification</h1>";
echo "<h2>Verifying Books with Updated Fields</h2>";

// Get book data
$query = $db->query("SELECT id, title, author, publisher, isbn, pages, published_year, category, stock, image FROM books ORDER BY id ASC");

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>ISBN</th>
        <th>Pages</th>
        <th>Year</th>
        <th>Category</th>
        <th>Stock</th>
        <th>Image</th>
      </tr>";

while ($book = $query->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($book['id']) . "</td>";
    echo "<td>" . htmlspecialchars($book['title']) . "</td>";
    echo "<td>" . htmlspecialchars($book['author']) . "</td>";
    echo "<td>" . htmlspecialchars($book['publisher'] ?? 'N/A') . "</td>";
    echo "<td>" . htmlspecialchars($book['isbn'] ?? 'N/A') . "</td>";
    echo "<td>" . htmlspecialchars($book['pages'] ?? 'N/A') . "</td>";
    echo "<td>" . htmlspecialchars($book['published_year'] ?? 'N/A') . "</td>";
    echo "<td>" . htmlspecialchars($book['category']) . "</td>";
    echo "<td>" . htmlspecialchars($book['stock']) . "</td>";
    echo "<td>" . htmlspecialchars($book['image']) . "</td>";
    echo "</tr>";
}

echo "</table>";
?> 