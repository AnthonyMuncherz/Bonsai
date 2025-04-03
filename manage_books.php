<?php
$page_title = 'Manage Books';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$db = get_db_connection();

// Handle book deletion if requested
$delete_success = $delete_error = '';
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $book_id = $_GET['delete'];
    
    // Get book info for activity log
    $book_query = $db->prepare("SELECT title, image FROM books WHERE id = :book_id");
    $book_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $book_result = $book_query->execute();
    $book = $book_result->fetchArray(SQLITE3_ASSOC);
    
    if ($book) {
        // Delete the book
        $delete_query = $db->prepare("DELETE FROM books WHERE id = :book_id");
        $delete_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $result = $delete_query->execute();
        
        if ($result) {
            // Delete image file if it exists
            if (!empty($book['image'])) {
                $image_path = 'Images/books/' . $book['image'];
                if (file_exists($image_path)) {
                    @unlink($image_path);
                }
            }
            
            // Add to activity log
            $activity = "Admin deleted book: " . $book['title'];
            $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :book_id)");
            $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
            $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
            $log_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
            $log_query->execute();
            
            $delete_success = "Book \"" . htmlspecialchars($book['title']) . "\" has been deleted successfully.";
        } else {
            $delete_error = "Failed to delete the book. Please try again.";
        }
    } else {
        $delete_error = "Book not found.";
    }
}

// Get filter parameters
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'title';
$sort_order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Build query with filters
$where_clauses = [];
$params = [];

if (!empty($category_filter)) {
    $where_clauses[] = "category = :category";
    $params[':category'] = $category_filter;
}

if (!empty($search_term)) {
    $where_clauses[] = "(title LIKE :search OR author LIKE :search OR description LIKE :search)";
    $params[':search'] = "%$search_term%";
}

$where_clause = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Build sort clause
$valid_sort_fields = ['title', 'author', 'price', 'stock', 'category', 'created_at'];
if (!in_array($sort_by, $valid_sort_fields)) {
    $sort_by = 'title';
}

$sort_clause = "ORDER BY $sort_by $sort_order";

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM books $where_clause";
$count_stmt = $db->prepare($count_query);
foreach ($params as $param => $value) {
    $count_stmt->bindValue($param, $value);
}
$count_result = $count_stmt->execute();
$total_books = $count_result->fetchArray(SQLITE3_ASSOC)['total'];

// Pagination
$items_per_page = 10;
$total_pages = ceil($total_books / $items_per_page);
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;

$offset = ($current_page - 1) * $items_per_page;

// Get books with pagination
$query = "SELECT * FROM books $where_clause $sort_clause LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($query);
$stmt->bindValue(':limit', $items_per_page, SQLITE3_INTEGER);
$stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);

foreach ($params as $param => $value) {
    $stmt->bindValue($param, $value);
}

$result = $stmt->execute();
$books = [];
while ($book = $result->fetchArray(SQLITE3_ASSOC)) {
    $books[] = $book;
}

// Get categories for filter dropdown
$category_query = $db->query("SELECT DISTINCT category FROM books ORDER BY category ASC");
$categories = [];
while ($row = $category_query->fetchArray(SQLITE3_ASSOC)) {
    if (!empty($row['category'])) {
        $categories[] = $row['category'];
    }
}
?>

<!-- Manage Books Section -->
<section class="py-16">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">Manage Books</h1>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="admin-dashboard.php" class="text-primary hover:underline flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                    <a href="add_book.php" class="btn btn-primary">Add New Book</a>
                </div>
                
                <?php if (!empty($delete_success)): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700"><?php echo $delete_success; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($delete_error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700"><?php echo $delete_error; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Search and Filter -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <form method="GET" action="manage_books.php" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search:</label>
                            <input type="text" name="search" id="search" 
                                   value="<?php echo htmlspecialchars($search_term); ?>"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md" 
                                   placeholder="Search by title, author...">
                        </div>
                        
                        <div class="md:w-1/4">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category:</label>
                            <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>" 
                                            <?php echo $category_filter === $category ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="md:w-1/6">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By:</label>
                            <select name="sort" id="sort" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="title" <?php echo $sort_by === 'title' ? 'selected' : ''; ?>>Title</option>
                                <option value="author" <?php echo $sort_by === 'author' ? 'selected' : ''; ?>>Author</option>
                                <option value="price" <?php echo $sort_by === 'price' ? 'selected' : ''; ?>>Price</option>
                                <option value="stock" <?php echo $sort_by === 'stock' ? 'selected' : ''; ?>>Stock</option>
                                <option value="created_at" <?php echo $sort_by === 'created_at' ? 'selected' : ''; ?>>Date Added</option>
                            </select>
                        </div>
                        
                        <div class="md:w-1/6">
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order:</label>
                            <select name="order" id="order" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="asc" <?php echo $sort_order === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                                <option value="desc" <?php echo $sort_order === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md">Apply</button>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($books)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No books found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($books as $book): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $book['id']; ?></td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded">
                                                        <?php if (!empty($book['image']) && file_exists('Images/books/' . $book['image'])): ?>
                                                            <img src="Images/books/<?php echo htmlspecialchars($book['image']); ?>" 
                                                                alt="<?php echo htmlspecialchars($book['title']); ?>"
                                                                class="h-10 w-10 object-cover rounded">
                                                        <?php else: ?>
                                                            <svg class="h-10 w-10 text-gray-400 p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                            </svg>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($book['title']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($book['author']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($book['category']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">RM<?php echo number_format($book['price'], 2); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $book['stock'] == 0 ? 'bg-red-100 text-red-800' : ($book['stock'] < 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'); ?>">
                                                    <?php echo $book['stock']; ?> in stock
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $book['id']; ?>, '<?php echo addslashes(htmlspecialchars($book['title'])); ?>')" class="text-red-600 hover:text-red-900">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="flex justify-center mt-6">
                        <nav class="flex items-center">
                            <?php if ($current_page > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>" class="px-4 py-2 mx-1 border rounded text-primary hover:bg-gray-100">
                                    Previous
                                </a>
                            <?php else: ?>
                                <span class="px-4 py-2 mx-1 border rounded text-gray-400 bg-gray-50 cursor-not-allowed">Previous</span>
                            <?php endif; ?>
                            
                            <?php
                            $range = 2;
                            $start_page = max(1, $current_page - $range);
                            $end_page = min($total_pages, $current_page + $range);
                            
                            if ($start_page > 1) {
                                echo '<a href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '" class="px-4 py-2 mx-1 border rounded hover:bg-gray-100">1</a>';
                                if ($start_page > 2) {
                                    echo '<span class="px-4 py-2 mx-1">...</span>';
                                }
                            }
                            
                            for ($i = $start_page; $i <= $end_page; $i++) {
                                if ($i == $current_page) {
                                    echo '<span class="px-4 py-2 mx-1 border rounded bg-primary text-white">' . $i . '</span>';
                                } else {
                                    echo '<a href="?' . http_build_query(array_merge($_GET, ['page' => $i])) . '" class="px-4 py-2 mx-1 border rounded hover:bg-gray-100">' . $i . '</a>';
                                }
                            }
                            
                            if ($end_page < $total_pages) {
                                if ($end_page < $total_pages - 1) {
                                    echo '<span class="px-4 py-2 mx-1">...</span>';
                                }
                                echo '<a href="?' . http_build_query(array_merge($_GET, ['page' => $total_pages])) . '" class="px-4 py-2 mx-1 border rounded hover:bg-gray-100">' . $total_pages . '</a>';
                            }
                            ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>" class="px-4 py-2 mx-1 border rounded text-primary hover:bg-gray-100">
                                    Next
                                </a>
                            <?php else: ?>
                                <span class="px-4 py-2 mx-1 border rounded text-gray-400 bg-gray-50 cursor-not-allowed">Next</span>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="fixed inset-0 bg-black opacity-50"></div>
    <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
        <h3 class="text-lg font-bold mb-4">Confirm Deletion</h3>
        <p class="mb-6">Are you sure you want to delete "<span id="deleteBookTitle"></span>"? This action cannot be undone.</p>
        <div class="flex justify-end">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded mr-2">Cancel</button>
            <a href="#" id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded">Delete</a>
        </div>
    </div>
</div>

<script>
    function confirmDelete(bookId, bookTitle) {
        document.getElementById('deleteBookTitle').textContent = bookTitle;
        document.getElementById('confirmDeleteBtn').href = 'manage_books.php?delete=' + bookId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
