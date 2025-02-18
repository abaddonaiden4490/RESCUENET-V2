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
    $attachments = '';  // Initialize attachments variable

    // Fetch existing attachments if any (to preserve them if no new ones are uploaded)
    $sql = "SELECT attachments FROM incidents WHERE incident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $incident_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_attachments = $row ? $row['attachments'] : '';
    $stmt->close();

    // Handle file uploads (if any)
    if (!empty($_FILES['attachments']['name'][0])) {
        $upload_dir = 'uploads/';  // Path to the 'uploads' directory

        // Check if the 'uploads' directory exists, if not, create it
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);  // Create directory if it doesn't exist
        }

        $attachment_files = [];
        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['attachments']['name'][$key];
            $file_path = $upload_dir . $file_name;  // Set the file path to the 'uploads' folder

            // Check if the file upload was successful
            if (move_uploaded_file($tmp_name, $file_path)) {
                $attachment_files[] = $file_path;
            } else {
                echo "Error uploading file: " . $file_name;
                exit();
            }
        }

        // Merge new attachments with the existing ones (if any)
        if ($existing_attachments) {
            $attachments = $existing_attachments . ',' . implode(',', $attachment_files);
        } else {
            $attachments = implode(',', $attachment_files);  // Only new files
        }
    } else {
        // No new files, use existing attachments if available
        $attachments = $existing_attachments;
    }

    // Update the incident in the database
    $sql = "UPDATE incidents SET
            incident_type = ?,
            location = ?,
            reported_by = ?,
            severity_id = ?,
            status_id = ?,
            actions_taken = ?, 
            attachments = ?  
            WHERE incident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiisis", $incident_type, $location, $reported_by, $severity_id, $status_id, $actions_taken, $attachments, $incident_id);

    if ($stmt->execute()) {
        // Redirect back to the incident details page
        header("Location: edit.php?id=" . $incident_id);
        exit();
    } else {
        echo "Error updating the incident.";
    }
}
?>
