<?php
include 'config.php';

echo "<h2>Users in Database:</h2>";
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $user) {
    echo "<pre>";
    print_r($user);
    echo "</pre>";
}

echo "<h2>Donors in Database:</h2>";
$stmt = $pdo->query("SELECT * FROM donors");
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($donors as $donor) {
    echo "<pre>";
    print_r($donor);
    echo "</pre>";
}
