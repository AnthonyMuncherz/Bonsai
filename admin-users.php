<?php
$page_title = 'User Management';
require_once 'includes/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

// Initialize database connection
$db = get_db_connection();

// AJAX endpoint for deleting users
if (isset($_POST['ajax_delete_user']) && isset($_POST['user_id'])) {
    $response = ['success' => false, 'message' => ''];
    
    $user_id = $_POST['user_id'];
    
    // Don't allow deleting yourself
    if ($user_id == $_SESSION['user_id']) {
        $response['message'] = 'You cannot delete your own account.';
        echo json_encode($response);
        exit;
    }
    
    try {
        $delete_query = $db->prepare("DELETE FROM users WHERE id = :user_id");
        $delete_query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $delete_query->execute();
        
        // Add activity log
        $activity = "Admin deleted user #" . $user_id;
        $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :target_user)");
        $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
        $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
        $log_query->bindValue(':target_user', $user_id, SQLITE3_INTEGER);
        $log_query->execute();
        
        $response['success'] = true;
        $response['message'] = 'User deleted successfully';
    } catch (Exception $e) {
        $response['message'] = 'Error deleting user: ' . $e->getMessage();
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle user deletion if submitted (non-AJAX fallback)
if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    // Don't allow deleting yourself
    if ($user_id != $_SESSION['user_id']) {
        $delete_query = $db->prepare("DELETE FROM users WHERE id = :user_id");
        $delete_query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $delete_query->execute();
        
        // Add activity log
        $activity = "Admin deleted user #" . $user_id;
        $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :target_user)");
        $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
        $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
        $log_query->bindValue(':target_user', $user_id, SQLITE3_INTEGER);
        $log_query->execute();
        
        // Redirect to refresh the page and avoid resubmission
        header('Location: admin-users.php');
        exit;
    }
}

// AJAX endpoint for editing users
if (isset($_POST['ajax_edit_user']) && isset($_POST['user_id'])) {
    $response = ['success' => false, 'message' => ''];
    
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    try {
        // Update user information
        $update_query = $db->prepare("UPDATE users SET username = :username, email = :email, is_admin = :is_admin WHERE id = :user_id");
        $update_query->bindValue(':username', $username, SQLITE3_TEXT);
        $update_query->bindValue(':email', $email, SQLITE3_TEXT);
        $update_query->bindValue(':is_admin', $is_admin, SQLITE3_INTEGER);
        $update_query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $update_query->execute();
        
        // Add activity log
        $activity = "Admin updated details for user #" . $user_id;
        $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :target_user)");
        $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
        $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
        $log_query->bindValue(':target_user', $user_id, SQLITE3_INTEGER);
        $log_query->execute();
        
        $response['success'] = true;
        $response['message'] = 'User updated successfully';
        $response['user'] = [
            'id' => $user_id,
            'username' => $username,
            'email' => $email,
            'is_admin' => $is_admin
        ];
    } catch (Exception $e) {
        $response['message'] = 'Error updating user: ' . $e->getMessage();
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Now include the header after all potential redirects
require_once 'includes/header.php';

// Handle role updates for users if submitted 
// (This can't use header redirect as it's after the header is included)
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

// Add traditional edit fallback capability
if (isset($_POST['edit_user']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    // Update user information
    $update_query = $db->prepare("UPDATE users SET username = :username, email = :email, is_admin = :is_admin WHERE id = :user_id");
    $update_query->bindValue(':username', $username, SQLITE3_TEXT);
    $update_query->bindValue(':email', $email, SQLITE3_TEXT);
    $update_query->bindValue(':is_admin', $is_admin, SQLITE3_INTEGER);
    $update_query->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $update_query->execute();
    
    // Add activity log
    $activity = "Admin updated details for user #" . $user_id;
    $log_query = $db->prepare("INSERT INTO user_activities (user_id, activity_type, activity_description, related_id) VALUES (:user_id, 'admin_action', :activity, :target_user)");
    $log_query->bindValue(':user_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $log_query->bindValue(':activity', $activity, SQLITE3_TEXT);
    $log_query->bindValue(':target_user', $user_id, SQLITE3_INTEGER);
    $log_query->execute();
    
    // Redirect to refresh the page and avoid resubmission
    header('Location: admin-users.php');
    exit;
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
                                                        <button type="button" 
                                                                class="text-indigo-600 hover:text-indigo-900 mr-3 edit-user-btn" 
                                                                data-user-id="<?php echo $user['id']; ?>"
                                                                data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                                                data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                                                data-is-admin="<?php echo $user['is_admin']; ?>">
                                                            Edit
                                                        </button>
                                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                            <button type="button" 
                                                                    class="text-red-600 hover:text-red-900 delete-user-btn"
                                                                    data-user-id="<?php echo $user['id']; ?>"
                                                                    data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                                                Delete
                                                            </button>
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

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden overflow-y-auto">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 overflow-hidden my-auto">
        <div class="bg-primary px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Edit User</h3>
            <button type="button" id="closeModal" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="edit-user-form" class="p-6">
            <input type="hidden" name="user_id" id="edit_user_id">
            
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="username" id="edit_username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="edit_email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_admin" id="edit_is_admin" class="form-checkbox h-5 w-5 text-primary">
                    <span class="ml-2 text-gray-700">Admin privileges</span>
                </label>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden form for delete fallback -->
<form id="delete-user-form" action="admin-users.php" method="POST" style="display: none;">
    <input type="hidden" name="user_id" id="delete_user_id">
    <input type="hidden" name="delete_user" value="1">
</form>

<!-- Hidden form for edit fallback -->
<form id="edit-user-fallback" action="admin-users.php" method="POST" style="display: none;">
    <input type="hidden" name="user_id" id="fallback_user_id">
    <input type="hidden" name="username" id="fallback_username">
    <input type="hidden" name="email" id="fallback_email">
    <input type="hidden" name="is_admin" id="fallback_is_admin">
    <input type="hidden" name="edit_user" value="1">
</form>

<!-- JavaScript for Modal and Delete -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editUserModal');
        const editButtons = document.querySelectorAll('.edit-user-btn');
        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        const closeModal = document.getElementById('closeModal');
        const cancelEdit = document.getElementById('cancelEdit');
        const deleteForm = document.getElementById('delete-user-form');
        const deleteUserId = document.getElementById('delete_user_id');
        const editForm = document.getElementById('edit-user-form');
        const editFallbackForm = document.getElementById('edit-user-fallback');
        
        // Handle edit form submission
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userId = document.getElementById('edit_user_id').value;
            const username = document.getElementById('edit_username').value;
            const email = document.getElementById('edit_email').value;
            const isAdmin = document.getElementById('edit_is_admin').checked ? 1 : 0;
            
            // Check if fetch is supported
            if (typeof fetch !== 'undefined') {
                // Show loading indicator
                const submitButton = editForm.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Saving...';
                submitButton.disabled = true;
                
                // Create form data
                const formData = new FormData();
                formData.append('ajax_edit_user', '1');
                formData.append('user_id', userId);
                formData.append('username', username);
                formData.append('email', email);
                if (isAdmin) {
                    formData.append('is_admin', '1');
                }
                
                // Send AJAX request
                fetch('admin-users.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                    
                    if (data.success) {
                        // Update the table row with new information
                        updateUserRow(data.user);
                        
                        // Close the modal
                        closeModalFunction();
                        
                        // Show success message
                        showNotification('User updated successfully', 'success');
                    } else {
                        // Show error message
                        showNotification(data.message || 'Error updating user', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                    showNotification('An error occurred while updating the user', 'error');
                });
            } else {
                // Fallback for browsers without fetch support
                document.getElementById('fallback_user_id').value = userId;
                document.getElementById('fallback_username').value = username;
                document.getElementById('fallback_email').value = email;
                document.getElementById('fallback_is_admin').value = isAdmin;
                editFallbackForm.submit();
            }
        });
        
        // Function to update user row in the table
        function updateUserRow(user) {
            // Find the user row
            const rows = document.querySelectorAll('tbody tr');
            let userRow = null;
            
            rows.forEach(row => {
                const idCell = row.querySelector('td:first-child');
                if (idCell && idCell.textContent === user.id.toString()) {
                    userRow = row;
                }
            });
            
            if (userRow) {
                // Update username
                const usernameCell = userRow.querySelector('td:nth-child(2) div');
                if (usernameCell) {
                    usernameCell.textContent = user.username;
                }
                
                // Update email
                const emailCell = userRow.querySelector('td:nth-child(3) div');
                if (emailCell) {
                    emailCell.textContent = user.email;
                }
                
                // Update role select
                const roleSelect = userRow.querySelector('select[name="is_admin"]');
                if (roleSelect) {
                    roleSelect.value = user.is_admin;
                }
                
                // Update edit button data attributes
                const editButton = userRow.querySelector('.edit-user-btn');
                if (editButton) {
                    editButton.dataset.username = user.username;
                    editButton.dataset.email = user.email;
                    editButton.dataset.isAdmin = user.is_admin;
                }
                
                // Update delete button data attribute
                const deleteButton = userRow.querySelector('.delete-user-btn');
                if (deleteButton) {
                    deleteButton.dataset.username = user.username;
                }
                
                // Update user counts
                updateUserCounts();
            }
        }
        
        // Handle delete button clicks
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const username = this.dataset.username;
                
                if (confirm(`Are you sure you want to delete user "${username}"? This action cannot be undone.`)) {
                    // Check if fetch is supported
                    if (typeof fetch !== 'undefined') {
                        // Show loading indicator
                        const row = this.closest('tr');
                        row.style.opacity = '0.5';
                        
                        // Create form data
                        const formData = new FormData();
                        formData.append('ajax_delete_user', '1');
                        formData.append('user_id', userId);
                        
                        // Send AJAX request
                        fetch('admin-users.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the row from the table
                                row.remove();
                                
                                // Update the user count
                                updateUserCounts();
                                
                                // Show success message
                                showNotification('User deleted successfully', 'success');
                            } else {
                                // Show error message
                                showNotification(data.message || 'Error deleting user', 'error');
                                row.style.opacity = '1';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('An error occurred while deleting the user', 'error');
                            row.style.opacity = '1';
                        });
                    } else {
                        // Fallback for browsers without fetch support
                        deleteUserId.value = userId;
                        deleteForm.submit();
                    }
                }
            });
        });
        
        // Function to update user counts
        function updateUserCounts() {
            const userRows = document.querySelectorAll('tbody tr');
            const totalUsersElement = document.querySelector('.text-blue-600');
            const regularUsersElement = document.querySelector('.text-green-600');
            const adminUsersElement = document.querySelector('.text-purple-600');
            
            let totalUsers = userRows.length;
            let adminUsers = 0;
            
            userRows.forEach(row => {
                const roleSelect = row.querySelector('select[name="is_admin"]');
                if (roleSelect && roleSelect.value === '1') {
                    adminUsers++;
                }
            });
            
            const regularUsers = totalUsers - adminUsers;
            
            if (totalUsersElement) totalUsersElement.textContent = totalUsers;
            if (regularUsersElement) regularUsersElement.textContent = regularUsers;
            if (adminUsersElement) adminUsersElement.textContent = adminUsers;
        }
        
        // Function to show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.textContent = message;
            
            // Add to document
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 500);
            }, 3000);
        }
        
        // Open modal with user data
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const username = this.dataset.username;
                const email = this.dataset.email;
                const isAdmin = this.dataset.isAdmin === '1';
                
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_username').value = username;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_is_admin').checked = isAdmin;
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
                
                // Ensure modal is centered vertically
                setTimeout(() => {
                    const modalHeight = modal.querySelector('.bg-white').offsetHeight;
                    const windowHeight = window.innerHeight;
                    if (modalHeight < windowHeight) {
                        modal.querySelector('.bg-white').style.marginTop = Math.max(0, (windowHeight - modalHeight) / 2) + 'px';
                    } else {
                        modal.querySelector('.bg-white').style.marginTop = '2rem';
                    }
                }, 10);
            });
        });
        
        // Close modal functions
        function closeModalFunction() {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        closeModal.addEventListener('click', closeModalFunction);
        cancelEdit.addEventListener('click', closeModalFunction);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModalFunction();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (!modal.classList.contains('hidden')) {
                const modalHeight = modal.querySelector('.bg-white').offsetHeight;
                const windowHeight = window.innerHeight;
                if (modalHeight < windowHeight) {
                    modal.querySelector('.bg-white').style.marginTop = Math.max(0, (windowHeight - modalHeight) / 2) + 'px';
                } else {
                    modal.querySelector('.bg-white').style.marginTop = '2rem';
                }
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?> 