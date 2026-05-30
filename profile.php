<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'donor') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT d.*, u.name, u.email FROM donors d JOIN users u ON d.user_id = u.id WHERE d.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$donor = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $bloodGroup = $_POST['blood_group'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?")->execute([$name, $email, $_SESSION['user_id']]);
    $pdo->prepare("UPDATE donors SET blood_group = ?, city = ?, phone = ?, availability = ? WHERE user_id = ?")->execute([$bloodGroup, $city, $phone, $availability, $_SESSION['user_id']]);

    $_SESSION['user_name'] = $name;
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Blood Donor Finder</title>
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
                        <a href="about.php" class="hover:text-gray-200 transition-colors">About</a>
                        <a href="features.php" class="hover:text-gray-200 transition-colors">Features</a>
                        <a href="gallery.php" class="hover:text-gray-200 transition-colors">Gallery</a>
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
            <h2 class="text-3xl font-bold mb-8 text-gray-900">My Profile</h2>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($donor['name']); ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($donor['email']); ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                    <select name="blood_group" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                        <option value="A+" <?php echo $donor['blood_group'] === 'A+' ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo $donor['blood_group'] === 'A-' ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo $donor['blood_group'] === 'B+' ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo $donor['blood_group'] === 'B-' ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo $donor['blood_group'] === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo $donor['blood_group'] === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo $donor['blood_group'] === 'O+' ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo $donor['blood_group'] === 'O-' ? 'selected' : ''; ?>>O-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" value="<?php echo htmlspecialchars($donor['city']); ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($donor['phone']); ?>" required class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="availability" id="availability" <?php echo $donor['availability'] ? 'checked' : ''; ?> class="mr-3 h-5 w-5 text-red-600">
                    <label for="availability" class="text-sm font-medium text-gray-700">Available to donate</label>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl">Update Profile</button>
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
