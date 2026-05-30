<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_GET['delete']]);
    header('Location: admin.php');
    exit;
}

// Get statistics
$totalDonors = $pdo->query("SELECT COUNT(*) as count FROM donors")->fetch()['count'];
$availableDonors = $pdo->query("SELECT COUNT(*) as count FROM donors WHERE availability = 1")->fetch()['count'];
$totalDonations = $pdo->query("SELECT COUNT(*) as count FROM donations")->fetch()['count'];
$totalUsers = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];

// Get blood group distribution
$bloodGroups = $pdo->query("SELECT blood_group, COUNT(*) as count FROM donors GROUP BY blood_group")->fetchAll(PDO::FETCH_ASSOC);

// Get monthly donations
$monthlyDonations = $pdo->query("SELECT strftime('%Y-%m', donation_date) as month, COUNT(*) as count FROM donations GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_ASSOC);

// Get city distribution
$cityDistribution = $pdo->query("SELECT city, COUNT(*) as count FROM donors GROUP BY city ORDER BY count DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT d.*, u.name, u.email, u.created_at FROM donors d JOIN users u ON d.user_id = u.id");
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Blood Donor Finder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8 text-gray-900">Admin Dashboard</h2>
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Total Donors</p>
                        <p class="text-3xl font-bold mt-2"><?php echo $totalDonors; ?></p>
                        <p class="text-red-200 text-xs mt-1">Registered users</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Available Donors</p>
                        <p class="text-3xl font-bold mt-2"><?php echo $availableDonors; ?></p>
                        <p class="text-green-200 text-xs mt-1"><?php echo $totalDonors > 0 ? round(($availableDonors / $totalDonors) * 100) : 0; ?>% availability</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Donations</p>
                        <p class="text-3xl font-bold mt-2"><?php echo $totalDonations; ?></p>
                        <p class="text-blue-200 text-xs mt-1">Lives impacted</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white hover:shadow-2xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Users</p>
                        <p class="text-3xl font-bold mt-2"><?php echo $totalUsers; ?></p>
                        <p class="text-purple-200 text-xs mt-1">System users</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="generate_pdf.php" class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow cursor-pointer border-l-4 border-red-500">
                <div class="flex items-center space-x-4">
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Download Report</h4>
                        <p class="text-gray-500 text-sm">Export donor list as PDF</p>
                    </div>
                </div>
            </a>
            
            <a href="profile.php" class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow cursor-pointer border-l-4 border-blue-500">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Manage Users</h4>
                        <p class="text-gray-500 text-sm">View and edit user profiles</p>
                    </div>
                </div>
            </a>
            
            <a href="search.php" class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow cursor-pointer border-l-4 border-green-500">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Search Donors</h4>
                        <p class="text-gray-500 text-sm">Find donors by location</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Blood Group Distribution</h3>
                <canvas id="bloodGroupChart"></canvas>
            </div>
            
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Monthly Donations</h3>
                <canvas id="monthlyDonationsChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Donor Distribution by City</h3>
            <canvas id="cityChart"></canvas>
        </div>
        
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Donor Management</h2>
            <div class="flex space-x-3">
                <a href="add_user.php" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg font-bold hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Add User</span>
                </a>
                <a href="generate_pdf.php" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span>Download PDF Report</span>
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Group</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($donors as $donor): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($donor['name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($donor['email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800"><?php echo htmlspecialchars($donor['blood_group']); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($donor['city']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($donor['phone']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo $donor['availability'] ? '<span class="text-green-600 font-medium">Yes</span>' : '<span class="text-red-600 font-medium">No</span>'; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="view_user.php?id=<?php echo $donor['user_id']; ?>" class="text-green-600 hover:text-green-900 mr-3">View</a>
                                <a href="edit_user.php?id=<?php echo $donor['user_id']; ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <a href="admin.php?delete=<?php echo $donor['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4">Blood Donor Finder System</h3>
            <p class="text-gray-400">Connecting donors, saving lives</p>
        </div>
    </footer>

    <script>
        // Blood Group Distribution Chart
        const bloodGroupCtx = document.getElementById('bloodGroupChart').getContext('2d');
        const bloodGroupData = <?php echo json_encode($bloodGroups); ?>;
        const bloodGroupLabels = bloodGroupData.map(item => item.blood_group);
        const bloodGroupCounts = bloodGroupData.map(item => item.count);
        
        new Chart(bloodGroupCtx, {
            type: 'doughnut',
            data: {
                labels: bloodGroupLabels,
                datasets: [{
                    data: bloodGroupCounts,
                    backgroundColor: [
                        '#EF4444', '#F97316', '#EAB308', '#22C55E',
                        '#3B82F6', '#8B5CF6', '#EC4899', '#6B7280'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Monthly Donations Chart
        const monthlyCtx = document.getElementById('monthlyDonationsChart').getContext('2d');
        const monthlyData = <?php echo json_encode($monthlyDonations); ?>;
        const monthlyLabels = monthlyData.map(item => item.month);
        const monthlyCounts = monthlyData.map(item => item.count);
        
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Donations',
                    data: monthlyCounts,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // City Distribution Chart
        const cityCtx = document.getElementById('cityChart').getContext('2d');
        const cityData = <?php echo json_encode($cityDistribution); ?>;
        const cityLabels = cityData.map(item => item.city);
        const cityCounts = cityData.map(item => item.count);
        
        new Chart(cityCtx, {
            type: 'bar',
            data: {
                labels: cityLabels,
                datasets: [{
                    label: 'Donors',
                    data: cityCounts,
                    backgroundColor: [
                        '#EF4444', '#F97316', '#EAB308', '#22C55E',
                        '#3B82F6', '#8B5CF6', '#EC4899', '#6B7280',
                        '#14B8A6', '#F43F5E'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
