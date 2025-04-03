<?php
$page_title = 'User Management';
require_once 'includes/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// Include header after redirects
require_once 'includes/header.php';

// Initialize database connection
$db = get_db_connection();

// Handle role updates for users if submitted
if (isset($_POST['update_user_role']) && isset($_POST['user_id']) && isset($_POST['is_admin'])) {
    $user_id = $_POST['user_id'];
    $is_admin = $_POST['is_admin'];
    
    $update_query = $db->prepare("UPDATE users SET is_admin = :is_admin WHERE id = :user_id");
    $update_query->bindValue(':is_admin', $is_admin, SQLITE3_INTEGER);
    $update_query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $update_query->execute();
    
    // Add activity log
    $role_name = ($is_admin == 1) ? 'Admin' : 'User';
    $activity = "Admin changed user #" . $user_id . " role to " . $role_name;
    $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :target_user)");
    $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
    $log_query->bindValue(':target_user', $user_id, SQLITE3_INTEGER);
    $log_query->execute();
}

// Handle user search if submitted
$search_term = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $_GET['search'];
    $user_query = $db->prepare("SELECT id, username, email, created_at, is_admin FROM users 
                               WHERE username LIKE :search OR email LIKE :search
                               ORDER BY id");
    $user_query->bindValue(':search', "%$search_term%", SQLITE3_TEXT);
    $result = $user_query->execute();
} else {
    // Get all users
    $result = $db->query("SELECT id, username, email, created_at, is_admin FROM users ORDER BY id");
}

$users = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $users[] = $row;
}

// Count users
$total_users = count($users);
$admin_count = 0;
foreach ($users as $user) {
    if ($user['is_admin'] == 1) {
        $admin_count++;
    }
}
?>

<!-- Admin User Management -->
<section class="py-16">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">User Management</h1>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar -->
                    <div class="md:w-1/4 mb-8 md:mb-0">
                        <ul class="space-y-2">
                            <li>
                                <a href="admin-dashboard.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Dashboard Overview</a>
                            </li>
                            <li>
                                <a href="admin-orders.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Order Management</a>
                            </li>
                            <li>
                                <a href="admin-users.php" class="block px-4 py-2 bg-gray-100 rounded font-medium">User Management</a>
                            </li>
                            <li>
                                <a href="admin-books.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Book Inventory</a>
                            </li>
                            <li>
                                <a href="catalogue.php" class="block px-4 py-2 hover:bg-gray-100 rounded">Book Catalogue</a>
                            </li>
                            <li>
                                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100 rounded text-red-600">Logout</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Main Content -->
                    <div class="md:w-3/4 md:pl-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">User Management</h2>
                            <a href="#" class="btn btn-primary">Add New User</a>
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="font-semibold text-blue-800">Total Users</h3>
                                <p class="text-3xl font-bold text-blue-600"><?php echo $total_users; ?></p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <h3 class="font-semibold text-green-800">Regular Users</h3>
                                <p class="text-3xl font-bold text-green-600"><?php echo $total_users - $admin_count; ?></p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                                <h3 class="font-semibold text-purple-800">Admin Users</h3>
                                <p class="text-3xl font-bold text-purple-600"><?php echo $admin_count; ?></p>
                            </div>
                        </div>
                        
                        <!-- Search Form -->
                        <div class="mb-6">
                            <form action="admin-users.php" method="GET" class="flex">
                                <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" 
                                       placeholder="Search by username or email" 
                                       class="flex-grow rounded-l border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r hover:bg-primary-dark">
                                    Search
                                </button>
                            </form>
                        </div>
                        
                        <!-- User Table -->
                        <div class="bg-white border rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (empty($users)): ?>
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No users found</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $user['id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <form action="admin-users.php" method="POST">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="update_user_role" value="1">
                                                            <select name="is_admin" class="rounded border border-gray-300 text-sm p-1" onchange="this.form.submit()">
                                                                <option value="0" <?php echo $user['is_admin'] == 0 ? 'selected' : ''; ?>>User</option>
                                                                <option value="1" <?php echo $user['is_admin'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- User Activity -->
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4">Recent User Activities</h3>
                            <?php
                            // Get recent activities
                            $activity_query = $db->query("
                                SELECT ua.*, u.username 
                                FROM user_activities ua
                                JOIN users u ON ua.user_id = u.id
                                ORDER BY ua.created_at DESC 
                                LIMIT 10
                            ");
                            
                            $activities = [];
                            while ($row = $activity_query->fetchArray(SQLITE3_ASSOC)) {
                                $activities[] = $row;
                            }
                            ?>
                            
                            <?php if (empty($activities)): ?>
                                <p class="text-gray-500">No recent activities found.</p>
                            <?php else: ?>
                                <div class="bg-white border rounded-lg overflow-hidden">
                                    <ul class="divide-y divide-gray-200">
                                        <?php foreach ($activities as $activity): ?>
                                            <li class="p-4 hover:bg-gray-50">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0 mt-1">
                                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3 flex-1">
                                                        <div class="text-sm text-gray-800">
                                                            <span class="font-medium"><?php echo htmlspecialchars($activity['username']); ?></span> - 
                                                            <?php echo htmlspecialchars($activity['activity_description']); ?>
                                                        </div>
                                                        <div class="mt-1 text-xs text-gray-500">
                                                            <?php echo date('M j, Y, g:i a', strtotime($activity['created_at'])); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 