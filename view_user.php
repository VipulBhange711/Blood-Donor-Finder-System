<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$userId = $_GET['id'];

// Get user and donor information
$stmt = $pdo->prepare("SELECT d.*, u.name, u.email, u.role, u.created_at as user_created_at FROM donors d JOIN users u ON d.user_id = u.id WHERE u.id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: admin.php');
    exit;
}

// Get donation history for this donor
$stmt = $pdo->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY donation_date DESC");
$stmt->execute([$user['id']]);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalDonations = count($donations);
$lastDonation = !empty($donations) ? $donations[0]['donation_date'] : 'Never';

// Get blood group compatibility info
$compatibility = [
    'A+' => ['A+', 'AB+'],
    'A-' => ['A+', 'A-', 'AB+', 'AB-'],
    'B+' => ['B+', 'AB+'],
    'B-' => ['B+', 'B-', 'AB+', 'AB-'],
    'AB+' => ['AB+'],
    'AB-' => ['AB+', 'AB-'],
    'O+' => ['A+', 'B+', 'AB+', 'O+'],
    'O-' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']
];
$canDonateTo = $compatibility[$user['blood_group']] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User - Blood Donor Finder</title>
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

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">User Details</h2>
            <div class="flex space-x-3">
                <a href="edit_user.php?id=<?php echo $userId; ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Edit User</a>
                <a href="admin.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">Back to List</a>
            </div>
        </div>
        
        <!-- User Information Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center mb-6">
                <div class="bg-red-100 p-4 rounded-full mr-4">
                    <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></h3>
                    <p class="text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-1">Blood Group</p>
                    <p class="text-xl font-bold text-red-600"><?php echo htmlspecialchars($user['blood_group']); ?></p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-1">Role</p>
                    <p class="text-xl font-bold text-gray-900"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-1">City</p>
                    <p class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($user['city']); ?></p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-1">Phone</p>
                    <p class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($user['phone']); ?></p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-1">Availability</p>
                    <p class="text-xl font-bold <?php echo $user['availability'] ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo $user['availability'] ? 'Available' : 'Not Available'; ?>
                    </p>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-1">Member Since</p>
                    <p class="text-xl font-bold text-gray-900"><?php echo date('F j, Y', strtotime($user['user_created_at'])); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Donation Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
                    <p class="text-red-100 text-sm font-medium">Total Donations</p>
                    <p class="text-4xl font-bold mt-2"><?php echo $totalDonations; ?></p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                    <p class="text-blue-100 text-sm font-medium">Last Donation</p>
                    <p class="text-2xl font-bold mt-2"><?php echo $lastDonation; ?></p>
                </div>
            </div>
        </div>
        
        <!-- Blood Group Compatibility -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Blood Group Compatibility</h3>
            <p class="text-sm text-gray-600 mb-3">This donor can donate to:</p>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($canDonateTo as $bg): ?>
                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold"><?php echo $bg; ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Donation History -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Donation History</h3>
            <?php if (empty($donations)): ?>
                <p class="text-gray-500 text-center py-8">No donation history recorded yet.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($donations as $donation): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($donation['donation_date']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($donation['location']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($donation['notes']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
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
