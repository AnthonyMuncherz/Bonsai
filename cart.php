<?php
$page_title = 'Shopping Cart';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle remove from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $item_id = (int)$_GET['remove'];
    $user_id = $_SESSION['user_id'];
    
    $db = get_db_connection();
    
    // Get book information before deletion for activity log
    $stmt = $db->prepare("
        SELECT c.book_id, b.title 
        FROM cart c 
        JOIN books b ON c.book_id = b.id 
        WHERE c.id = :id AND c.user_id = :user_id
    ");
    $stmt->bindValue(':id', $item_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $book = $result->fetchArray(SQLITE3_ASSOC);
    
    // Delete the item from cart
    $stmt = $db->prepare("DELETE FROM cart WHERE id = :id AND user_id = :user_id");
    $stmt->bindValue(':id', $item_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->execute();
    
    // Record activity if book was found
    if ($book) {
        record_user_activity(
            $user_id,
            'cart_remove',
            "Removed \"{$book['title']}\" from cart",
            $book['book_id']
        );
    }
    
    header('Location: cart.php?success=removed');
    exit;
}

// Handle update quantity
if (isset($_POST['update_cart']) && isset($_POST['quantities'])) {
    $user_id = $_SESSION['user_id'];
    $db = get_db_connection();
    
    foreach ($_POST['quantities'] as $item_id => $quantity) {
        $item_id = (int)$item_id;
        $quantity = (int)$quantity;
        
        if ($quantity < 1) continue;
        
        // Get the book stock
        $stmt = $db->prepare("
            SELECT b.stock
            FROM cart c 
            JOIN books b ON c.book_id = b.id
            WHERE c.id = :item_id AND c.user_id = :user_id
        ");
        $stmt->bindValue(':item_id', $item_id, SQLITE3_INTEGER);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $book = $result->fetchArray(SQLITE3_ASSOC);
        
        if (!$book) continue;
        
        // Make sure we don't exceed stock
        if ($quantity > $book['stock']) {
            $quantity = $book['stock'];
        }
        
        $stmt = $db->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id AND user_id = :user_id");
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $item_id, SQLITE3_INTEGER);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->execute();
    }
    
    header('Location: cart.php?success=updated');
    exit;
}

// Get cart items
$user_id = $_SESSION['user_id'];
$db = get_db_connection();

$query = "
    SELECT c.id, c.quantity, c.book_id, b.title, b.author, b.price, b.image, b.stock
    FROM cart c
    JOIN books b ON c.book_id = b.id
    WHERE c.user_id = :user_id
    ORDER BY c.created_at DESC
";

$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$cart_items = [];
$total_price = 0;
$total_items = 0;

while ($item = $result->fetchArray(SQLITE3_ASSOC)) {
    $cart_items[] = $item;
    $total_price += $item['price'] * $item['quantity'];
    $total_items += $item['quantity'];
}

// Include header after redirects
require_once 'includes/header.php';
?>

<!-- Cart Header -->
<section class="pt-16 pb-8 bg-gray-50">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-center">Shopping Cart</h1>
        <p class="text-center text-gray-600 mt-2">Review your selected items</p>
    </div>
</section>

<!-- Cart Content -->
<section class="py-12">
    <div class="container mx-auto max-w-6xl">
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php if ($_GET['success'] === 'added'): ?>
                    <p>Item added to cart successfully!</p>
                <?php elseif ($_GET['success'] === 'removed'): ?>
                    <p>Item removed from cart successfully!</p>
                <?php elseif ($_GET['success'] === 'updated'): ?>
                    <p>Cart updated successfully!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Looks like you haven't added any books to your cart yet.</p>
                <a href="catalogue.php" class="bg-primary text-white px-6 py-3 rounded-md hover:bg-primary-dark transition">
                    Browse Books
                </a>
            </div>
        <?php else: ?>
            <form method="POST" action="cart.php">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($cart_items as $item): ?>
                                <tr class="cursor-pointer hover:bg-gray-50" onclick="window.location='book.php?id=<?php echo $item['book_id']; ?>'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-16 w-16 bg-gray-100">
                                                <?php if (!empty($item['image']) && file_exists('Images/books/' . $item['image'])): ?>
                                                    <img src="Images/books/<?php echo htmlspecialchars($item['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                                                         class="h-16 w-16 object-cover">
                                                <?php else: ?>
                                                    <div class="h-16 w-16 flex items-center justify-center bg-gray-200">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                                        <input type="number" name="quantities[<?php echo $item['id']; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" 
                                               class="w-16 border border-gray-300 rounded px-2 py-1"
                                               onclick="event.stopPropagation()">
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php echo $item['stock']; ?> available
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            RM<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="cart.php?remove=<?php echo $item['id']; ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="event.stopPropagation(); return confirm('Are you sure you want to remove this item?');">
                                            Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-start">
                    <div>
                        <button type="submit" name="update_cart" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            Update Cart
                        </button>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md w-72">
                        <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                        <div class="flex justify-between mb-2">
                            <span>Items (<?php echo $total_items; ?>):</span>
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
                        <a href="checkout.php" class="block w-full bg-primary text-white py-3 px-4 rounded-md hover:bg-primary-dark transition mt-4 text-center">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 