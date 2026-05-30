<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Blood Donor Finder System</title>
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
                        <a href="features.php" class="bg-red-700 px-3 py-1 rounded">Features</a>
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
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Powerful Features</h1>
            <p class="text-xl text-gray-600">Everything you need to find and manage blood donors efficiently</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🔍</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Search</h3>
                <p class="text-gray-600">Search donors by blood group and location with real-time results powered by AJAX</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">👤</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Donor Profiles</h3>
                <p class="text-gray-600">Easy registration and profile management with availability status updates</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">🛡️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Secure System</h3>
                <p class="text-gray-600">Password hashing and secure data handling to protect donor information</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">📱</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Responsive Design</h3>
                <p class="text-gray-600">Looks great on mobile, tablet, and desktop with Tailwind CSS</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">⚙️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Admin Dashboard</h3>
                <p class="text-gray-600">Complete donor management system for administrators</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <span class="text-3xl">⚡</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Fast & Efficient</h3>
                <p class="text-gray-600">Optimized performance for quick access during emergencies</p>
            </div>
        </div>

        <div class="mt-20 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <img src="https://coresg-normal.trae.ai/api/v1/text_to_image?prompt=medical%20hospital%20blood%20bank%20modern%20clean%20professional%20interior&image_size=landscape_16_9" alt="Blood Bank" class="rounded-2xl shadow-2xl w-full">
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">How It Works</h2>
                <ol class="space-y-4">
                    <li class="flex items-start space-x-4">
                        <span class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">1</span>
                        <div>
                            <h4 class="font-semibold text-gray-900">Register as Donor</h4>
                            <p class="text-gray-600">Create your profile with blood group, location, and contact details</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-4">
                        <span class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">2</span>
                        <div>
                            <h4 class="font-semibold text-gray-900">Search for Donors</h4>
                            <p class="text-gray-600">Patients and hospitals can find compatible donors quickly</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-4">
                        <span class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">3</span>
                        <div>
                            <h4 class="font-semibold text-gray-900">Connect & Save Lives</h4>
                            <p class="text-gray-600">Get in touch with donors and arrange for blood donation</p>
                        </div>
                    </li>
                </ol>
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
