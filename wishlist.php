<?php
$page_title = 'My Wishlist';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header after redirects
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'My Wishlist' => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', 'My Wishlist');

// Get user's wishlist items
$user_id = $_SESSION['user_id'];
$db = get_db_connection();

$query = "
    SELECT w.id as wishlist_id, b.*
    FROM wishlist w
    JOIN books b ON w.book_id = b.id
    WHERE w.user_id = :user_id
    ORDER BY w.created_at DESC
";

$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$wishlist_items = [];

while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
    $wishlist_items[] = $item;
}
?>

<!-- Wishlist Content -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php if ($_GET['success'] === 'added_to_cart'): ?>
                    <p>Item added to cart successfully!</p>
                <?php elseif ($_GET['success'] === 'removed_from_wishlist'): ?>
                    <p>Item removed from wishlist successfully!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($wishlist_items)): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold mb-2">Your wishlist is empty</h2>
                <p class="text-gray-600 mb-6">Looks like you haven't added any books to your wishlist yet.</p>
                <a href="catalogue.php" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition">
                    Browse Books
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                        <!-- Book Image -->
                        <div class="h-64 bg-gray-100 overflow-hidden relative">
                            <?php if (!empty($item['image']) && file_exists('Images/books/' . $item['image'])): ?>
                                <img src="Images/books/<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="h-full flex items-center justify-center bg-gray-200">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Remove from wishlist button -->
                            <form action="add_to_wishlist.php" method="POST" class="absolute top-2 right-2">
                                <input type="hidden" name="book_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="bg-white text-red-500 p-2 rounded-full shadow-md hover:bg-red-500 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Book Info -->
                        <div class="p-6 flex flex-col h-64">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <p class="text-gray-600 mb-4">by <?php echo htmlspecialchars($item['author']); ?></p>
                                </div>
                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm">
                                    RM<?php echo number_format($item['price'], 2); ?>
                                </span>
                            </div>
                            
                            <p class="text-gray-700 mb-4 line-clamp-3 h-18 overflow-hidden"><?php echo htmlspecialchars($item['description']); ?></p>
                            
                            <div class="flex justify-between items-center mt-auto">
                                <span class="text-sm text-gray-500">
                                    <?php if ($item['stock'] > 0): ?>
                                        <span class="text-green-600">In Stock (<?php echo $item['stock']; ?>)</span>
                                    <?php else: ?>
                                        <span class="text-red-600">Out of Stock</span>
                                    <?php endif; ?>
                                </span>
                                
                                <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark transition w-32 text-center" <?php echo $item['stock'] <= 0 ? 'disabled' : ''; ?>>
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Call to Action -->
        <div class="mt-16 text-center">
            <a href="catalogue.php" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition inline-flex items-center">
                Browse More Books
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 