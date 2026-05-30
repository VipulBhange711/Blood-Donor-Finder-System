<?php
include 'config.php';

try {
    // Check if donations table exists
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='donations'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        $pdo->exec("CREATE TABLE donations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            donor_id INTEGER NOT NULL,
            donation_date DATE NOT NULL,
            location VARCHAR(100),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE
        )");
        echo "Donations table created successfully.<br>";
    } else {
        echo "Donations table already exists.<br>";
    }
    
    // Insert some sample donation data for testing
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM donations");
    $count = $stmt->fetch()['count'];
    
    if ($count == 0) {
        // Get some donor IDs
        $stmt = $pdo->query("SELECT id FROM donors LIMIT 5");
        $donors = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($donors) > 0) {
            $sampleDonations = [
                ['donor_id' => $donors[0], 'donation_date' => '2024-01-15', 'location' => 'City Hospital', 'notes' => 'Regular donation'],
                ['donor_id' => $donors[0], 'donation_date' => '2024-06-20', 'location' => 'Red Cross Center', 'notes' => 'Emergency donation'],
                ['donor_id' => $donors[1] ?? $donors[0], 'donation_date' => '2024-02-10', 'location' => 'City Hospital', 'notes' => 'First donation'],
                ['donor_id' => $donors[2] ?? $donors[0], 'donation_date' => '2024-03-25', 'location' => 'Community Center', 'notes' => 'Camp donation'],
                ['donor_id' => $donors[3] ?? $donors[0], 'donation_date' => '2024-04-12', 'location' => 'City Hospital', 'notes' => 'Regular donation'],
                ['donor_id' => $donors[4] ?? $donors[0], 'donation_date' => '2024-05-18', 'location' => 'Red Cross Center', 'notes' => 'Emergency donation'],
                ['donor_id' => $donors[0], 'donation_date' => '2024-08-30', 'location' => 'City Hospital', 'notes' => 'Regular donation'],
                ['donor_id' => $donors[1] ?? $donors[0], 'donation_date' => '2024-09-15', 'location' => 'Community Center', 'notes' => 'Camp donation'],
            ];
            
            $insertStmt = $pdo->prepare("INSERT INTO donations (donor_id, donation_date, location, notes) VALUES (?, ?, ?, ?)");
            foreach ($sampleDonations as $donation) {
                $insertStmt->execute([$donation['donor_id'], $donation['donation_date'], $donation['location'], $donation['notes']]);
            }
            echo "Sample donation data inserted successfully.<br>";
        }
    } else {
        echo "Donation data already exists.<br>";
    }
    
    echo "Migration completed successfully!";
    
} catch (PDOException $e) {
    die("Migration failed: " . $e->getMessage());
}
?>
