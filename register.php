<?php
$page_title = 'Register';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Initialize variables
$username = $email = '';
$error = '';
$success = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if user exists
        if (user_exists($email, $username)) {
            $error = 'Username or email already exists';
        } else {
            // Create new user
            $db = get_db_connection();
            
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $query->bindValue(':username', $username, SQLITE3_TEXT);
            $query->bindValue(':email', $email, SQLITE3_TEXT); 
            $query->bindValue(':password', $hashed_password, SQLITE3_TEXT);
            
            if ($query->execute()) {
                $success = true;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!-- Registration Form -->
<section class="py-16">
    <div class="container mx-auto max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-6 text-center">Create an Account</h1>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <p>Registration successful! <a href="login.php" class="text-primary font-bold">Login here</a></p>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-4">
                    <div>
                        <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <p class="text-sm text-gray-500 mt-1">Must be at least 6 characters</p>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" class="w-full btn btn-primary py-2">Register</button>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p>Already have an account? <a href="login.php" class="text-primary font-medium">Login here</a></p>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 