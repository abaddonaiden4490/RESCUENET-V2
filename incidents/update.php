<?php
require '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $incident_id = intval($_POST['incident_id']);
    $incident_type = $_POST['incident_type'];
    $location = $_POST['location'];
    $reported_by = intval($_POST['reported_by']);
    $severity_id = intval($_POST['severity_id']);
    $status_id = intval($_POST['status_id']);  // Use status_id to match the status table
    $actions_taken = $_POST['actions_taken'];  // Capture actions_taken

    // Fetch current attachments from the database (if any)
    $sql = "SELECT attachments FROM incidents WHERE incident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $incident_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_attachments = $row ? $row['attachments'] : '';  // Keep existing attachments
    $stmt->close();

    // Prepare the SQL query without the 'attachments' column
    $sql = "UPDATE incidents SET
            incident_type = ?,
            location = ?,
            reported_by = ?,
            severity_id = ?,
            status_id = ?,
            actions_taken = ?
            WHERE incident_id = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiisi", $incident_type, $location, $reported_by, $severity_id, $status_id, $actions_taken, $incident_id);

    // Execute the update
    if ($stmt->execute()) {
        // Redirect back to the incident details page
        header("Location: edit.php?id=" . $incident_id);
        exit();
    } else {
        echo "Error updating the incident.";
    }
}
?>
