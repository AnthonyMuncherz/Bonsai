<?php
$page_title = 'Edit Account';
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
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    // Validate input
    if (empty($username)) {
        $error_message = 'Username is required';
    } elseif (empty($email)) {
        $error_message = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format';
    } else {
        // Check if email exists for another user
        $check_email = $db->prepare("SELECT id FROM users WHERE email = :email AND id != :user_id");
        $check_email->bindValue(':email', $email, SQLITE3_TEXT);
        $check_email->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $check_email->execute();
        $existing_user = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($existing_user) {
            $error_message = 'Email is already in use by another account';
        } else {
            // Update user information
            $update = $db->prepare("UPDATE users SET username = :username, email = :email WHERE id = :user_id");
            $update->bindValue(':username', $username, SQLITE3_TEXT);
            $update->bindValue(':email', $email, SQLITE3_TEXT);
            $update->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $result = $update->execute();
            
            if ($result) {
                $success_message = 'Your account information has been updated successfully';
                // Update session
                $_SESSION['username'] = $username;
            } else {
                $error_message = 'Failed to update account information';
            }
        }
    }
}

// Get current user data
$query = $db->prepare("SELECT username, email FROM users WHERE id = :user_id");
$query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $query->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

// Include header after processing to avoid header issues
require_once 'includes/header.php';
?>

<!-- Edit Account Section -->
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
                                <a href="edit_account.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">Edit Account</a>
                            </li>
                            <li>
                                <a href="change_password.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Change Password</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded text-red-600">Logout</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="md:w-3/4 md:pl-8">
                        <h2 class="text-2xl font-bold mb-6">Edit Account Information</h2>
                        
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
                            <form method="POST" action="edit_account.php">
                                <div class="mb-4">
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                </div>
                                
                                <div class="mb-6">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary">
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-primary-dark transition">
                                        Save Changes
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