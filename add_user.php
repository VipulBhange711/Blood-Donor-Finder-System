<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $bloodGroup = $_POST['blood_group'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $role = $_POST['role'];
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($bloodGroup) || empty($city) || empty($phone)) {
        $error = "All fields are required.";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email already exists.";
            } else {
                // Start transaction
                $pdo->beginTransaction();
                
                // Insert user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $hashedPassword, $role]);
                $userId = $pdo->lastInsertId();
                
                // Insert donor information
                $stmt = $pdo->prepare("INSERT INTO donors (user_id, blood_group, city, phone, availability) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $bloodGroup, $city, $phone, $availability]);
                
                $pdo->commit();
                $success = "User created successfully!";
                
                // Clear form
                $_POST = array();
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error creating user: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Blood Donor Finder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-xl font-bold">Blood Donor Finder</a>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="index.php" class="hover:text-gray-200 transition-colors">Home</a>
                        <a href="admin.php" class="hover:text-gray-200 transition-colors">Admin Panel</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-3xl font-bold mb-8 text-gray-900">Add New User</h2>
            
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-6"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-6"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                        <option value="donor" selected>Donor</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                    <select name="blood_group" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                        <option value="">Select Blood Group</option>
                        <option value="A+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'A+') ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'A-') ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'B+') ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'B-') ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'AB+') ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'AB-') ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'O+') ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo (isset($_POST['blood_group']) && $_POST['blood_group'] === 'O-') ? 'selected' : ''; ?>>O-</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="availability" id="availability" <?php echo (isset($_POST['availability']) && $_POST['availability']) ? 'checked' : 'checked'; ?> class="mr-3 h-5 w-5 text-red-600">
                    <label for="availability" class="text-sm font-medium text-gray-700">Available to donate</label>
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white py-3 rounded-lg font-bold hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl">Create User</button>
                    <a href="admin.php" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold hover:bg-gray-300 transition-all text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4">Blood Donor Finder System</h3>
            <p class="text-gray-400">Connecting donors, saving lives</p>
        </div>
    </footer>
</body>
</html>
