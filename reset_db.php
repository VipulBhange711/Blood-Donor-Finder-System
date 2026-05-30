<?php
// Reset the database (only use this for testing!)
$dbFile = __DIR__ . '/blood_donor_finder.sqlite';

if (file_exists($dbFile)) {
    unlink($dbFile);
    echo "Database deleted successfully!<br>";
}

// Also clear sessions
$sessionPath = __DIR__ . '/sessions';
if (is_dir($sessionPath)) {
    $files = glob($sessionPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "Sessions cleared!<br>";
}

echo "<a href='index.php'>Go to Homepage</a>";
?>