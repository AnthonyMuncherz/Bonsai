<?php
$page_title = 'Admin Panel';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// Get all users
$db = get_db_connection();
$query = $db->query("SELECT id, username, email, created_at, is_admin FROM users ORDER BY id");
$users = [];

while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
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

<!-- Admin Panel -->
<section class="py-16">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary text-white p-6">
                <h1 class="text-3xl font-bold">Admin Panel</h1>
            </div>
            
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <!-- Sidebar -->
                    <div class="md:w-1/4 mb-8 md:mb-0">
                        <ul class="space-y-2">
                            <li>
                                <a href="#" class="block px-4 py-2 bg-gray-100 rounded font-medium">User Management</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">Book Inventory</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">Orders</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 rounded">Site Settings</a>
                            </li>
                            <li>
                                <a href="dashboard.php" class="block px-4 py-2 hover:bg-gray-100 rounded">My Account</a>
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
                        
                        <!-- User Table -->
                        <div class="bg-white border rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
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
                                                    <?php if ($user['is_admin'] == 1): ?>
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Admin</span>
                                                    <?php else: ?>
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">User</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 