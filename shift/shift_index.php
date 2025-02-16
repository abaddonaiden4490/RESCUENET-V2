<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');
// Fetch shifts with member details
$query = "SELECT s.shift_id, m.first_name, m.last_name, s.start_time, s.end_time, u.username AS assigned_by 
          FROM shifts s
          JOIN members m ON s.member_id = m.member_id
          LEFT JOIN users u ON s.assigned_by = u.user_id
          ORDER BY s.start_time ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shift Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Shift Management</h2>

    

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Member</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Assigned By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td><?php echo $row['start_time']; ?></td>
                            <td><?php echo $row['end_time']; ?></td>
                            <td><?php echo $row['assigned_by'] ?? 'N/A'; ?></td>
                          
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>