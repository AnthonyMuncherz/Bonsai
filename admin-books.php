<?php
$page_title = 'Book Inventory';
require_once 'includes/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// Include header after redirects
require_once 'includes/header.php';

// Initialize database connection
$db = get_db_connection();

// Handle book deletion if submitted
if (isset($_POST['delete_book']) && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    
    $delete_query = $db->prepare("DELETE FROM books WHERE id = :book_id");
    $delete_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $delete_query->execute();
    
    // Add activity log
    $activity = "Admin deleted book #" . $book_id;
    $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :book_id)");
    $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
    $log_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $log_query->execute();
}

// Handle search if submitted
$search_term = '';
$category_filter = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
}

if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category_filter = $_GET['category'];
}

// Build the query based on filters
$query_parts = ["SELECT * FROM books"];
$where_clauses = [];
$params = [];

if (!empty($search_term)) {
    $where_clauses[] = "(title LIKE :search OR author LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search_term%";
}

if (!empty($category_filter)) {
    $where_clauses[] = "category = :category";
    $params[':category'] = $category_filter;
}

if (!empty($where_clauses)) {
    $query_parts[] = "WHERE " . implode(" AND ", $where_clauses);
}

$query_parts[] = "ORDER BY title ASC";
$sql = implode(" ", $query_parts);

// Execute the query
$stmt = $db->prepare($sql);
foreach ($params as $param => $value) {
    $stmt->bindValue($param, $value, SQLITE3_TEXT);
}
$result = $stmt->execute();

// Fetch books
$books = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $books[] = $row;
}

// Get categories for filter
$categories_query = $db->query("SELECT DISTINCT category FROM books ORDER BY category");
$categories = [];
while ($row = $categories_query->fetchArray(SQLITE3_ASSOC)) {
    if (!empty($row['category'])) {
        $categories[] = $row['category'];
    }
}

// Get low stock books (less than 5 in stock)
$low_stock_query = $db->query("SELECT COUNT(*) as count FROM books WHERE stock < 5");
$low_stock_count = $low_stock_query->fetchArray(SQLITE3_ASSOC)['count'];

// Get total book count
$book_count_query = $db->query("SELECT COUNT(*) as count FROM books");
$book_count = $book_count_query->fetchArray(SQLITE3_ASSOC)['count'];

// Get out of stock count
$out_of_stock_query = $db->query("SELECT COUNT(*) as count FROM books WHERE stock = 0");
$out_of_stock_count = $out_of_stock_query->fetchArray(SQLITE3_ASSOC)['count'];
?>

<!-- Admin Book Inventory -->
<section class="py-16">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">Book Inventory</h1>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar -->
                    <div class="md:w-1/4 mb-8 md:mb-0">
                        <ul class="space-y-2">
                            <li>
                                <a href="admin-dashboard.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Dashboard Overview</a>
                            </li>
                            <li>
                                <a href="admin-orders.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Order Management</a>
                            </li>
                            <li>
                                <a href="admin-users.php" class="block px-4 py-2 hover:bg-gray-100 rounded">User Management</a>
                            </li>
                            <li>
                                <a href="admin-books.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">Book Inventory</a>
                            </li>
                            <li>
                                <a href="catalogue.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Book Catalogue</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded text-red-600">Logout</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="md:w-3/4 md:pl-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Book Inventory</h2>
                            <a href="add_book.php" class="btn btn-primary">Add New Book</a>
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="font-semibold text-blue-800">Total Books</h3>
                                <p class="text-3xl font-bold text-blue-600"><?php echo $book_count; ?></p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                                <h3 class="font-semibold text-yellow-800">Low Stock</h3>
                                <p class="text-3xl font-bold text-yellow-600"><?php echo $low_stock_count; ?></p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                <h3 class="font-semibold text-red-800">Out of Stock</h3>
                                <p class="text-3xl font-bold text-red-600"><?php echo $out_of_stock_count; ?></p>
                            </div>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            <a href="add_book.php" class="bg-white border border-primary rounded-lg p-6 flex items-center hover:shadow-md transition-shadow">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Add New Book</h3>
                                    <p class="text-sm text-gray-600">Add a new book to your catalogue</p>
                                </div>
                            </a>
                            
                            <a href="#" id="show-low-stock" class="bg-white border border-yellow-300 rounded-lg p-6 flex items-center hover:shadow-md transition-shadow">
                                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Show Low Stock</h3>
                                    <p class="text-sm text-gray-600">View books with low inventory</p>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Search and Filter -->
                        <div class="mb-6">
                            <form action="admin-books.php" method="GET" class="space-y-4">
                                <div class="flex">
                                    <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" 
                                           placeholder="Search by title, author, or description" 
                                           class="flex-grow rounded-l border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r hover:bg-primary-dark">
                                        Search
                                    </button>
                                </div>
                                
                                <div class="flex space-x-4">
                                    <div class="w-1/2">
                                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                                        <select name="category" id="category" class="w-full rounded border border-gray-300 p-2" onchange="this.form.submit()">
                                            <option value="">All Categories</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category_filter === $category ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="w-1/2">
                                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                        <select name="sort" id="sort" class="w-full rounded border border-gray-300 p-2" onchange="this.form.submit()">
                                            <option value="title">Title</option>
                                            <option value="author">Author</option>
                                            <option value="price_low">Price: Low to High</option>
                                            <option value="price_high">Price: High to Low</option>
                                            <option value="stock_low">Stock: Low to High</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Books Table -->
                        <div class="bg-white border rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (empty($books)): ?>
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No books found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($books as $book): ?>
                                                <tr class="book-row <?php echo $book['stock'] < 5 ? 'low-stock' : ''; ?>" data-stock="<?php echo $book['stock']; ?>">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $book['id']; ?></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                            <?php if (!empty($book['image'])): ?>
                                                                <img src="/Bonsai/images/books/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="h-10 w-10 object-cover rounded mr-3">
                                                            <?php else: ?>
                                                                <div class="h-10 w-10 bg-gray-200 rounded mr-3 flex items-center justify-center text-gray-500">
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                                    </svg>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($book['title']); ?></div>
                                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($book['author']); ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?php echo htmlspecialchars($book['category'] ?? 'Uncategorized'); ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        RM<?php echo number_format($book['price'], 2); ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            <?php 
                                                            if ($book['stock'] == 0) {
                                                                echo 'bg-red-100 text-red-800';
                                                            } elseif ($book['stock'] < 5) {
                                                                echo 'bg-yellow-100 text-yellow-800';
                                                            } else {
                                                                echo 'bg-green-100 text-green-800';
                                                            }
                                                            ?>">
                                                            <?php echo $book['stock']; ?> in stock
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                        <form action="admin-books.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                                            <input type="hidden" name="delete_book" value="1">
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Simple JavaScript for filtering low stock -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const lowStockButton = document.getElementById('show-low-stock');
                                const bookRows = document.querySelectorAll('.book-row');
                                let showingLowStock = false;
                                
                                lowStockButton.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    
                                    showingLowStock = !showingLowStock;
                                    
                                    if (showingLowStock) {
                                        lowStockButton.querySelector('h3').textContent = 'Show All Books';
                                        lowStockButton.querySelector('p').textContent = 'View all books in inventory';
                                        
                                        bookRows.forEach(row => {
                                            const stock = parseInt(row.getAttribute('data-stock'));
                                            if (stock >= 5) {
                                                row.style.display = 'none';
                                            }
                                        });
                                    } else {
                                        lowStockButton.querySelector('h3').textContent = 'Show Low Stock';
                                        lowStockButton.querySelector('p').textContent = 'View books with low inventory';
                                        
                                        bookRows.forEach(row => {
                                            row.style.display = '';
                                        });
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 