<?php
$page_title = 'My Orders';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$db = get_db_connection();

// Get user's orders
$stmt = $db->prepare("
    SELECT * FROM orders
    WHERE user_id = :user_id
    ORDER BY created_at DESC
");
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$orders = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $orders[] = $row;
}

// Close database connection
$db->close();

require_once 'includes/header.php';
?>

<!-- Account Header -->
<section class="bg-primary py-12">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-white text-center">My Account</h1>
    </div>
</section>

<!-- My Orders -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <div class="flex flex-wrap md:flex-nowrap">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4 mb-8 md:mb-0 md:pr-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-primary p-4">
                        <h2 class="text-xl font-bold text-white">Account Menu</h2>
                    </div>
                    <nav class="p-4">
                        <ul class="space-y-2">
                            <li>
                                <a href="dashboard.php" class="block p-2 hover:bg-gray-100 rounded">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="my_orders.php" class="block p-2 bg-gray-100 text-primary font-medium rounded">
                                    My Orders
                                </a>
                            </li>
                            <li>
                                <a href="wishlist.php" class="block p-2 hover:bg-gray-100 rounded">
                                    Wishlist
                                </a>
                            </li>
                            <li>
                                <a href="edit_account.php" class="block p-2 hover:bg-gray-100 rounded">
                                    Edit Account
                                </a>
                            </li>
                            <li>
                                <a href="change_password.php" class="block p-2 hover:bg-gray-100 rounded">
                                    Change Password
                                </a>
                            </li>
                            <li>
                                <a href="activity_history.php" class="block p-2 hover:bg-gray-100 rounded">
                                    Activity History
                                </a>
                            </li>
                            <li>
                                <a href="logout.php" class="block p-2 hover:bg-gray-100 text-red-600 rounded">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Orders Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b border-gray-200 p-6">
                        <h2 class="text-2xl font-bold">My Orders</h2>
                        <p class="text-gray-600">View and track your orders</p>
                    </div>
                    
                    <?php if (empty($orders)): ?>
                        <div class="p-6 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold mb-2">No Orders Found</h3>
                            <p class="text-gray-600 mb-6">You haven't placed any orders yet.</p>
                            <a href="catalogue.php" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                                Browse Books
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Order
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo htmlspecialchars($order['order_number']); ?>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                                    </div>
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
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        RM<?php echo number_format($order['total_amount'], 2); ?>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="order_details.php?id=<?php echo $order['id']; ?>" class="text-primary hover:text-primary-dark">
                                                        View Details
                                                    </a>
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
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 