<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Blood Donor Finder System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="flex items-center space-x-2">
                        <img src="images/logo.png" alt="Blood Donor Finder Logo" class="h-10 w-10 rounded-full object-cover">
                        <span class="text-xl font-bold">Blood Donor Finder</span>
                    </a>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="index.php" class="hover:text-gray-200 transition-colors">Home</a>
                        <a href="about.php" class="bg-red-700 px-3 py-1 rounded">About</a>
                        <a href="features.php" class="hover:text-gray-200 transition-colors">Features</a>
                        <a href="gallery.php" class="hover:text-gray-200 transition-colors">Gallery</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-sm">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                            <a href="admin.php" class="bg-white text-red-600 px-4 py-2 rounded font-medium hover:bg-gray-100">Admin Panel</a>
                        <?php else: ?>
                            <a href="profile.php" class="bg-white text-red-600 px-4 py-2 rounded font-medium hover:bg-gray-100">My Profile</a>
                        <?php endif; ?>
                        <a href="logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="bg-white text-red-600 px-4 py-2 rounded font-medium hover:bg-gray-100">Login</a>
                        <a href="register.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-6">About Blood Donor Finder</h1>
                <p class="text-lg text-gray-600 mb-4">
                    We are dedicated to connecting blood donors with those in need, ensuring that no one faces a medical emergency without access to life-saving blood.
                </p>
                <p class="text-lg text-gray-600 mb-4">
                    Our platform provides a simple yet powerful solution to the complex challenge of finding compatible blood donors quickly and efficiently.
                </p>
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-3xl font-bold text-red-600">5000+</div>
                        <div class="text-gray-600">Registered Donors</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="text-3xl font-bold text-red-600">1000+</div>
                        <div class="text-gray-600">Lives Saved</div>
                    </div>
                </div>
            </div>
            <div>
                <img src="images/11.jpg" alt="Blood Donation" class="rounded-2xl shadow-2xl w-full">
            </div>
        </div>

        <div class="mt-20 bg-white rounded-2xl shadow-xl p-12">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">Our Mission</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">❤️</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Save Lives</h3>
                    <p class="text-gray-600">Connecting donors with patients in urgent need of blood</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⚡</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Quick Response</h3>
                    <p class="text-gray-600">Fast and efficient matching of compatible donors</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🌍</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Community Support</h3>
                    <p class="text-gray-600">Building a strong network of voluntary blood donors</p>
                </div>
            </div>
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
