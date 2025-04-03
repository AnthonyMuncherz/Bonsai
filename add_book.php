<?php
$page_title = 'Add New Book';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$db = get_db_connection();

// Define variables and set to empty values
$title = $author = $description = $price = $category = $stock = '';
$errors = [];
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    
    // Validate fields
    if (empty($title)) {
        $errors[] = 'Book title is required';
    }
    
    if (empty($author)) {
        $errors[] = 'Author name is required';
    }
    
    if (empty($description)) {
        $errors[] = 'Book description is required';
    }
    
    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = 'Valid price is required';
    }
    
    if (empty($category)) {
        $errors[] = 'Book category is required';
    }
    
    if (empty($stock) || !is_numeric($stock) || $stock < 0) {
        $errors[] = 'Valid stock quantity is required';
    }
    
    // Handle file upload
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors[] = 'Only JPG, JPEG and PNG image files are allowed';
        } else {
            $upload_dir = 'Images/books/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $image_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $image_name;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $errors[] = 'Failed to upload image. Please try again.';
                $image_name = '';
            }
        }
    }
    
    // If no errors, insert the book into database
    if (empty($errors)) {
        $query = $db->prepare("
            INSERT INTO books (title, author, description, price, image, category, stock, created_at)
            VALUES (:title, :author, :description, :price, :image, :category, :stock, datetime('now'))
        ");
        
        $query->bindValue(':title', $title, SQLITE3_TEXT);
        $query->bindValue(':author', $author, SQLITE3_TEXT);
        $query->bindValue(':description', $description, SQLITE3_TEXT);
        $query->bindValue(':price', $price, SQLITE3_FLOAT);
        $query->bindValue(':image', $image_name, SQLITE3_TEXT);
        $query->bindValue(':category', $category, SQLITE3_TEXT);
        $query->bindValue(':stock', $stock, SQLITE3_INTEGER);
        
        $result = $query->execute();
        
        if ($result) {
            // Add to activity log
            $book_id = $db->lastInsertRowID();
            $activity = "Admin added new book: " . $title;
            
            $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :book_id)");
            $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
            $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
            $log_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
            $log_query->execute();
            
            $success_message = 'Book added successfully!';
            // Clear form fields after successful submission
            $title = $author = $description = $price = $category = $stock = '';
        } else {
            $errors[] = 'Failed to add book. Please try again.';
        }
    }
}

// Get categories for dropdown
$category_query = $db->query("SELECT DISTINCT category FROM books ORDER BY category ASC");
$categories = [];
while ($row = $category_query->fetchArray(SQLITE3_ASSOC)) {
    if (!empty($row['category'])) {
        $categories[] = $row['category'];
    }
}
// Add some default categories if none exist
if (empty($categories)) {
    $categories = ['Guide', 'Beginner', 'Reference', 'Indoor', 'Species', 'Advanced'];
}

?>

<!-- Add Book Form -->
<section class="py-16">
    <div class="container mx-auto max-w-3xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">Add New Book</h1>
            </div>
            
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="admin-dashboard.php" class="text-primary hover:underline flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                    <a href="catalogue.php" class="text-primary hover:underline">View Book Catalogue</a>
                </div>
                
                <?php if (!empty($success_message)): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700"><?php echo $success_message; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">Please fix the following errors:</p>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Book Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                        </div>
                        
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Author <span class="text-red-500">*</span></label>
                            <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($author); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required><?php echo htmlspecialchars($description); ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (RM) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($price); ?>" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select name="category" id="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="other">Other (Add New)</option>
                            </select>
                        </div>
                        
                        <div id="new-category-container" class="hidden">
                            <label for="new_category" class="block text-sm font-medium text-gray-700 mb-1">New Category <span class="text-red-500">*</span></label>
                            <input type="text" name="new_category" id="new_category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        </div>
                        
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" id="stock" value="<?php echo htmlspecialchars($stock); ?>" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Book Cover Image</label>
                        <input type="file" name="image" id="image" accept="image/jpeg, image/png, image/jpg" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                        <p class="mt-1 text-sm text-gray-500">Upload a JPG, JPEG or PNG image. Maximum size: 2MB.</p>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="window.location.href='admin-dashboard.php'" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">Add Book</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // Show/hide new category field based on selection
    document.getElementById('category').addEventListener('change', function() {
        const newCategoryContainer = document.getElementById('new-category-container');
        if (this.value === 'other') {
            newCategoryContainer.classList.remove('hidden');
            document.getElementById('new_category').setAttribute('required', 'required');
        } else {
            newCategoryContainer.classList.add('hidden');
            document.getElementById('new_category').removeAttribute('required');
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>
