<?php
$page_title = 'Change Password';
require_once 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$db = get_db_connection();

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($current_password)) {
        $error_message = 'Current password is required';
    } elseif (empty($new_password)) {
        $error_message = 'New password is required';
    } elseif (strlen($new_password) < 8) {
        $error_message = 'New password must be at least 8 characters long';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'New passwords do not match';
    } else {
        // Check current password
        $query = $db->prepare("SELECT password FROM users WHERE id = :user_id");
        $query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $query->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($user && password_verify($current_password, $user['password'])) {
            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $db->prepare("UPDATE users SET password = :password WHERE id = :user_id");
            $update->bindValue(':password', $hashed_password, SQLITE3_TEXT);
            $update->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $result = $update->execute();
            
            if ($result) {
                $success_message = 'Your password has been updated successfully';
                
                // Record activity
                record_user_activity(
                    $user_id,
                    'password_change',
                    "Changed account password",
                    null
                );
            } else {
                $error_message = 'Failed to update password';
            }
        } else {
            $error_message = 'Current password is incorrect';
        }
    }
}

// Include header after processing
require_once 'includes/header.php';
?>

<!-- Change Password Section -->
<section class="py-16">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">My Account</h1>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar -->
                    <div class="md:w-1/4 mb-8 md:mb-0">
                        <ul class="space-y-2">
                            <li>
                                <a href="dashboard.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Account Dashboard</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">My Orders</a>
                            </li>
                            <li>
                                <a href="wishlist.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Wish List</a>
                            </li>
                            <li>
                                <a href="edit_account.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Edit Account</a>
                            </li>
                            <li>
                                <a href="change_password.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">Change Password</a>
                            </li>
                            <li>
                                <a href="activity_history.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Activity History</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded text-red-600">Logout</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="md:w-3/4 md:pl-8">
                        <h2 class="text-2xl font-bold mb-6">Change Password</h2>
                        
                        <?php if (!empty($success_message)): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($error_message)): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <form method="POST" action="change_password.php">
                                <div class="mb-4">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <input type="password" id="new_password" name="new_password" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                    <p class="text-sm text-gray-600 mt-1">Password must be at least 8 characters long</p>
                                </div>
                                
                                <div class="mb-6">
                                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark transition">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 