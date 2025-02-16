<?php
session_start();
include('../includes/header.php');
include('../includes/config.php');

$sql = "SELECT * FROM assets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset List</title>
</head>
<body>
    <h2>Asset List</h2>
   
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Last Maintenance</th>
            <th>Images</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['asset_id']; ?></td>
            <td><?php echo $row['asset_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['last_maintenance_date']; ?></td>
            <td>
                <?php
                $asset_id = $row['asset_id'];
                $img_query = "SELECT img_path FROM assets_image WHERE asset_id = $asset_id";
                $img_result = $conn->query($img_query);
                
                while ($img_row = $img_result->fetch_assoc()) {
                    echo '<img src="../' . $img_row['img_path'] . '" width="100" height="100" style="margin:5px;">';
                }
                ?>
            </td>
           
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
