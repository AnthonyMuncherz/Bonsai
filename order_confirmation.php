<?php
$page_title = 'Order Confirmation';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if order ID is provided
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header('Location: dashboard.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = (int)$_GET['order_id'];
$transaction_id = $_GET['transaction_id'] ?? null;
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
    header('Location: dashboard.php');
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

// Close database connection
$db->close();

require_once 'includes/header.php';
?>

<!-- Order Confirmation Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">Order Confirmation</h1>
        <p class="text-center text-gray-600 mt-2">Thank you for your order!</p>
    </div>
</section>

<!-- Order Confirmation Content -->
<section class="py-12">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 text-center border-b border-gray-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full text-green-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold mb-2">Order Placed Successfully!</h2>
                <p class="text-gray-600">
                    Your order #<?php echo htmlspecialchars($order['order_number']); ?> has been received and is now being processed.
                </p>
                <?php if ($transaction_id): ?>
                    <p class="text-gray-500 text-sm mt-2">
                        Transaction ID: <?php echo htmlspecialchars($transaction_id); ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4">Order Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-sm">
                            <div class="flex justify-between mb-1">
                                <span class="font-medium">Order Number:</span>
                                <span><?php echo htmlspecialchars($order['order_number']); ?></span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span class="font-medium">Order Date:</span>
                                <span><?php echo date('M j, Y, g:i a', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span class="font-medium">Order Status:</span>
                                <span>
                                    <?php
                                    switch ($order['status']) {
                                        case 'pending':
                                            echo '<span class="text-yellow-600">Pending</span>';
                                            break;
                                        case 'processing':
                                            echo '<span class="text-blue-600">Processing</span>';
                                            break;
                                        case 'shipped':
                                            echo '<span class="text-green-600">Shipped</span>';
                                            break;
                                        case 'delivered':
                                            echo '<span class="text-green-600">Delivered</span>';
                                            break;
                                        case 'cancelled':
                                            echo '<span class="text-red-600">Cancelled</span>';
                                            break;
                                        default:
                                            echo htmlspecialchars($order['status']);
                                    }
                                    ?>
                                </span>
                            </div>
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
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium mb-2">Shipping Address</h4>
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
                                <tr>
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
                                                    <a href="book.php?id=<?php echo $item['book_id']; ?>" class="hover:text-primary">
                                                        <?php echo htmlspecialchars($item['title']); ?>
                                                    </a>
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
        
        <div class="flex justify-between">
            <a href="dashboard.php" class="text-primary hover:underline">
                &larr; Back to Dashboard
            </a>
            <a href="catalogue.php" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark">
                Continue Shopping
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 