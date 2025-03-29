<?php
$page_title = 'Activity History';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user ID
$user_id = $_SESSION['user_id'];

// Process any pending activity logs
if (function_exists('process_activity_logs')) {
    process_activity_logs();
}

// Setup pagination with strict type checking
$items_per_page = 10;
$current_page = 1; // Default to page 1

// Validate and convert page parameter to integer
if (isset($_GET['page']) && ctype_digit($_GET['page']) && $_GET['page'] > 0) {
    $current_page = (int)$_GET['page'];
}

// Calculate offset with explicit integer typing
$current_page = (int)$current_page;
$items_per_page = (int)$items_per_page;
$offset = ($current_page - 1) * $items_per_page;
$offset = (int)$offset;

// Fetch activities with error handling
$activities = [];
$total_pages = 1;

try {
    $db = get_db_connection();
    
    // Get total count with explicit integer casting
    $count_stmt = $db->prepare("SELECT COUNT(*) as total FROM user_activities WHERE user_id = :user_id");
    $count_stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $count_result = $count_stmt->execute();
    $count_row = $count_result->fetchArray(SQLITE3_ASSOC);
    $total_count = isset($count_row['total']) ? (int)$count_row['total'] : 0;
    
    // Calculate total pages using integer division and ceiling
    $total_pages = $total_count > 0 ? (int)ceil($total_count / $items_per_page) : 1;
    
    // Adjust current page if needed
    if ((int)$current_page > (int)$total_pages) {
        $current_page = (int)$total_pages;
        $offset = ((int)$current_page - 1) * (int)$items_per_page;
        $offset = (int)$offset;
    }
    
    // Fetch activities for current page
    $stmt = $db->prepare("
        SELECT * FROM user_activities 
        WHERE user_id = :user_id 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':limit', $items_per_page, SQLITE3_INTEGER);
    $stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
    
    $result = $stmt->execute();
    
    // Collect activities
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $activities[] = $row;
    }
    
    // Close database connection
    $db->close();
    
} catch (Exception $e) {
    error_log("Activity history error: " . $e->getMessage());
    // Keep defaults if there's an error
    $activities = [];
    $total_pages = 1;
    $current_page = 1;
}

// Include header
require_once 'includes/header.php';
?>

<!-- Activity History Section -->
<section class="py-16">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">My Account</h1>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar -->
                    <div class="md:w-1/4 mb-8 md:mb-0">
                        <ul class="space-y-2">
                            <li>
                                <a href="dashboard.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Account Dashboard</a>
                            </li>
                            <li>
                                <a href="my_orders.php" class="block px-4 py-2 hover:bg-gray-100 rounded">My Orders</a>
                            </li>
                            <li>
                                <a href="wishlist.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Wish List</a>
                            </li>
                            <li>
                                <a href="edit_account.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Edit Account</a>
                            </li>
                            <li>
                                <a href="change_password.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Change Password</a>
                            </li>
                            <li>
                                <a href="activity_history.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">Activity History</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded text-red-600">Logout</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="md:w-3/4 md:pl-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Activity History</h2>
                            <a href="dashboard.php" class="text-primary hover:underline">← Back to Dashboard</a>
                        </div>
                        
                        <!-- Activity List -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <?php if (empty($activities)): ?>
                                <p class="text-gray-600 text-center py-4">No activity history to display.</p>
                            <?php else: ?>
                                <div class="divide-y divide-gray-200">
                                    <?php foreach ($activities as $activity): ?>
                                        <div class="py-4">
                                            <div class="flex items-start">
                                                <div class="mr-4 bg-primary bg-opacity-10 p-2 rounded-full">
                                                    <?php 
                                                    $icon = 'info-circle';
                                                    if (strpos($activity['activity_type'], 'cart') !== false) {
                                                        $icon = 'shopping-cart';
                                                    } elseif (strpos($activity['activity_type'], 'wishlist') !== false) {
                                                        $icon = 'heart';
                                                    } elseif (strpos($activity['activity_type'], 'account') !== false) {
                                                        $icon = 'user';
                                                    } elseif (strpos($activity['activity_type'], 'password') !== false) {
                                                        $icon = 'key';
                                                    }
                                                    ?>
                                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <?php if ($icon === 'shopping-cart'): ?>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        <?php elseif ($icon === 'heart'): ?>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                        <?php elseif ($icon === 'user'): ?>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        <?php elseif ($icon === 'key'): ?>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                                        <?php else: ?>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        <?php endif; ?>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-gray-800"><?php echo htmlspecialchars($activity['activity_description']); ?></p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <?php echo date('F j, Y, g:i a', strtotime($activity['created_at'])); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Extremely simplified pagination to avoid any calculation errors -->
                                <?php if ((int)$total_pages > 1): ?>
                                    <div class="flex justify-center mt-6">
                                        <nav class="inline-flex shadow-sm">
                                            <!-- Previous button -->
                                            <?php if ((int)$current_page > 1): ?>
                                                <a href="activity_history.php?page=<?php echo (int)$current_page - 1; ?>" class="px-3 py-2 bg-white border border-gray-300 text-sm font-medium rounded-l-md text-gray-700 hover:bg-gray-50">
                                                    Previous
                                                </a>
                                            <?php else: ?>
                                                <span class="px-3 py-2 bg-gray-100 border border-gray-300 text-sm font-medium rounded-l-md text-gray-400 cursor-not-allowed">
                                                    Previous
                                                </span>
                                            <?php endif; ?>
                                            
                                            <!-- Current page indicator -->
                                            <span class="px-3 py-2 bg-blue-50 border-t border-b border-gray-300 text-sm font-medium text-blue-700">
                                                Page <?php echo (int)$current_page; ?> of <?php echo (int)$total_pages; ?>
                                            </span>
                                            
                                            <!-- Next button -->
                                            <?php if ((int)$current_page < (int)$total_pages): ?>
                                                <a href="activity_history.php?page=<?php echo (int)$current_page + 1; ?>" class="px-3 py-2 bg-white border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 hover:bg-gray-50">
                                                    Next
                                                </a>
                                            <?php else: ?>
                                                <span class="px-3 py-2 bg-gray-100 border border-gray-300 text-sm font-medium rounded-r-md text-gray-400 cursor-not-allowed">
                                                    Next
                                                </span>
                                            <?php endif; ?>
                                        </nav>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 