<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Blood Donor Finder System</title>
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
                        <a href="about.php" class="hover:text-gray-200 transition-colors">About</a>
                        <a href="features.php" class="hover:text-gray-200 transition-colors">Features</a>
                        <a href="gallery.php" class="bg-red-700 px-3 py-1 rounded">Gallery</a>
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
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Our Gallery</h1>
            <p class="text-xl text-gray-600">Moments of compassion, hope, and life-saving donations</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/1.jpg" alt="Gallery Image 1" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 1</h3>
                    <p class="text-gray-600">A moment of compassion</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/2.jpg" alt="Gallery Image 2" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 2</h3>
                    <p class="text-gray-600">Saving lives together</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/3.jpg" alt="Gallery Image 3" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 3</h3>
                    <p class="text-gray-600">Hope and care</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/4.jpg" alt="Gallery Image 4" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 4</h3>
                    <p class="text-gray-600">Every drop counts</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/5.jpg" alt="Gallery Image 5" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 5</h3>
                    <p class="text-gray-600">Medical professionals</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/6.jpg" alt="Gallery Image 6" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 6</h3>
                    <p class="text-gray-600">Community support</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/7.jpg" alt="Gallery Image 7" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 7</h3>
                    <p class="text-gray-600">Donor appreciation</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/8.jpg" alt="Gallery Image 8" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 8</h3>
                    <p class="text-gray-600">Life saving moments</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/9.jpg" alt="Gallery Image 9" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 9</h3>
                    <p class="text-gray-600">Happy patients</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/10.jpg" alt="Gallery Image 10" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 10</h3>
                    <p class="text-gray-600">Blood donation drive</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/11.jpg" alt="Gallery Image 11" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 11</h3>
                    <p class="text-gray-600">Volunteer team</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <img src="images/12.jpg" alt="Gallery Image 12" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gallery Image 12</h3>
                    <p class="text-gray-600">Hope for tomorrow</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4">Blood Donor Finder System</h3>
            <p class="text-gray-400">Connecting donors, saving lives</p>
        </div>
    </footer>
</body>
</html>
