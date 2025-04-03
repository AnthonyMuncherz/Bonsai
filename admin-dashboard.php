<?php
$page_title = 'Admin Dashboard';
require_once 'includes/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// Include header after redirects
require_once 'includes/header.php';

// Check if required admin files exist, if not, set flag for message
$add_book_exists = file_exists('add_book.php');
$manage_books_exists = file_exists('manage_books.php');
$edit_book_exists = file_exists('edit_book.php');
$missing_files = !$add_book_exists || !$manage_books_exists || !$edit_book_exists;

// Initialize database connection
$db = get_db_connection();

// Handle status updates for orders if submitted
if (isset($_POST['update_order_status']) && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    
    $update_query = $db->prepare("UPDATE orders SET status = :status WHERE id = :order_id");
    $update_query->bindValue(':status', $status, SQLITE3_TEXT);
    $update_query->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
    $update_query->execute();
    
    // Add activity log
    $activity = "Admin updated order #" . $order_id . " status to " . $status;
    $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :order_id)");
    $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
    $log_query->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
    $log_query->execute();
}

// Handle role updates for users if submitted
if (isset($_POST['update_user_role']) && isset($_POST['user_id']) && isset($_POST['is_admin'])) {
    $user_id = $_POST['user_id'];
    $is_admin = $_POST['is_admin'];
    
    $update_query = $db->prepare("UPDATE users SET is_admin = :is_admin WHERE id = :user_id");
    $update_query->bindValue(':is_admin', $is_admin, SQLITE3_INTEGER);
    $update_query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $update_query->execute();
    
    // Add activity log
    $role_name = ($is_admin == 1) ? 'Admin' : 'User';
    $activity = "Admin changed user #" . $user_id . " role to " . $role_name;
    $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :target_user)");
    $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
    $log_query->bindValue(':target_user', $user_id, SQLITE3_INTEGER);
    $log_query->execute();
}

// Get all users
$user_query = $db->query("SELECT id, username, email, created_at, is_admin FROM users ORDER BY id");
$users = [];
while ($row = $user_query->fetchArray(SQLITE3_ASSOC)) {
    $users[] = $row;
}

// Get recent orders
$order_query = $db->query("SELECT o.id, o.order_number, o.user_id, o.total_amount, o.status, o.created_at, u.username 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          ORDER BY o.created_at DESC 
                          LIMIT 10");
$orders = [];
while ($row = $order_query->fetchArray(SQLITE3_ASSOC)) {
    $orders[] = $row;
}

// Get low stock books (less than 5 in stock)
$book_query = $db->query("SELECT id, title, author, stock FROM books WHERE stock < 5 ORDER BY stock ASC");
$low_stock_books = [];
while ($row = $book_query->fetchArray(SQLITE3_ASSOC)) {
    $low_stock_books[] = $row;
}

// Count users
$total_users = count($users);
$admin_count = 0;
foreach ($users as $user) {
    if ($user['is_admin'] == 1) {
        $admin_count++;
    }
}

// Count orders by status
$order_stats_query = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
$order_stats = [];
while ($row = $order_stats_query->fetchArray(SQLITE3_ASSOC)) {
    $order_stats[$row['status']] = $row['count'];
}

// Get total book count
$book_count_query = $db->query("SELECT COUNT(*) as count FROM books");
$book_count = $book_count_query->fetchArray(SQLITE3_ASSOC)['count'];
?>

<!-- Admin Dashboard -->
<section class="py-16">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar -->
                    <div class="md:w-1/4 mb-8 md:mb-0">
                        <ul class="space-y-2">
                            <li>
                                <a href="#dashboard" class="block px-4 py-2 bg-gray-100 rounded font-medium">Dashboard Overview</a>
                            </li>
                            <li>
                                <a href="#orders" class="block px-4 py-2 hover:bg-gray-100 rounded">Order Management</a>
                            </li>
                            <li>
                                <a href="#users" class="block px-4 py-2 hover:bg-gray-100 rounded">User Management</a>
                            </li>
                            <li>
                                <a href="#books" class="block px-4 py-2 hover:bg-gray-100 rounded">Book Inventory</a>
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
                        <?php if($missing_files): ?>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Note:</strong> Some admin functionality files are missing. 
                                        <?php if(!$add_book_exists): ?>Create <code>add_book.php</code> to enable adding books.<?php endif; ?>
                                        <?php if(!$manage_books_exists): ?>Create <code>manage_books.php</code> to enable book management.<?php endif; ?>
                                        <?php if(!$edit_book_exists): ?>Create <code>edit_book.php</code> to enable editing books.<?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div id="dashboard">
                            <h2 class="text-2xl font-bold mb-6">Dashboard Overview</h2>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                    <h3 class="font-semibold text-blue-800">Total Users</h3>
                                    <p class="text-3xl font-bold text-blue-600"><?php echo $total_users; ?></p>
                                    <p class="text-sm text-blue-500 mt-1">Regular: <?php echo $total_users - $admin_count; ?> | Admin: <?php echo $admin_count; ?></p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                    <h3 class="font-semibold text-green-800">Books in Catalogue</h3>
                                    <p class="text-3xl font-bold text-green-600"><?php echo $book_count; ?></p>
                                    <p class="text-sm text-green-500 mt-1">Low stock: <?php echo count($low_stock_books); ?></p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                                    <h3 class="font-semibold text-purple-800">Total Orders</h3>
                                    <p class="text-3xl font-bold text-purple-600"><?php echo array_sum($order_stats); ?></p>
                                    <p class="text-sm text-purple-500 mt-1">
                                        Pending: <?php echo $order_stats['pending'] ?? 0; ?> | 
                                        Processing: <?php echo $order_stats['processing'] ?? 0; ?> | 
                                        Shipped: <?php echo $order_stats['shipped'] ?? 0; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Recent Orders -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Recent Orders</h3>
                                <div class="bg-white border rounded-lg overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php if (empty($orders)): ?>
                                                    <tr>
                                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($orders as $order): ?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                <?php echo htmlspecialchars($order['order_number']); ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                <?php echo htmlspecialchars($order['username']); ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                RM<?php echo number_format($order['total_amount'], 2); ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                    <?php
                                                                    switch ($order['status']) {
                                                                        case 'pending':
                                                                            echo 'bg-yellow-100 text-yellow-800';
                                                                            break;
                                                                        case 'processing':
                                                                            echo 'bg-blue-100 text-blue-800';
                                                                            break;
                                                                        case 'shipped':
                                                                            echo 'bg-indigo-100 text-indigo-800';
                                                                            break;
                                                                        case 'delivered':
                                                                            echo 'bg-green-100 text-green-800';
                                                                            break;
                                                                        case 'cancelled':
                                                                            echo 'bg-red-100 text-red-800';
                                                                            break;
                                                                        default:
                                                                            echo 'bg-gray-100 text-gray-800';
                                                                    }
                                                                    ?>">
                                                                    <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                <a href="order_details.php?id=<?php echo $order['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Low Stock Books -->
                            <div>
                                <h3 class="text-xl font-semibold mb-4">Low Stock Alert</h3>
                                <?php if (empty($low_stock_books)): ?>
                                    <p class="text-green-500 font-medium">All books have sufficient stock levels.</p>
                                <?php else: ?>
                                    <div class="bg-white border rounded-lg overflow-hidden">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <?php foreach ($low_stock_books as $book): ?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $book['id']; ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($book['title']); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($book['author']); ?></td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $book['stock'] == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                                    <?php echo $book['stock']; ?> in stock
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="text-indigo-600 hover:text-indigo-900">Update Stock</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Order Management Section -->
                        <div id="orders" class="mt-12 pt-8 border-t border-gray-200">
                            <h2 class="text-2xl font-bold mb-6">Order Management</h2>
                            
                            <div class="bg-white border rounded-lg overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php if (empty($orders)): ?>
                                                <tr>
                                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($orders as $order): ?>
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($order['order_number']); ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?php echo htmlspecialchars($order['username']); ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            RM<?php echo number_format($order['total_amount'], 2); ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <form action="admin-dashboard.php#orders" method="POST">
                                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                                <input type="hidden" name="update_order_status" value="1">
                                                                <select name="status" class="rounded border border-gray-300 text-sm p-1" onchange="this.form.submit()">
                                                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                                    <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                                    <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                                    <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Management Section -->
                        <div id="users" class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold">User Management</h2>
                                <a href="#" class="btn btn-primary">Add New User</a>
                            </div>
                            
                            <div class="bg-white border rounded-lg overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $user['id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <form action="admin-dashboard.php#users" method="POST">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="update_user_role" value="1">
                                                            <select name="is_admin" class="rounded border border-gray-300 text-sm p-1" onchange="this.form.submit()">
                                                                <option value="0" <?php echo $user['is_admin'] == 0 ? 'selected' : ''; ?>>User</option>
                                                                <option value="1" <?php echo $user['is_admin'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Book Inventory Section -->
                        <div id="books" class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold">Book Inventory</h2>
                                <a href="add_book.php" class="btn btn-primary">Add New Book</a>
                            </div>
                            
                            <p class="mb-4">Manage your book inventory by viewing all books in the <a href="catalogue.php" class="text-primary hover:underline">Book Catalogue</a>.</p>
                            
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
                                
                                <a href="catalogue.php" class="bg-white border border-gray-200 rounded-lg p-6 flex items-center hover:shadow-md transition-shadow">
                                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-lg">View Book Catalogue</h3>
                                        <p class="text-sm text-gray-600">Manage your entire book inventory</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 