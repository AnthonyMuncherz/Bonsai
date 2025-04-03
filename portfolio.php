<?php
$page_title = 'Collection';
require_once 'includes/db.php'; // Add this line to ensure session is available

// Check if it's an AJAX request for books
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

// Connect to the database
$db = get_db_connection();

// Get categories from the database
$category_query = "SELECT DISTINCT category FROM books ORDER BY category ASC";
$category_result = $db->query($category_query);
$categories = [];
while ($row = $category_result->fetchArray(SQLITE3_ASSOC)) {
    $categories[] = $row['category'];
}

// Handle filtering
$current_category = isset($_GET['category']) ? $_GET['category'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$books_per_page = 6;
$offset = ($page - 1) * $books_per_page;

// Build the query based on filter
$where_clause = '';
$params = [];

if (!empty($current_category) && $current_category !== 'all') {
    $where_clause = " WHERE category = :category";
    $params[':category'] = $current_category;
}

// Count total books for pagination
$count_query = "SELECT COUNT(*) as total FROM books" . $where_clause;
$count_stmt = $db->prepare($count_query);

foreach ($params as $param => $value) {
    $count_stmt->bindValue($param, $value);
}

$count_result = $count_stmt->execute();
$total_books = $count_result->fetchArray(SQLITE3_ASSOC)['total'];
$total_pages = ceil($total_books / $books_per_page);

// Get books with pagination
$query = "SELECT * FROM books" . $where_clause . " ORDER BY title ASC LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($query);

foreach ($params as $param => $value) {
    $stmt->bindValue($param, $value);
}

$stmt->bindValue(':limit', $books_per_page, SQLITE3_INTEGER);
$stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
$result = $stmt->execute();

// If it's an AJAX request, return JSON data and exit early
if ($is_ajax) {
    $books = [];
    while ($book = $result->fetchArray(SQLITE3_ASSOC)) {
        $books[] = $book;
    }
    
    $response = [
        'books' => $books,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_books' => $total_books
        ]
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Include header and other files only for non-AJAX requests
require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

// Output breadcrumbs
echo breadcrumbs([
    'Home' => 'index.php',
    'Book Collection' => ''
], '/Bonsai/Images/Index/tree-dark-background.jpg', 'Our Bonsai Book Collection');
?>

<!-- Animation styles -->
<style>
    .fade-transition {
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    
    .fade-out {
        opacity: 0;
        transform: translateY(10px);
    }
    
    .fade-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .book-item {
        transition: all 0.4s ease-in-out;
    }
    
    .category-filter.active {
        position: relative;
        overflow: hidden;
    }
    
    .category-filter.active:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: currentColor;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
        animation: slideIn 0.3s forwards;
    }
    
    @keyframes slideIn {
        to {
            transform: scaleX(1);
            transform-origin: left;
        }
    }
    
    .category-filter {
        transition: background-color 0.3s, color 0.3s, transform 0.2s;
    }
    
    .category-filter:hover {
        transform: translateY(-2px);
    }
    
    .category-filter:active {
        transform: translateY(1px);
    }
    
    #loading-indicator {
        transition: opacity 0.3s ease;
    }
</style>

<!-- Collection Categories -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <!-- Category Filters -->
        <div class="flex flex-wrap justify-center gap-2 sm:gap-4 mb-12">
            <button data-category="all" class="category-filter px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base <?php echo empty($current_category) || $current_category === 'all' ? 'bg-primary text-white active' : 'bg-white border border-gray-300 text-gray-700'; ?> rounded-md hover:bg-primary hover:text-white transition">All Books</button>
            
            <?php foreach ($categories as $category): ?>
                <button data-category="<?php echo htmlspecialchars($category); ?>" 
                   class="category-filter px-3 py-2 sm:px-4 sm:py-2 text-sm sm:text-base <?php echo $current_category === $category ? 'bg-primary text-white active' : 'bg-white border border-gray-300 text-gray-700'; ?> rounded-md hover:bg-primary hover:text-white transition">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <!-- Book Grid - Will be updated dynamically -->
        <div id="book-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 fade-transition">
            <?php $has_books = false; ?>
            <?php while ($book = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <?php $has_books = true; ?>
                <div class="book-item bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105">
                    <div class="h-64 bg-gray-100 overflow-hidden">
                        <?php if (!empty($book['image']) && file_exists('Images/books/' . $book['image'])): ?>
                            <img src="Images/books/<?php echo htmlspecialchars($book['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($book['title']); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <img src="/Bonsai/Images/Index/IMG_6171.JPG" alt="<?php echo htmlspecialchars($book['title']); ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <span class="inline-block px-3 py-1 bg-<?php echo get_category_color($book['category']); ?>-100 text-<?php echo get_category_color($book['category']); ?>-800 rounded-full text-xs font-medium mb-2"><?php echo htmlspecialchars($book['category']); ?></span>
                        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p class="text-gray-700 mb-3">By <?php echo htmlspecialchars($book['author']); ?></p>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?php echo htmlspecialchars($book['description']); ?>
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-primary">RM <?php echo number_format($book['price'], 2); ?></span>
                            <a href="book.php?id=<?php echo $book['id']; ?>" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if (!$has_books): ?>
                <div class="col-span-full text-center py-16 no-books-message">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-bold mt-4">No books found</h3>
                    <p class="text-gray-600 mt-2">Try selecting a different category</p>
                    <button data-category="all" class="category-filter inline-block mt-4 text-primary">View all books</button>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination - Will be updated dynamically -->
        <div id="pagination-container" class="flex justify-center mt-12 fade-transition <?php echo $total_pages <= 1 ? 'hidden' : ''; ?>">
            <nav class="pagination-nav inline-flex rounded-md shadow flex-wrap">
                <?php if ($page > 1): ?>
                    <button data-page="<?php echo $page - 1; ?>" class="pagination-btn px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-l-md hover:bg-gray-50">Previous</button>
                <?php else: ?>
                    <span class="px-4 py-2 border border-gray-300 bg-gray-100 text-gray-400 rounded-l-md cursor-not-allowed">Previous</span>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <button data-page="<?php echo $i; ?>" 
                       class="pagination-btn px-4 py-2 border-t border-b border-gray-300 <?php echo $i === $page ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50'; ?>">
                        <?php echo $i; ?>
                    </button>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <button data-page="<?php echo $page + 1; ?>" class="pagination-btn px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-r-md hover:bg-gray-50">Next</button>
                <?php else: ?>
                    <span class="px-4 py-2 border border-gray-300 bg-gray-100 text-gray-400 rounded-r-md cursor-not-allowed">Next</span>
                <?php endif; ?>
            </nav>
        </div>
        
        <!-- Loading Indicator -->
        <div id="loading-indicator" class="hidden opacity-0 flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-primary"></div>
        </div>
        
        <!-- Call to Action -->
        <div class="bg-gray-50 rounded-lg p-8 mt-16 text-center">
            <h2 class="text-2xl font-bold mb-4">Want to see our full collection?</h2>
            <p class="text-gray-700 mb-6 max-w-3xl mx-auto">
                Visit our catalogue to see all available books with detailed descriptions, reviews, and purchasing options.
            </p>
            <a href="catalogue.php" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition inline-flex items-center">
                Go to Full Catalogue
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Call to action section -->
<section class="py-16 bg-secondary">
    <div class="container mx-auto">
        <div class="text-center fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Start Your Bonsai Library?</h2>
            <p class="text-lg mb-8 max-w-3xl mx-auto">
                Whether you're looking for your first bonsai book or adding to your collection, 
                we're here to help you find the perfect resources for your bonsai journey. Our knowledgeable team 
                can provide personalized recommendations based on your experience level and interests.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="catalogue.php" class="btn btn-primary">View All Categories</a>
                <a href="contacts.php" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php
// Helper function to get color class based on category
function get_category_color($category) {
    $colors = [
        'Beginner' => 'blue',
        'Guide' => 'green',
        'Reference' => 'purple',
        'Indoor' => 'yellow',
        'Species' => 'red',
        'Tropical' => 'green'
    ];
    
    return isset($colors[$category]) ? $colors[$category] : 'gray';
}
?>

<!-- JavaScript for dynamic filtering without page refresh -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCategory = '<?php echo empty($current_category) ? "all" : $current_category; ?>';
    let currentPage = <?php echo $page; ?>;
    let isLoading = false;
    
    // Helper to create a book card element
    function createBookCard(book) {
        const categoryColor = getCategoryColor(book.category);
        
        return `
            <div class="book-item bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105 opacity-0" style="transform: translateY(20px);">
                <div class="h-64 bg-gray-100 overflow-hidden">
                    ${book.image && book.image !== '' ?
                    `<img src="Images/books/${book.image}" alt="${book.title}" class="w-full h-full object-cover">` :
                    `<img src="/Bonsai/Images/Index/IMG_6171.JPG" alt="${book.title}" class="w-full h-full object-cover">`}
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-${categoryColor}-100 text-${categoryColor}-800 rounded-full text-xs font-medium mb-2">${book.category}</span>
                    <h3 class="text-xl font-bold mb-2">${book.title}</h3>
                    <p class="text-gray-700 mb-3">By ${book.author}</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">${book.description}</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary">RM ${parseFloat(book.price).toFixed(2)}</span>
                        <a href="book.php?id=${book.id}" class="text-primary hover:text-primary-dark font-medium">View Details →</a>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Helper to create pagination
    function createPagination(pagination) {
        if (pagination.total_pages <= 1) {
            return '';
        }
        
        let paginationHtml = '<nav class="pagination-nav inline-flex rounded-md shadow flex-wrap">';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHtml += `<button data-page="${pagination.current_page - 1}" class="pagination-btn px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-l-md hover:bg-gray-50">Previous</button>`;
        } else {
            paginationHtml += `<span class="px-4 py-2 border border-gray-300 bg-gray-100 text-gray-400 rounded-l-md cursor-not-allowed">Previous</span>`;
        }
        
        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            paginationHtml += `<button data-page="${i}" class="pagination-btn px-4 py-2 border-t border-b border-gray-300 ${i === pagination.current_page ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50'}">${i}</button>`;
        }
        
        // Next button
        if (pagination.current_page < pagination.total_pages) {
            paginationHtml += `<button data-page="${pagination.current_page + 1}" class="pagination-btn px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-r-md hover:bg-gray-50">Next</button>`;
        } else {
            paginationHtml += `<span class="px-4 py-2 border border-gray-300 bg-gray-100 text-gray-400 rounded-r-md cursor-not-allowed">Next</span>`;
        }
        
        paginationHtml += '</nav>';
        return paginationHtml;
    }
    
    // No books message template
    function createNoBookMessage() {
        return `
            <div class="col-span-full text-center py-16 no-books-message opacity-0" style="transform: translateY(20px);">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-bold mt-4">No books found</h3>
                <p class="text-gray-600 mt-2">Try selecting a different category</p>
                <button data-category="all" class="category-filter inline-block mt-4 text-primary">View all books</button>
            </div>
        `;
    }
    
    // Function to animate new books in with a staggered entrance effect
    function animateBooks() {
        const bookItems = document.querySelectorAll('.book-item, .no-books-message');
        bookItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 50 * index); // Stagger the animations
        });
    }
    
    // Function to load books via AJAX
    function loadBooks(category, page) {
        if (isLoading) return;
        isLoading = true;
        
        // Show loading indicator with fade
        const loadingIndicator = document.getElementById('loading-indicator');
        loadingIndicator.classList.remove('hidden');
        setTimeout(() => {
            loadingIndicator.classList.remove('opacity-0');
        }, 10);
        
        // Update category button states
        document.querySelectorAll('.category-filter').forEach(button => {
            const btnCategory = button.getAttribute('data-category');
            button.classList.remove('active');
            
            if (btnCategory === category) {
                button.classList.add('active');
                button.classList.remove('bg-white', 'border', 'border-gray-300', 'text-gray-700');
                button.classList.add('bg-primary', 'text-white');
            } else {
                button.classList.remove('bg-primary', 'text-white');
                button.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-700');
            }
        });
        
        // Fade out the current books
        const bookGrid = document.getElementById('book-grid');
        bookGrid.classList.add('fade-out');
        
        // Format the URL with query parameters
        const url = `portfolio.php?category=${encodeURIComponent(category !== 'all' ? category : '')}&page=${page}`;
        
        // Set the AJAX header
        const headers = new Headers({
            'X-Requested-With': 'XMLHttpRequest'
        });
        
        // Wait for fade out to complete before fetching
        setTimeout(() => {
            // Fetch the data
            fetch(url, { headers })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update book grid
                    if (data.books.length === 0) {
                        bookGrid.innerHTML = createNoBookMessage();
                        // Re-attach event listeners to the "View all books" button
                        setTimeout(() => {
                            bookGrid.querySelector('.category-filter').addEventListener('click', function() {
                                loadBooks('all', 1);
                            });
                        }, 0);
                    } else {
                        let booksHtml = '';
                        data.books.forEach(book => {
                            booksHtml += createBookCard(book);
                        });
                        bookGrid.innerHTML = booksHtml;
                    }
                    
                    // Update pagination with fade
                    const paginationContainer = document.getElementById('pagination-container');
                    if (data.pagination.total_pages <= 1) {
                        paginationContainer.classList.add('fade-out');
                        setTimeout(() => {
                            paginationContainer.classList.add('hidden');
                        }, 300);
                    } else {
                        paginationContainer.innerHTML = createPagination(data.pagination);
                        paginationContainer.classList.remove('hidden');
                        setTimeout(() => {
                            paginationContainer.classList.remove('fade-out');
                        }, 10);
                        
                        // Attach event listeners to pagination buttons
                        paginationContainer.querySelectorAll('.pagination-btn').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const page = parseInt(this.getAttribute('data-page'));
                                loadBooks(currentCategory, page);
                                // Update URL without refreshing
                                updateURL(currentCategory, page);
                            });
                        });
                    }
                    
                    // Update state
                    currentCategory = category;
                    currentPage = data.pagination.current_page;
                    
                    // Animate in the new books
                    setTimeout(() => {
                        bookGrid.classList.remove('fade-out');
                        animateBooks();
                    }, 100);
                    
                    // Hide loading indicator with fade
                    loadingIndicator.classList.add('opacity-0');
                    setTimeout(() => {
                        loadingIndicator.classList.add('hidden');
                        isLoading = false;
                    }, 300);
                    
                    // Update URL without refreshing
                    updateURL(category, data.pagination.current_page);
                })
                .catch(error => {
                    console.error('Error loading books:', error);
                    
                    // Hide loading indicator
                    loadingIndicator.classList.add('opacity-0');
                    setTimeout(() => {
                        loadingIndicator.classList.add('hidden');
                        isLoading = false;
                    }, 300);
                    
                    // Show error and revert book grid
                    bookGrid.classList.remove('fade-out');
                });
        }, 300); // Match the fade-out transition duration
    }
    
    // Add event listeners to category filter buttons
    document.querySelectorAll('.category-filter').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            if (category !== currentCategory && !isLoading) {
                loadBooks(category, 1); // Reset to page 1 when changing categories
            }
        });
    });
    
    // Add event listeners to initial pagination buttons
    document.querySelectorAll('.pagination-btn').forEach(button => {
        button.addEventListener('click', function() {
            const page = parseInt(this.getAttribute('data-page'));
            if (page !== currentPage && !isLoading) {
                loadBooks(currentCategory, page);
            }
        });
    });
    
    // Animation for initial load
    setTimeout(() => {
        animateBooks();
    }, 100);
    
    // Helper function to get category color
    function getCategoryColor(category) {
        const colors = {
            'Beginner': 'blue',
            'Guide': 'green',
            'Reference': 'purple',
            'Indoor': 'yellow',
            'Species': 'red',
            'Tropical': 'green'
        };
        
        return colors[category] || 'gray';
    }
    
    // Helper function to update URL without page refresh
    function updateURL(category, page) {
        const url = new URL(window.location);
        
        if (category === 'all') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category);
        }
        
        url.searchParams.set('page', page);
        
        window.history.pushState({}, '', url);
    }
});
</script>

<?php require_once 'includes/footer.php'; ?> 