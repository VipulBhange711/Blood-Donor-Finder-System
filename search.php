<?php
include 'config.php';

$bloodGroup = isset($_POST['blood_group']) ? $_POST['blood_group'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';

$query = "SELECT d.*, u.name, u.email FROM donors d JOIN users u ON d.user_id = u.id WHERE d.availability = 1";
$params = [];

if ($bloodGroup) {
    $query .= " AND d.blood_group = ?";
    $params[] = $bloodGroup;
}
if ($city) {
    $query .= " AND d.city LIKE ?";
    $params[] = "%$city%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($donors) > 0) {
    foreach ($donors as $donor) {
        echo '<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="flex items-start justify-between">
                <div class="w-full">
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">' . htmlspecialchars($donor['name']) . '</h4>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Blood Group:</span>
                            <span class="bg-gradient-to-r from-red-100 to-red-200 text-red-800 px-3 py-1 rounded-full text-sm font-bold">' . htmlspecialchars($donor['blood_group']) . '</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">City:</span>
                            <span class="text-gray-600">' . htmlspecialchars($donor['city']) . '</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Phone:</span>
                            <a href="tel:' . htmlspecialchars($donor['phone']) . '" class="text-red-600 hover:text-red-700 font-medium">' . htmlspecialchars($donor['phone']) . '</a>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-700">Email:</span>
                            <a href="mailto:' . htmlspecialchars($donor['email']) . '" class="text-red-600 hover:text-red-700 font-medium">' . htmlspecialchars($donor['email']) . '</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<div class="col-span-full text-center py-16 bg-white rounded-2xl shadow-lg">
        <div class="text-6xl mb-4">🩸</div>
        <p class="text-2xl font-bold text-gray-700 mb-2">No donors found</p>
        <p class="text-gray-500">Try adjusting your search criteria</p>
    </div>';
}
