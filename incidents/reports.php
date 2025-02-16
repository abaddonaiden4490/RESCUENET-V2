<?php
require '../includes/config.php';
include '../includes/header.php';

// Fetch incidents with attachments and member details
$sql = "SELECT i.incident_id, i.incident_type, i.location, 
               CONCAT(m.first_name, ' ', m.last_name) AS reported_by, 
               i.reported_time, i.status, i.attachments 
        FROM incidents i
        LEFT JOIN members m ON i.reported_by = m.member_id
        ORDER BY i.reported_time DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Incident Reports</h1>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Incident ID</th>
                    <th>Incident Type</th>
                    <th>Location</th>
                    <th>Reported By</th>
                    <th>Reported Time</th>
                    <th>Status</th>
                    <th>Attachments</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['incident_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['incident_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['reported_by'] ?? 'Unknown'); ?></td>
                    <td><?php echo htmlspecialchars($row['reported_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php 
                        if (!empty($row['attachments'])) {
                            $files = explode(',', $row['attachments']);
                            foreach ($files as $file) {
                                $file = htmlspecialchars(trim($file));
                                $ext = pathinfo($file, PATHINFO_EXTENSION);
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

                                if (in_array(strtolower($ext), $imageExtensions)) {
                                    echo '<img src="' . $file . '" alt="Attachment" class="img-thumbnail" style="max-width: 100px; max-height: 100px; margin: 5px;">';
                                } else {
                                    echo '<a href="' . $file . '" target="_blank">View File</a><br>';
                                }
                            }
                        } else {
                            echo 'No Attachments';
                        }
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
