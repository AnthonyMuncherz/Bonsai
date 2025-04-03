<?php
$page_title = 'Order Management';
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

// Get all orders
$order_query = $db->query("SELECT o.id, o.order_number, o.user_id, o.total_amount, o.status, o.created_at, u.username 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          ORDER BY o.created_at DESC");
$orders = [];
while ($row = $order_query->fetchArray(SQLITE3_ASSOC)) {
    $orders[] = $row;
}

// Count orders by status
$order_stats_query = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
$order_stats = [];
while ($row = $order_stats_query->fetchArray(SQLITE3_ASSOC)) {
    $order_stats[$row['status']] = $row['count'];
}
?>

<!-- Admin Orders Management -->
<section class="py-16">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">Order Management</h1>
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
                                <a href="admin-orders.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">Order Management</a>
                            </li>
                            <li>
                                <a href="admin-users.php" class="block px-4 py-2 hover:bg-gray-100 rounded">User Management</a>
                            </li>
                            <li>
                                <a href="admin-books.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Book Inventory</a>
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
                        <h2 class="text-2xl font-bold mb-6">Order Management</h2>
                        
                        <!-- Order Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="font-semibold text-blue-800 text-sm">Total Orders</h3>
                                <p class="text-2xl font-bold text-blue-600"><?php echo array_sum($order_stats); ?></p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                                <h3 class="font-semibold text-yellow-800 text-sm">Pending</h3>
                                <p class="text-2xl font-bold text-yellow-600"><?php echo $order_stats['pending'] ?? 0; ?></p>
                            </div>
                            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                <h3 class="font-semibold text-indigo-800 text-sm">Processing</h3>
                                <p class="text-2xl font-bold text-indigo-600"><?php echo $order_stats['processing'] ?? 0; ?></p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <h3 class="font-semibold text-green-800 text-sm truncate">Shipped/Delivered</h3>
                                <p class="text-2xl font-bold text-green-600"><?php echo ($order_stats['shipped'] ?? 0) + ($order_stats['delivered'] ?? 0); ?></p>
                            </div>
                        </div>
                        
                        <!-- Filter Controls -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">All Orders</h3>
                            <div class="flex space-x-2">
                                <select class="rounded border border-gray-300 text-sm p-2" id="orderFilter">
                                    <option value="all">All Orders</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Orders Table -->
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
                                                <tr class="order-row" data-status="<?php echo $order['status']; ?>">
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
                                                        <form action="admin-orders.php" method="POST">
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
                        
                        <!-- Simple JavaScript for filtering -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const filterSelect = document.getElementById('orderFilter');
                                const orderRows = document.querySelectorAll('.order-row');
                                
                                filterSelect.addEventListener('change', function() {
                                    const selectedValue = this.value;
                                    
                                    orderRows.forEach(row => {
                                        const status = row.getAttribute('data-status');
                                        
                                        if (selectedValue === 'all' || status === selectedValue) {
                                            row.style.display = '';
                                        } else {
                                            row.style.display = 'none';
                                        }
                                    });
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