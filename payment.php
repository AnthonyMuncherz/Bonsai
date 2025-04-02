<?php
$page_title = 'Payment';
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
$db = get_db_connection();

// Get order details
$stmt = $db->prepare("
    SELECT o.*, sa.full_name, sa.address_line1, sa.address_line2, sa.city, sa.postal_code, sa.state, sa.country
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

// If order is already paid, redirect to order confirmation
if ($order['status'] !== 'pending') {
    header("Location: order_confirmation.php?order_id={$order_id}");
    exit;
}

// Handle payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Set pragmas to help prevent database locking issues - must be set BEFORE starting a transaction
        $db->exec('PRAGMA journal_mode = WAL;');
        $db->exec('PRAGMA synchronous = NORMAL;');
        $db->exec('PRAGMA busy_timeout = 15000;');
        $db->exec('PRAGMA temp_store = MEMORY;');
        
        // Start transaction
        $db->exec('BEGIN EXCLUSIVE TRANSACTION');
        
        // Simulate payment processing
        $transaction_id = 'TXN-' . date('Ymd') . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        
        // Update order status to processing
        $stmt = $db->prepare("
            UPDATE orders 
            SET status = 'processing', updated_at = CURRENT_TIMESTAMP 
            WHERE id = :order_id
        ");
        $stmt->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to update order status: " . $db->lastErrorMsg());
        }
        
        // Commit the transaction before attempting to record activity
        $db->exec('COMMIT');
        
        // Record activity independently to prevent transaction issues
        record_user_activity(
            $user_id,
            'payment_completed',
            "Completed payment for order #{$order['order_number']} (Transaction ID: {$transaction_id})",
            $order_id
        );
        
        // Close database connection
        $db->close();
        
        // Redirect to order confirmation page
        header("Location: order_confirmation.php?order_id={$order_id}&transaction_id={$transaction_id}");
        exit;
        
    } catch (Exception $e) {
        // Rollback on error
        if (isset($db) && $db instanceof SQLite3) {
            $db->exec('ROLLBACK');
            $db->close();
        }
        
        error_log("Payment processing error: " . $e->getMessage());
        
        // Redirect with error
        header("Location: payment.php?order_id={$order_id}&error=1");
        exit;
    }
}

// Check for errors in the URL
$payment_error = isset($_GET['error']) ? true : false;

// Determine which payment form to show based on selected payment method
$payment_method = $order['payment_method'];

require_once 'includes/header.php';
?>

<!-- Payment Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">Payment</h1>
        <p class="text-center text-gray-600 mt-2">Complete your payment for Order #<?php echo htmlspecialchars($order['order_number']); ?></p>
    </div>
</section>

<!-- Payment Content -->
<section class="py-12">
    <div class="container mx-auto max-w-4xl">
        <?php if ($payment_error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <p>There was an error processing your payment. Please try again or contact customer support.</p>
            </div>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Shipping Address</h3>
                        <div class="text-sm text-gray-600">
                            <?php echo htmlspecialchars($order['full_name']); ?><br>
                            <?php echo htmlspecialchars($order['address_line1']); ?><br>
                            <?php if (!empty($order['address_line2'])): ?>
                                <?php echo htmlspecialchars($order['address_line2']); ?><br>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($order['postal_code'] . ' ' . $order['city']); ?><br>
                            <?php echo htmlspecialchars($order['state'] . ', ' . $order['country']); ?>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-700 mb-2">Order Details</h3>
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-1">
                                <span>Order Number:</span>
                                <span class="font-medium"><?php echo htmlspecialchars($order['order_number']); ?></span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Order Date:</span>
                                <span class="font-medium"><?php echo date('M j, Y, g:i a', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Payment Method:</span>
                                <span class="font-medium">
                                    <?php
                                    switch ($payment_method) {
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
                                            echo htmlspecialchars($payment_method);
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between mb-1">
                                <span>Total Amount:</span>
                                <span class="font-medium">RM<?php echo number_format($order['total_amount'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-6">
                    <?php
                    switch ($payment_method) {
                        case 'credit_card':
                            echo 'Credit/Debit Card Payment';
                            break;
                        case 'bank_transfer':
                            echo 'Bank Transfer Payment';
                            break;
                        case 'e_wallet':
                            echo 'E-Wallet Payment';
                            break;
                        default:
                            echo 'Complete Payment';
                    }
                    ?>
                </h2>
                
                <form method="POST" action="payment.php?order_id=<?php echo $order_id; ?>">
                    <?php if ($payment_method === 'credit_card'): ?>
                        <!-- Credit Card Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                <input type="text" id="card_number" name="card_number" placeholder="4111 1111 1111 1111" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            
                            <div>
                                <label for="expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            
                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="card_name" class="block text-sm font-medium text-gray-700">Name on Card</label>
                                <input type="text" id="card_name" name="card_name" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        
                        <div class="mt-6 text-sm text-gray-500">
                            <p>For development purposes, you can use any valid-looking card information.</p>
                        </div>
                    <?php elseif ($payment_method === 'bank_transfer'): ?>
                        <!-- Bank Transfer Form -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h3 class="font-medium mb-4">Bank Transfer Details</h3>
                            <div class="mb-4">
                                <div class="text-sm text-gray-600">
                                    <div class="flex justify-between mb-1">
                                        <span>Bank Name:</span>
                                        <span class="font-medium">Maybank</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span>Account Name:</span>
                                        <span class="font-medium">Sejuta Ranting Sdn Bhd</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span>Account Number:</span>
                                        <span class="font-medium">1234 5678 9012</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span>Reference:</span>
                                        <span class="font-medium"><?php echo htmlspecialchars($order['order_number']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                <p>Please include your order number as reference when making the bank transfer.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="transfer_date" class="block text-sm font-medium text-gray-700">Transfer Date</label>
                                <input type="date" id="transfer_date" name="transfer_date" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                            
                            <div>
                                <label for="transfer_reference" class="block text-sm font-medium text-gray-700">Transfer Reference Number (Optional)</label>
                                <input type="text" id="transfer_reference" name="transfer_reference" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                    <?php elseif ($payment_method === 'e_wallet'): ?>
                        <!-- E-Wallet Form -->
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="wallet_type" class="block text-sm font-medium text-gray-700">Select E-Wallet</label>
                                <select id="wallet_type" name="wallet_type" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="touch_n_go">Touch n Go</option>
                                    <option value="grabpay">GrabPay</option>
                                    <option value="boost">Boost</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="wallet_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" id="wallet_phone" name="wallet_phone" 
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        
                        <div class="mt-6 text-sm text-gray-500">
                            <p>For development purposes, proceed with any valid-looking phone number.</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-8 flex justify-center">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-md hover:bg-primary-dark">
                            Complete Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="text-center">
            <a href="dashboard.php" class="text-primary hover:underline">
                Cancel and return to dashboard
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 