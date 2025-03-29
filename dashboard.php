<?php
$page_title = 'My Account';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$db = get_db_connection();

$query = $db->prepare("SELECT username, email, created_at FROM users WHERE id = :user_id");
$query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$result = $query->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

// Set default values in case user data is not found
$username = 'Unknown';
$email = 'Unknown';
$registration_date = 'Unknown';

// Format date only if we have valid user data
if ($user && is_array($user)) {
    $username = $user['username'] ?? 'Unknown';
    $email = $user['email'] ?? 'Unknown';
    $registration_date = isset($user['created_at']) && !empty($user['created_at']) ? 
        date('F j, Y', strtotime($user['created_at'])) : 'Unknown';
}
?>

<!-- User Dashboard -->
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
                                <a href="dashboard.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">Account Dashboard</a>
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
                                <a href="change_password.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Change Password</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded text-red-600">Logout</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="md:w-3/4 md:pl-8">
                        <h2 class="text-2xl font-bold mb-6">Account Information</h2>
                        
                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold mb-3">Account Details</h3>
                                    <p class="mb-1"><span class="font-medium">Username:</span> <?php echo htmlspecialchars($username); ?></p>
                                    <p class="mb-1"><span class="font-medium">Email:</span> <?php echo htmlspecialchars($email); ?></p>
                                    <p><span class="font-medium">Member Since:</span> <?php echo $registration_date; ?></p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold mb-3">Recent Activity</h3>
                                    <p class="text-gray-600">No recent activity to display.</p>
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-2xl font-bold mb-6">Quick Links</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="catalogue.php" class="bg-white p-4 border rounded-lg flex items-center hover:shadow-md transition-shadow">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Browse Books</h3>
                                    <p class="text-sm text-gray-600">Explore our collection of bonsai books</p>
                                </div>
                            </a>
                            
                            <a href="cart.php" class="bg-white p-4 border rounded-lg flex items-center hover:shadow-md transition-shadow">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">My Cart</h3>
                                    <p class="text-sm text-gray-600">View your shopping cart</p>
                                </div>
                            </a>
                            
                            <a href="wishlist.php" class="bg-white p-4 border rounded-lg flex items-center hover:shadow-md transition-shadow">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">My Wishlist</h3>
                                    <p class="text-sm text-gray-600">View your saved items</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 