<?php
include 'config.php';

$dummyDonors = [
    ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password', 'blood_group' => 'O+', 'city' => 'New York', 'phone' => '555-1234'],
    ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'password' => 'password', 'blood_group' => 'A-', 'city' => 'Los Angeles', 'phone' => '555-5678'],
    ['name' => 'Michael Johnson', 'email' => 'michael@example.com', 'password' => 'password', 'blood_group' => 'B+', 'city' => 'Chicago', 'phone' => '555-9012'],
    ['name' => 'Emily Davis', 'email' => 'emily@example.com', 'password' => 'password', 'blood_group' => 'AB+', 'city' => 'New York', 'phone' => '555-3456'],
    ['name' => 'David Wilson', 'email' => 'david@example.com', 'password' => 'password', 'blood_group' => 'O-', 'city' => 'Houston', 'phone' => '555-7890'],
];

try {
    $pdo->beginTransaction();

    foreach ($dummyDonors as $donor) {
        // Check if user already exists
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$donor['email']]);
        $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$existingUser) {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'donor')");
            $stmt->execute([$donor['name'], $donor['email'], password_hash($donor['password'], PASSWORD_DEFAULT)]);
            $userId = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO donors (user_id, blood_group, city, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $donor['blood_group'], $donor['city'], $donor['phone']]);
            echo "Added donor: " . $donor['name'] . "<br>";
        } else {
            echo "Donor already exists: " . $donor['name'] . "<br>";
        }
    }

    $pdo->commit();
    echo "<br><a href='index.php'>Go back to home</a>";
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
