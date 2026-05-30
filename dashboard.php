<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user and donor information
$stmt = $pdo->prepare("SELECT d.*, u.name, u.email, u.role FROM donors d JOIN users u ON d.user_id = u.id WHERE d.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get donation history for this donor
$stmt = $pdo->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY donation_date DESC");
$stmt->execute([$user['id']]);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalDonations = count($donations);
$lastDonation = !empty($donations) ? $donations[0]['donation_date'] : 'Never';
$nextEligibleDate = !empty($donations) ? date('Y-m-d', strtotime($donations[0]['donation_date'] . ' + 56 days')) : 'Eligible Now';
$isEligible = empty($donations) || strtotime(date('Y-m-d')) >= strtotime($donations[0]['donation_date'] . ' + 56 days');

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
    <title>My Dashboard - Blood Donor Finder</title>
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
                    <span class="text-sm">Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
                    <a href="dashboard.php" class="bg-white text-red-600 px-4 py-2 rounded font-medium hover:bg-gray-100">Dashboard</a>
                    <a href="profile.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Profile</a>
                    <a href="logout.php" class="bg-red-800 px-4 py-2 rounded hover:bg-red-900">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-8 text-gray-900">My Dashboard</h2>
        
        <!-- Personal Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Blood Group</p>
                        <p class="text-4xl font-bold mt-2"><?php echo htmlspecialchars($user['blood_group']); ?></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Donations</p>
                        <p class="text-4xl font-bold mt-2"><?php echo $totalDonations; ?></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Last Donation</p>
                        <p class="text-lg font-bold mt-2"><?php echo $lastDonation; ?></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br <?php echo $isEligible ? 'from-purple-500 to-purple-600' : 'from-yellow-500 to-yellow-600'; ?> rounded-2xl shadow-xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm font-medium">Next Eligible</p>
                        <p class="text-lg font-bold mt-2"><?php echo $nextEligibleDate; ?></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Blood Group Compatibility -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Blood Group Compatibility</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">You can donate to:</p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($canDonateTo as $bg): ?>
                            <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold"><?php echo $bg; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">Your availability status:</p>
                    <span class="px-4 py-2 <?php echo $user['availability'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?> rounded-full font-semibold">
                        <?php echo $user['availability'] ? 'Available to Donate' : 'Not Available'; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Donation Eligibility Progress -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Donation Eligibility Progress</h3>
            <?php if (!empty($donations)): ?>
                <?php
                $lastDonationDate = strtotime($donations[0]['donation_date']);
                $nextEligibleDate = strtotime($donations[0]['donation_date'] . ' + 56 days');
                $currentDate = strtotime(date('Y-m-d'));
                $daysPassed = floor(($currentDate - $lastDonationDate) / (60 * 60 * 24));
                $daysRemaining = max(0, 56 - $daysPassed);
                $progress = min(100, ($daysPassed / 56) * 100);
                ?>
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Days since last donation: <?php echo $daysPassed; ?></span>
                        <span>Days until eligible: <?php echo $daysRemaining; ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-4 rounded-full transition-all duration-500" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
                <div class="flex items-center justify-center mt-6">
                    <?php if ($isEligible): ?>
                        <div class="bg-green-100 border-2 border-green-500 rounded-xl p-6 text-center">
                            <svg class="w-16 h-16 text-green-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-green-800 font-bold text-lg">You are eligible to donate!</p>
                            <p class="text-green-600 text-sm">Contact your nearest blood donation center</p>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-100 border-2 border-yellow-500 rounded-xl p-6 text-center">
                            <svg class="w-16 h-16 text-yellow-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-yellow-800 font-bold text-lg">Wait <?php echo $daysRemaining; ?> more days</p>
                            <p class="text-yellow-600 text-sm">You'll be eligible on <?php echo date('F j, Y', $nextEligibleDate); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="bg-blue-100 border-2 border-blue-500 rounded-xl p-6 text-center">
                    <svg class="w-16 h-16 text-blue-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-blue-800 font-bold text-lg">No donations recorded yet</p>
                    <p class="text-blue-600 text-sm">You are eligible to donate now!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Donation History -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Donation History</h3>
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
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="profile.php" class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Update Profile</h4>
                        <p class="text-gray-500 text-sm">Edit your personal information</p>
                    </div>
                </div>
            </a>
            
            <a href="search.php" class="bg-white rounded-2xl shadow-xl p-6 hover:shadow-2xl transition-shadow cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Find Donors</h4>
                        <p class="text-gray-500 text-sm">Search for blood donors in your area</p>
                    </div>
                </div>
            </a>
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
