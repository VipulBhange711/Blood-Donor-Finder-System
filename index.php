<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donor Finder System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="flex items-center space-x-2">
                        <!-- Logo Image -->
                        <img src="images/logo.png" alt="Blood Donor Finder Logo" class="h-10 w-10 rounded-full object-cover">
                        <span class="text-xl font-bold">Blood Donor Finder</span>
                    </a>
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="index.php" class="bg-red-700 px-3 py-1 rounded">Home</a>
                        <a href="about.php" class="hover:text-gray-200 transition-colors">About</a>
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
                            <a href="dashboard.php" class="bg-white text-red-600 px-4 py-2 rounded font-medium hover:bg-gray-100">Dashboard</a>
                            <a href="profile.php" class="bg-red-800 text-white px-4 py-2 rounded font-medium hover:bg-red-900">Profile</a>
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

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-20">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6">Save Lives With Blood Donation</h1>
                    <p class="text-xl mb-8 text-red-100">Find compatible blood donors quickly during emergencies. Register today and be a lifesaver!</p>
                    <div class="flex space-x-4">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="register.php" class="bg-white text-red-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors">Register Now</a>
                        <?php endif; ?>
                        <a href="about.php" class="border-2 border-white px-8 py-3 rounded-lg font-bold hover:bg-white hover:text-red-600 transition-colors">Learn More</a>
                    </div>
                </div>
                <div>
                    <img src="images/2.jpg" alt="Blood Donation" class="rounded-2xl shadow-2xl">
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-12">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-900">Find Blood Donors</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                    <select id="bloodGroup" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                        <option value="">All Blood Groups</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" id="city" placeholder="Enter city name" class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:border-red-500 focus:outline-none transition-colors">
                </div>
                <div class="flex items-end">
                    <button onclick="searchDonors()" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl">Search Donors</button>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div id="searchResults" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>

    <!-- Stats Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-red-600 mb-2">5000+</div>
                    <div class="text-gray-600 font-medium">Registered Donors</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-red-600 mb-2">1000+</div>
                    <div class="text-gray-600 font-medium">Lives Saved</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-red-600 mb-2">50+</div>
                    <div class="text-gray-600 font-medium">Cities Covered</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-red-600 mb-2">8</div>
                    <div class="text-gray-600 font-medium">Blood Groups</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4">Blood Donor Finder System</h3>
            <p class="text-gray-400">Connecting donors, saving lives</p>
        </div>
    </footer>

    <script>
        function searchDonors() {
            const bloodGroup = document.getElementById('bloodGroup').value;
            const city = document.getElementById('city').value;

            fetch('search.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'blood_group=' + encodeURIComponent(bloodGroup) + '&city=' + encodeURIComponent(city)
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('searchResults').innerHTML = data;
            });
        }

        searchDonors();
    </script>
</body>
</html>
