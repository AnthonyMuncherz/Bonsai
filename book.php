<?php
$page_title = 'Book Details';
require_once 'includes/db.php'; // Add this line to ensure session is available

// Check if book ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: catalogue.php');
    exit;
}

$book_id = (int)$_GET['id'];
$db = get_db_connection();

// Get book details
$stmt = $db->prepare("SELECT * FROM books WHERE id = :id");
$stmt->bindValue(':id', $book_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$book = $result->fetchArray(SQLITE3_ASSOC);

// If book not found, redirect to catalogue
if (!$book) {
    header('Location: catalogue.php');
    exit;
}

// Set page title to book title
$page_title = htmlspecialchars($book['title']);

// Check if book is in user's wishlist
$in_wishlist = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT id FROM wishlist WHERE user_id = :user_id AND book_id = :book_id");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $wishlist_item = $result->fetchArray(SQLITE3_ASSOC);
    $in_wishlist = $wishlist_item !== false;
    
    // Record view activity
    record_user_activity(
        $user_id,
        'book_view',
        "Viewed \"{$book['title']}\" book details",
        $book_id
    );
}

require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Display breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'Catalogue' => 'catalogue.php',
    $book['title'] => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', $book['title']);
?>

<!-- Book Details Section -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Book Image -->
                <div class="md:w-2/5 p-8 flex justify-center">
                    <div class="w-full max-w-xs">
                        <?php if (!empty($book['image']) && file_exists('Images/books/' . $book['image'])): ?>
                            <img src="Images/books/<?php echo htmlspecialchars($book['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($book['title']); ?>"
                                 class="w-full rounded-lg shadow-md">
                        <?php else: ?>
                            <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Book Information -->
                <div class="md:w-3/5 p-8">
                    <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($book['title']); ?></h1>
                    <p class="text-xl text-gray-600 mb-6">By <?php echo htmlspecialchars($book['author']); ?></p>
                    
                    <div class="mb-8">
                        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div>
                            <h3 class="text-gray-500 text-sm">Publisher:</h3>
                            <p class="font-medium"><?php echo !empty($book['publisher']) ? htmlspecialchars($book['publisher']) : 'Unknown'; ?></p>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm">ISBN:</h3>
                            <p class="font-medium"><?php echo !empty($book['isbn']) ? htmlspecialchars($book['isbn']) : 'Unknown'; ?></p>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm">Pages:</h3>
                            <p class="font-medium"><?php echo !empty($book['pages']) ? $book['pages'] : 'Unknown'; ?></p>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm">Published:</h3>
                            <p class="font-medium"><?php echo !empty($book['published_year']) ? $book['published_year'] : 'Unknown'; ?></p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <h3 class="text-gray-500 text-sm mb-1">Price:</h3>
                        <p class="text-3xl font-bold text-primary">RM<?php echo number_format($book['price'], 2); ?></p>
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <?php if ($book['stock'] > 0): ?>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition">
                                    Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled class="bg-gray-300 text-gray-600 px-6 py-3 rounded-md cursor-not-allowed">
                                Out of Stock
                            </button>
                        <?php endif; ?>
                        
                        <button class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md hover:bg-gray-300 transition">
                            Inquire About Availability
                        </button>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="add_to_wishlist.php" method="POST" class="ml-auto">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full border-2 <?php echo $in_wishlist ? 'text-red-500 border-red-500 bg-red-50' : 'text-gray-400 border-gray-300 hover:text-red-500 hover:border-red-500'; ?> transition">
                                    <svg class="w-6 h-6" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Books Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto max-w-6xl">
        <h2 class="text-2xl font-bold mb-8">You May Also Like</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            // Get random books
            $stmt = $db->prepare("
                SELECT * FROM books 
                WHERE id != :book_id 
                ORDER BY RANDOM() 
                LIMIT 4
            ");
            $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            
            $has_related_books = false;
            while ($related_book = $result->fetchArray(SQLITE3_ASSOC)) {
                $has_related_books = true;
                ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105">
                    <a href="book.php?id=<?php echo $related_book['id']; ?>" class="block">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <?php if (!empty($related_book['image']) && file_exists('Images/books/' . $related_book['image'])): ?>
                                <img src="Images/books/<?php echo htmlspecialchars($related_book['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($related_book['title']); ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="h-full flex items-center justify-center bg-gray-200">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 hover:text-primary"><?php echo htmlspecialchars($related_book['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-2">by <?php echo htmlspecialchars($related_book['author']); ?></p>
                            <p class="text-primary font-medium">RM<?php echo number_format($related_book['price'], 2); ?></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
            
            <?php if (!$has_related_books): ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600">No related books found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 