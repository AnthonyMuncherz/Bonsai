<?php
$page_title = 'Order Details';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if order ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: my_orders.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = (int)$_GET['id'];
$db = get_db_connection();

// Get order details
$stmt = $db->prepare("
    SELECT o.*, sa.full_name, sa.address_line1, sa.address_line2, sa.city, sa.postal_code, sa.state, sa.country, sa.phone
    FROM orders o
    LEFT JOIN shipping_addresses sa ON o.shipping_address_id = sa.id
    WHERE o.id = :order_id AND o.user_id = :user_id
");
$stmt->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$order = $result->fetchArray(SQLITE3_ASSOC);

// If order not found or doesn't belong to the user, redirect
if (!$order) {
    header('Location: my_orders.php');
    exit;
}

// Get order items
$stmt = $db->prepare("
    SELECT oi.*, b.title, b.author, b.image
    FROM order_items oi
    JOIN books b ON oi.book_id = b.id
    WHERE oi.order_id = :order_id
");
$stmt->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$order_items = [];
while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
    $order_items[] = $item;
}

require_once 'includes/header.php';
?>

<!-- Account Header -->
<section class="bg-primary py-12">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-white text-center">My Account</h1>
    </div>
</section>

<!-- Order Details -->
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
            
            <!-- Order Details Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold">Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
                                <p class="text-gray-600">Placed on <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
                            </div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
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
                            <?php if ($order['status'] === 'pending'): ?>
                                <a href="payment.php?order_id=<?php echo $order_id; ?>" class="ml-3 inline-flex items-center px-4 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Complete Payment
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Shipping Address</h3>
                                <div class="text-sm text-gray-600">
                                    <?php echo htmlspecialchars($order['full_name']); ?><br>
                                    <?php echo htmlspecialchars($order['address_line1']); ?><br>
                                    <?php if (!empty($order['address_line2'])): ?>
                                        <?php echo htmlspecialchars($order['address_line2']); ?><br>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($order['postal_code'] . ' ' . $order['city']); ?><br>
                                    <?php echo htmlspecialchars($order['state'] . ', ' . $order['country']); ?><br>
                                    <?php echo htmlspecialchars($order['phone']); ?>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Order Information</h3>
                                <div class="text-sm">
                                    <div class="flex justify-between mb-1">
                                        <span class="font-medium">Payment Method:</span>
                                        <span>
                                            <?php
                                            switch ($order['payment_method']) {
                                                case 'credit_card':
                                                    echo 'Credit/Debit Card';
                                                    break;
                                                case 'bank_transfer':
                                                    echo 'Bank Transfer';
                                                    break;
                                                case 'e_wallet':
                                                    echo 'E-Wallet';
                                                    break;
                                                default:
                                                    echo htmlspecialchars($order['payment_method']);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($order['notes'])): ?>
                                        <div class="mt-2">
                                            <span class="font-medium">Order Notes:</span>
                                            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($order['notes']); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($order_items as $item): ?>
                                        <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location='book.php?id=<?php echo $item['book_id']; ?>'">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100">
                                                        <?php if (!empty($item['image']) && file_exists('Images/books/' . $item['image'])): ?>
                                                            <img src="Images/books/<?php echo htmlspecialchars($item['image']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                                                 class="h-10 w-10 object-cover">
                                                        <?php else: ?>
                                                            <div class="h-10 w-10 flex items-center justify-center bg-gray-200">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                                </svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo htmlspecialchars($item['title']); ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            by <?php echo htmlspecialchars($item['author']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">RM<?php echo number_format($item['price'], 2); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?php echo $item['quantity']; ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    RM<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">
                                            Subtotal:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            RM<?php echo number_format($order['total_amount'], 2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">
                                            Shipping:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">
                                            Free
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-bold">
                                            Total:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold">
                                            RM<?php echo number_format($order['total_amount'], 2); ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <a href="my_orders.php" class="text-primary hover:underline">
                        &larr; Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 