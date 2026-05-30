<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get data for PDF
$stmt = $pdo->query("SELECT d.*, u.name, u.email, u.created_at FROM donors d JOIN users u ON d.user_id = u.id ORDER BY d.city, d.blood_group");
$donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalDonors = count($donors);
$availableDonors = $pdo->query("SELECT COUNT(*) as count FROM donors WHERE availability = 1")->fetch()['count'];
$totalDonations = $pdo->query("SELECT COUNT(*) as count FROM donations")->fetch()['count'];

// Generate HTML for PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Donor Report - Blood Donor Finder</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #dc2626; padding-bottom: 20px; }
        .header h1 { color: #dc2626; margin: 0; }
        .header p { color: #666; margin: 5px 0 0 0; }
        .stats { display: flex; justify-content: space-around; margin-bottom: 30px; background: #fef2f2; padding: 20px; border-radius: 10px; }
        .stat-box { text-align: center; }
        .stat-box .number { font-size: 32px; font-weight: bold; color: #dc2626; }
        .stat-box .label { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #dc2626; color: white; padding: 12px; text-align: left; }
        td { border: 1px solid #ddd; padding: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .blood-group { background: #fef2f2; color: #dc2626; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
        .available { color: #16a34a; font-weight: bold; }
        .not-available { color: #dc2626; font-weight: bold; }
        .footer { text-align: center; margin-top: 40px; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Blood Donor Finder System</h1>
        <p>Complete Donor Report</p>
        <p>Generated on: ' . date('F j, Y, g:i A') . '</p>
    </div>
    
    <div class="stats">
        <div class="stat-box">
            <div class="number">' . $totalDonors . '</div>
            <div class="label">Total Donors</div>
        </div>
        <div class="stat-box">
            <div class="number">' . $availableDonors . '</div>
            <div class="label">Available Donors</div>
        </div>
        <div class="stat-box">
            <div class="number">' . $totalDonations . '</div>
            <div class="label">Total Donations</div>
        </div>
    </div>
    
    <h2 style="color: #333; margin-bottom: 15px;">Donor List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Blood Group</th>
                <th>City</th>
                <th>Phone</th>
                <th>Availability</th>
            </tr>
        </thead>
        <tbody>';

foreach ($donors as $donor) {
    $html .= '
            <tr>
                <td>' . htmlspecialchars($donor['name']) . '</td>
                <td>' . htmlspecialchars($donor['email']) . '</td>
                <td><span class="blood-group">' . htmlspecialchars($donor['blood_group']) . '</span></td>
                <td>' . htmlspecialchars($donor['city']) . '</td>
                <td>' . htmlspecialchars($donor['phone']) . '</td>
                <td class="' . ($donor['availability'] ? 'available' : 'not-available') . '">' . ($donor['availability'] ? 'Available' : 'Not Available') . '</td>
            </tr>';
}

$html .= '
        </tbody>
    </table>
    
    <div class="footer">
        <p>Blood Donor Finder System - Connecting Donors, Saving Lives</p>
        <p>This is an auto-generated report. For questions, contact the administrator.</p>
    </div>
</body>
</html>';

// Save HTML to temporary file
$tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
file_put_contents($tempFile . '.html', $html);

// Use wkhtmltopdf if available, otherwise provide HTML download
$wkhtmltopdf = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe'; // Windows path
if (file_exists($wkhtmltopdf)) {
    $pdfFile = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';
    exec("\"$wkhtmltopdf\" \"$tempFile.html\" \"$pdfFile\"");
    
    if (file_exists($pdfFile)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="donor_report_' . date('Y-m-d') . '.pdf"');
        header('Content-Length: ' . filesize($pdfFile));
        readfile($pdfFile);
        unlink($pdfFile);
        unlink($tempFile . '.html');
        exit;
    }
}

// Fallback: Download as HTML file that can be printed to PDF
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="donor_report_' . date('Y-m-d') . '.html"');
echo $html;
unlink($tempFile . '.html');
exit;
?>
