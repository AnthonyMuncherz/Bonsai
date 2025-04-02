<?php
$page_title = 'Login';
require_once 'includes/db.php';

// Initialize variables
$username = '';
$error = '';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
    } else {
        // Authenticate user
        $db = get_db_connection();
        
        $query = $db->prepare("SELECT id, username, email, password, is_admin FROM users WHERE username = :username OR email = :email");
        $query->bindValue(':username', $username, SQLITE3_TEXT);
        $query->bindValue(':email', $username, SQLITE3_TEXT); // Allow login with email too
        $result = $query->execute();
        
        $user = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Redirect to dashboard page instead of home page
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
}

// Include header AFTER all possible redirects
require_once 'includes/header.php';
?>

<!-- Login Form -->
<section class="py-16">
    <div class="container mx-auto max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-6 text-center">Login to Your Account</h1>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-4">
                <div>
                    <label for="username" class="block text-gray-700 font-medium mb-2">Username or Email</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-primary">
                        <label for="remember" class="ml-2 text-gray-700">Remember me</label>
                    </div>
                    <a href="#" class="text-primary text-sm font-medium">Forgot password?</a>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full btn btn-primary py-2">Login</button>
                </div>
                
                <div class="text-center mt-4">
                    <p>Don't have an account? <a href="register.php" class="text-primary font-medium">Register here</a></p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 