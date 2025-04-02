<?php
$page_title = 'Checkout';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$db = get_db_connection();

// Get cart items and calculate total
$query = "
    SELECT c.id, c.quantity, c.book_id, b.title, b.price, b.stock
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = :user_id
";

$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$cart_items = [];
$total_price = 0;

while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
    // Make sure we don't exceed stock
    if ($item['quantity'] > $item['stock']) {
        $item['quantity'] = $item['stock'];
        
        // Update the cart with the corrected quantity
        $update = $db->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
        $update->bindValue(':quantity', $item['stock'], SQLITE3_INTEGER);
        $update->bindValue(':id', $item['id'], SQLITE3_INTEGER);
        $update->execute();
    }
    
    $cart_items[] = $item;
    $total_price += $item['price'] * $item['quantity'];
}

// If cart is empty, redirect to cart page
if (empty($cart_items)) {
    header('Location: cart.php');
    exit;
}

// Get user's saved addresses
$query = "
    SELECT * FROM shipping_addresses
    WHERE user_id = :user_id
    ORDER BY is_default DESC, created_at DESC
";

$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$addresses = [];
while ($address = $result->fetchArray(SQLITE3_ASSOC)) {
    $addresses[] = $address;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $errors = [];
    
    if (isset($_POST['use_existing_address']) && !empty($_POST['address_id'])) {
        $address_id = (int) $_POST['address_id'];
        
        // Verify the address belongs to the user
        $stmt = $db->prepare("SELECT id FROM shipping_addresses WHERE id = :id AND user_id = :user_id");
        $stmt->bindValue(':id', $address_id, SQLITE3_INTEGER);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        if (!$result->fetchArray()) {
            $errors[] = "Invalid address selected.";
        }
    } else {
        // Validate new address fields
        $required_fields = ['full_name', 'address_line1', 'city', 'postal_code', 'state', 'phone'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required.";
            }
        }
    }
    
    if (empty($_POST['payment_method'])) {
        $errors[] = "Please select a payment method.";
    }
    
    // If no errors, proceed with creating order
    if (empty($errors)) {
        // Begin transaction
        $db->exec('BEGIN TRANSACTION');
        
        try {
            // Create or use existing shipping address
            if (isset($_POST['use_existing_address']) && !empty($_POST['address_id'])) {
                $address_id = (int) $_POST['address_id'];
            } else {
                // Insert new shipping address
                $stmt = $db->prepare("
                    INSERT INTO shipping_addresses (
                        user_id, full_name, address_line1, address_line2, 
                        city, postal_code, state, country, phone, is_default
                    ) VALUES (
                        :user_id, :full_name, :address_line1, :address_line2,
                        :city, :postal_code, :state, :country, :phone, :is_default
                    )
                ");
                
                $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                $stmt->bindValue(':full_name', $_POST['full_name'], SQLITE3_TEXT);
                $stmt->bindValue(':address_line1', $_POST['address_line1'], SQLITE3_TEXT);
                $stmt->bindValue(':address_line2', $_POST['address_line2'] ?? '', SQLITE3_TEXT);
                $stmt->bindValue(':city', $_POST['city'], SQLITE3_TEXT);
                $stmt->bindValue(':postal_code', $_POST['postal_code'], SQLITE3_TEXT);
                $stmt->bindValue(':state', $_POST['state'], SQLITE3_TEXT);
                $stmt->bindValue(':country', $_POST['country'] ?? 'Malaysia', SQLITE3_TEXT);
                $stmt->bindValue(':phone', $_POST['phone'], SQLITE3_TEXT);
                $stmt->bindValue(':is_default', isset($_POST['save_as_default']) ? 1 : 0, SQLITE3_INTEGER);
                
                $stmt->execute();
                $address_id = $db->lastInsertRowID();
                
                // If set as default, update other addresses
                if (isset($_POST['save_as_default'])) {
                    $stmt = $db->prepare("
                        UPDATE shipping_addresses 
                        SET is_default = 0 
                        WHERE user_id = :user_id AND id != :id
                    ");
                    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
                    $stmt->bindValue(':id', $address_id, SQLITE3_INTEGER);
                    $stmt->execute();
                }
            }
            
            // Generate order number
            $order_number = 'ORD-' . date('Ymd') . '-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
            
            // Create order
            $stmt = $db->prepare("
                INSERT INTO orders (
                    user_id, order_number, total_amount, status,
                    payment_method, shipping_address_id, notes
                ) VALUES (
                    :user_id, :order_number, :total_amount, 'pending',
                    :payment_method, :shipping_address_id, :notes
                )
            ");
            
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $stmt->bindValue(':order_number', $order_number, SQLITE3_TEXT);
            $stmt->bindValue(':total_amount', $total_price, SQLITE3_FLOAT);
            $stmt->bindValue(':payment_method', $_POST['payment_method'], SQLITE3_TEXT);
            $stmt->bindValue(':shipping_address_id', $address_id, SQLITE3_INTEGER);
            $stmt->bindValue(':notes', $_POST['notes'] ?? '', SQLITE3_TEXT);
            
            $stmt->execute();
            $order_id = $db->lastInsertRowID();
            
            // Add order items
            foreach ($cart_items as $item) {
                $stmt = $db->prepare("
                    INSERT INTO order_items (
                        order_id, book_id, quantity, price
                    ) VALUES (
                        :order_id, :book_id, :quantity, :price
                    )
                ");
                
                $stmt->bindValue(':order_id', $order_id, SQLITE3_INTEGER);
                $stmt->bindValue(':book_id', $item['book_id'], SQLITE3_INTEGER);
                $stmt->bindValue(':quantity', $item['quantity'], SQLITE3_INTEGER);
                $stmt->bindValue(':price', $item['price'], SQLITE3_FLOAT);
                
                $stmt->execute();
                
                // Update book stock
                $stmt = $db->prepare("
                    UPDATE books
                    SET stock = stock - :quantity
                    WHERE id = :book_id
                ");
                
                $stmt->bindValue(':quantity', $item['quantity'], SQLITE3_INTEGER);
                $stmt->bindValue(':book_id', $item['book_id'], SQLITE3_INTEGER);
                
                $stmt->execute();
            }
            
            // Record activity
            record_user_activity(
                $user_id,
                'order_placed',
                "Placed order #{$order_number} for RM" . number_format($total_price, 2),
                $order_id
            );
            
            // Clear cart
            $stmt = $db->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $stmt->execute();
            
            // Commit transaction
            $db->exec('COMMIT');
            
            // Close database connection
            $db->close();
            
            // Redirect to payment page with a small delay to ensure DB operations complete
            echo "<script>
                // Use a more reliable way to redirect
                document.addEventListener('DOMContentLoaded', function() {
                    var redirectUrl = 'payment.php?order_id={$order_id}';
                    
                    // Show loading message
                    document.body.innerHTML = `
                        <div class='flex items-center justify-center min-h-screen'>
                            <div class='text-center p-8 max-w-md mx-auto bg-white rounded-lg shadow-lg'>
                                <p class='text-lg mb-4'>Processing your order, please wait...</p>
                                <div class='w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto'></div>
                                <p class='text-sm text-gray-500 mt-4'>You will be redirected automatically</p>
                            </div>
                        </div>
                    `;
                    
                    // Redirect after a delay
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 2000);
                });
            </script>";
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $db->exec('ROLLBACK');
            
            // Close database connection
            $db->close();
            
            // Log the error
            error_log("Checkout error: " . $e->getMessage());
            
            $errors[] = "An error occurred: " . $e->getMessage();
        }
    }
}

// Get user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

require_once 'includes/header.php';
?>

<!-- Checkout Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">Checkout</h1>
        <p class="text-center text-gray-600 mt-2">Complete your purchase</p>
    </div>
</section>

<!-- Checkout Content -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="md:flex md:space-x-8">
            <!-- Checkout Form -->
            <div class="md:w-2/3">
                <form method="POST" action="checkout.php">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                            
                            <?php if (!empty($addresses)): ?>
                            <div class="mb-6">
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="use_existing_address" value="1" class="form-checkbox h-5 w-5 text-primary" 
                                               onchange="document.getElementById('existing-address').style.display = this.checked ? 'block' : 'none'; document.getElementById('new-address').style.display = this.checked ? 'none' : 'block';">
                                        <span class="ml-2">Use an existing address</span>
                                    </label>
                                </div>
                                
                                <div id="existing-address" class="hidden">
                                    <div class="grid grid-cols-1 gap-4">
                                        <?php foreach ($addresses as $address): ?>
                                            <div class="border border-gray-200 rounded p-4">
                                                <label class="flex items-start">
                                                    <input type="radio" name="address_id" value="<?php echo $address['id']; ?>" class="mt-1 mr-3">
                                                    <div>
                                                        <div class="font-medium"><?php echo htmlspecialchars($address['full_name']); ?></div>
                                                        <div class="text-sm text-gray-700">
                                                            <?php echo htmlspecialchars($address['address_line1']); ?><br>
                                                            <?php if (!empty($address['address_line2'])): ?>
                                                                <?php echo htmlspecialchars($address['address_line2']); ?><br>
                                                            <?php endif; ?>
                                                            <?php echo htmlspecialchars($address['postal_code']) . ' ' . htmlspecialchars($address['city']); ?><br>
                                                            <?php echo htmlspecialchars($address['state'] . ', ' . $address['country']); ?><br>
                                                            <?php echo htmlspecialchars($address['phone']); ?>
                                                        </div>
                                                        <?php if ($address['is_default']): ?>
                                                            <div class="mt-1 text-sm text-primary">Default address</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div id="new-address">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" name="full_name" id="full_name" 
                                              value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="address_line1" class="block text-sm font-medium text-gray-700">Address Line 1</label>
                                        <input type="text" name="address_line1" id="address_line1" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="address_line2" class="block text-sm font-medium text-gray-700">Address Line 2 (Optional)</label>
                                        <input type="text" name="address_line2" id="address_line2" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" name="city" id="city" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" name="postal_code" id="postal_code" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                        <select name="state" id="state" 
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                            <option value="">Select State</option>
                                            <option value="Johor">Johor</option>
                                            <option value="Kedah">Kedah</option>
                                            <option value="Kelantan">Kelantan</option>
                                            <option value="Kuala Lumpur">Kuala Lumpur</option>
                                            <option value="Labuan">Labuan</option>
                                            <option value="Melaka">Melaka</option>
                                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                                            <option value="Pahang">Pahang</option>
                                            <option value="Penang">Penang</option>
                                            <option value="Perak">Perak</option>
                                            <option value="Perlis">Perlis</option>
                                            <option value="Putrajaya">Putrajaya</option>
                                            <option value="Sabah">Sabah</option>
                                            <option value="Sarawak">Sarawak</option>
                                            <option value="Selangor">Selangor</option>
                                            <option value="Terengganu">Terengganu</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                        <input type="text" name="country" id="country" value="Malaysia" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 focus:outline-none focus:ring-primary focus:border-primary" readonly>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="text" name="phone" id="phone" 
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="save_as_default" value="1" class="form-checkbox h-5 w-5 text-primary">
                                            <span class="ml-2">Save as default address</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
                            
                            <div class="space-y-4">
                                <label class="flex items-center p-4 border border-gray-200 rounded">
                                    <input type="radio" name="payment_method" value="credit_card" class="h-5 w-5 text-primary">
                                    <span class="ml-2">Credit/Debit Card</span>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-200 rounded">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="h-5 w-5 text-primary">
                                    <span class="ml-2">Bank Transfer</span>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-200 rounded">
                                    <input type="radio" name="payment_method" value="e_wallet" class="h-5 w-5 text-primary">
                                    <span class="ml-2">E-Wallet (Touch n Go, GrabPay, Boost)</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">Order Notes (Optional)</h2>
                            
                            <div>
                                <textarea name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary"
                                          placeholder="Special instructions for your order"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <a href="cart.php" class="text-primary hover:underline">
                            &larr; Return to Cart
                        </a>
                        
                        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div class="md:w-1/3 mt-8 md:mt-0">
                <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                        
                        <div class="space-y-4">
                            <?php foreach ($cart_items as $item): ?>
                                <div class="flex items-start">
                                    <div class="flex-grow">
                                        <div class="text-sm font-medium"><?php echo htmlspecialchars($item['title']); ?></div>
                                        <div class="text-sm text-gray-500">Qty: <?php echo $item['quantity']; ?></div>
                                    </div>
                                    <div class="text-sm font-medium">
                                        RM<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between mb-2">
                            <span>Subtotal:</span>
                            <span>RM<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="border-t border-gray-200 my-4 pt-4">
                            <div class="flex justify-between font-bold">
                                <span>Total:</span>
                                <span>RM<?php echo number_format($total_price, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle address sections based on checkbox
    const useExistingCheckbox = document.querySelector('input[name="use_existing_address"]');
    if (useExistingCheckbox) {
        useExistingCheckbox.addEventListener('change', function() {
            document.getElementById('existing-address').style.display = this.checked ? 'block' : 'none';
            document.getElementById('new-address').style.display = this.checked ? 'none' : 'block';
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?> 