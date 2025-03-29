<?php
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Return JSON response for AJAX requests
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'You must be logged in to add items to wishlist']);
        exit;
    }
    
    // Redirect for regular requests
    header('Location: login.php');
    exit;
}

// Process the add to wishlist request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = (int)$_POST['book_id'];
    
    $db = get_db_connection();
    
    // Check if the book exists
    $stmt = $db->prepare("SELECT id FROM books WHERE id = :book_id");
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
    
    // Check if the book is already in the wishlist
    $stmt = $db->prepare("SELECT id FROM wishlist WHERE user_id = :user_id AND book_id = :book_id");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $wishlist_item = $result->fetchArray(SQLITE3_ASSOC);
    
    if ($wishlist_item) {
        // Remove from wishlist if already exists (toggle functionality)
        $stmt = $db->prepare("DELETE FROM wishlist WHERE id = :id");
        $stmt->bindValue(':id', $wishlist_item['id'], SQLITE3_INTEGER);
        $stmt->execute();
        
        // Get book title for activity
        $book_query = $db->prepare("SELECT title FROM books WHERE id = :book_id");
        $book_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $book_result = $book_query->execute();
        $book = $book_result->fetchArray(SQLITE3_ASSOC);
        
        if ($book) {
            record_user_activity(
                $user_id,
                'wishlist_remove',
                "Removed \"{$book['title']}\" from wishlist",
                $book_id
            );
        }
        
        // Return success response with removed status
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Item removed from wishlist', 'status' => 'removed']);
            exit;
        }
        
        // Redirect for regular form submission
        header('Location: catalogue.php?success=removed_from_wishlist');
        exit;
    } else {
        // Add new item to wishlist
        $stmt = $db->prepare("INSERT INTO wishlist (user_id, book_id) VALUES (:user_id, :book_id)");
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $stmt->execute();
        
        // Get book title for activity
        $book_query = $db->prepare("SELECT title FROM books WHERE id = :book_id");
        $book_query->bindValue(':book_id', $book_id, SQLITE3_INTEGER);
        $book_result = $book_query->execute();
        $book = $book_result->fetchArray(SQLITE3_ASSOC);
        
        if ($book) {
            record_user_activity(
                $user_id,
                'wishlist_add',
                "Added \"{$book['title']}\" to wishlist",
                $book_id
            );
        }
        
        // Return success response with added status
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Item added to wishlist', 'status' => 'added']);
            exit;
        }
        
        // Redirect for regular form submission
        header('Location: catalogue.php?success=added_to_wishlist');
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