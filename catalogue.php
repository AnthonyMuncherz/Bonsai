<?php
$page_title = 'Book Catalogue';
require_once 'includes/db.php';

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header after redirects
require_once 'includes/header.php';

// Get books from database
$db = get_db_connection();
$query = "SELECT * FROM books ORDER BY title ASC";
$result = $db->query($query);

// Get user's wishlist if logged in
$user_wishlist = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $wishlist_query = "SELECT book_id FROM wishlist WHERE user_id = :user_id";
    $stmt = $db->prepare($wishlist_query);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $wishlist_result = $stmt->execute();
    
    while ($item = $wishlist_result->fetchArray(SQLITE3_ASSOC)) {
        $user_wishlist[] = $item['book_id'];
    }
}

// Define categories for filter
$categories = [];
$category_query = "SELECT DISTINCT category FROM books ORDER BY category ASC";
$category_result = $db->query($category_query);
while ($row = $category_result->fetchArray(SQLITE3_ASSOC)) {
    $categories[] = $row['category'];
}

// Handle filtering
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Create where clause if filtering is active
$where_clause = '';
$params = [];

if (!empty($filter_category)) {
    $where_clause = " WHERE category = :category";
    $params[':category'] = $filter_category;
}

if (!empty($search_term)) {
    $where_clause = empty($where_clause) ? " WHERE " : $where_clause . " AND ";
    $where_clause .= "(title LIKE :search OR author LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search_term%";
}

// Get filtered books
$query = "SELECT * FROM books" . $where_clause . " ORDER BY title ASC";
$stmt = $db->prepare($query);

foreach ($params as $param => $value) {
    $stmt->bindValue($param, $value);
}

$result = $stmt->execute();
?>

<!-- Catalogue Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">Bonsai Book Catalogue</h1>
        <p class="text-center text-gray-600 mt-2 mb-8">Explore our collection of specialized bonsai books</p>
        
        <?php if($_SESSION['is_admin'] == 1): ?>
        <!-- Admin Actions -->
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row gap-4 justify-center mb-6">
            <a href="add_book.php" class="btn btn-primary flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Add New Book
            </a>
            <a href="manage_books.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Manage Books
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Search and Filter -->
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6 mt-8">
            <form method="GET" action="catalogue.php" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Books:</label>
                    <input type="text" name="search" id="search" 
                           value="<?php echo htmlspecialchars($search_term); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md" 
                           placeholder="Search by title, author, or keywords">
                </div>
                
                <div class="md:w-1/4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category:</label>
                    <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" 
                                    <?php echo $filter_category === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Book Catalogue -->
<section class="py-12">
    <div class="container mx-auto">
        <!-- Filter info -->
        <?php if (!empty($filter_category) || !empty($search_term)): ?>
            <div class="mb-6 px-4">
                <div class="flex items-center">
                    <h2 class="text-lg font-medium mr-4">Active Filters:</h2>
                    <?php if (!empty($filter_category)): ?>
                        <span class="bg-gray-200 rounded-full px-3 py-1 text-sm mr-2">
                            Category: <?php echo htmlspecialchars($filter_category); ?>
                            <a href="?<?php echo !empty($search_term) ? 'search=' . urlencode($search_term) : ''; ?>" 
                               class="text-gray-600 ml-1" title="Remove filter">×</a>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!empty($search_term)): ?>
                        <span class="bg-gray-200 rounded-full px-3 py-1 text-sm">
                            Search: <?php echo htmlspecialchars($search_term); ?>
                            <a href="?<?php echo !empty($filter_category) ? 'category=' . urlencode($filter_category) : ''; ?>" 
                               class="text-gray-600 ml-1" title="Remove filter">×</a>
                        </span>
                    <?php endif; ?>
                    
                    <a href="catalogue.php" class="text-primary ml-4 text-sm">Clear all filters</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Book Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php $has_books = false; ?>
            <?php while ($book = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <?php $has_books = true; ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform hover:scale-105">
                    <!-- Book Image -->
                    <a href="book.php?id=<?php echo $book['id']; ?>" class="block h-64 bg-gray-100 overflow-hidden">
                        <?php if (!empty($book['image']) && file_exists('Images/books/' . $book['image'])): ?>
                            <img src="Images/books/<?php echo htmlspecialchars($book['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($book['title']); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="h-full flex items-center justify-center bg-gray-200">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Book Info -->
                    <div class="p-6 flex flex-col h-64">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold mb-2">
                                    <a href="book.php?id=<?php echo $book['id']; ?>" class="hover:text-primary">
                                        <?php echo htmlspecialchars($book['title']); ?>
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4">by <?php echo htmlspecialchars($book['author']); ?></p>
                            </div>
                            <span class="bg-primary text-white px-3 py-1 rounded-full text-sm">
                                RM<?php echo number_format($book['price'], 2); ?>
                            </span>
                        </div>
                        
                        <p class="text-gray-700 mb-4 line-clamp-3 h-18 overflow-hidden"><?php echo htmlspecialchars($book['description']); ?></p>
                        
                        <div class="flex justify-between items-center mt-auto">
                            <span class="text-sm text-gray-500">
                                <?php if ($book['stock'] > 0): ?>
                                    <span class="text-green-600">In Stock (<?php echo $book['stock']; ?>)</span>
                                <?php else: ?>
                                    <span class="text-red-600">Out of Stock</span>
                                <?php endif; ?>
                            </span>
                            
                            <div class="flex space-x-2">
                                <!-- Wishlist Button -->
                                <form action="add_to_wishlist.php" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="p-2 rounded-md border <?php echo in_array($book['id'], $user_wishlist) ? 'text-red-500 border-red-500' : 'text-gray-400 border-gray-300 hover:text-red-500 hover:border-red-500'; ?> transition">
                                        <svg class="w-5 h-5" fill="<?php echo in_array($book['id'], $user_wishlist) ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                                
                                <!-- Add to Cart Button -->
                                <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark transition w-32 text-center" <?php echo $book['stock'] <= 0 ? 'disabled' : ''; ?>>
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if (!$has_books): ?>
                <div class="col-span-full text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold mt-4">No books found</h3>
                    <p class="text-gray-600 mt-2">Try changing your search or filter criteria</p>
                    <a href="catalogue.php" class="inline-block mt-4 text-primary">View all books</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 