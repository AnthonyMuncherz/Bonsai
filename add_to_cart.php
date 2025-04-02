<?php
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Return JSON response for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'You must be logged in to add items to cart']);
        exit;
    }
    
    // Redirect for regular requests
    header('Location: login.php');
    exit;
}

// Process the add to cart request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = (int)$_POST['book_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Validate quantity
    if ($quantity < 1) $quantity = 1;
    
    $db = get_db_connection();
    
    // Check if the book exists and has enough stock
    $stmt = $db->prepare("SELECT stock FROM books WHERE id = :book_id");
    $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $book = $result->fetchArray(SQLITE3_ASSOC);
    
    if (!$book) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Book not found']);
            exit;
        }
        
        header('Location: catalogue.php?error=book_not_found');
        exit;
    }
    
    if ($book['stock'] < $quantity) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
            exit;
        }
        
        header('Location: catalogue.php?error=not_enough_stock&book_id=' . $book_id);
        exit;
    }
    
    // Check if the book is already in the cart
    $stmt = $db->prepare("SELECT id, quantity FROM cart WHERE user_id = :user_id AND book_id = :book_id");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $cart_item = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($cart_item) {
        // Update quantity if item already exists in cart
        $new_quantity = $cart_item['quantity'] + $quantity;
        
        $stmt = $db->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
        $stmt->bindValue(':quantity', $new_quantity, SQLITE3_INTEGER);
        $stmt->bindValue(':id', $cart_item['id'], SQLITE3_INTEGER);
        $stmt->execute();
        
        // Record activity
        $book_query = $db->prepare("SELECT title FROM books WHERE id = :book_id");
        $book_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $book_result = $book_query->execute();
        $book = $book_result->fetchArray(SQLITE3_ASSOC);
        
        if ($book) {
            record_user_activity(
                $user_id, 
                'cart_update', 
                "Updated quantity of \"{$book['title']}\" in cart to {$new_quantity}",
                $book_id
            );
        }
        
        // Redirect back
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            // Get total items in cart for AJAX response
            $stmt = $db->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $total = $result->fetchArray(SQLITE3_ASSOC)['total'];
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Item added to cart', 'total_items' => $total]);
            exit;
        }
        
        // Redirect for regular form submission
        header('Location: cart.php?success=added');
        exit;
    } else {
        // Add new item to cart
        $stmt = $db->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (:user_id, :book_id, :quantity)");
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $stmt->bindValue(':quantity', $quantity, SQLITE3_INTEGER);
        $stmt->execute();
        
        // Record activity
        $book_query = $db->prepare("SELECT title FROM books WHERE id = :book_id");
        $book_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $book_result = $book_query->execute();
        $book = $book_result->fetchArray(SQLITE3_ASSOC);
        
        if ($book) {
            record_user_activity(
                $user_id, 
                'cart_add', 
                "Added \"{$book['title']}\" to cart",
                $book_id
            );
        }
        
        // Redirect back
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            // Get total items in cart for AJAX response
            $stmt = $db->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $total = $result->fetchArray(SQLITE3_ASSOC)['total'];
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Item added to cart', 'total_items' => $total]);
            exit;
        }
        
        // Redirect for regular form submission
        header('Location: cart.php?success=added');
        exit;
    }
} else {
    // Invalid request
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    header('Location: catalogue.php');
    exit;
} 