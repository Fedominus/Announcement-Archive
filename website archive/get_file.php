<?php
    session_start();
    include 'userdb.php';

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if (isset($_GET['subject_code'])) {
        
        $subject_code = $_GET['subject_code'];
        
        // JOIN WITH userdata TABLE TO GET USERNAME
        $stmt = $conn->prepare("
            SELECT lm.id, lm.file_name, lm.file_path, lm.file_type, lm.file_size, lm.upload_date, u.username as uploaded_by 
            FROM learning_materials lm 
            LEFT JOIN userdata u ON lm.uploaded_by = u.id 
            WHERE lm.subject_code = ? 
            ORDER BY lm.upload_date DESC
        ");
        
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
            exit;
        }
        
        $stmt->bind_param("s", $subject_code);
        
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
            exit;
        }
        
        $result = $stmt->get_result();
        $files = array();
        
        while ($row = $result->fetch_assoc()) {
            // Ensure file_path is correct
            $files[] = $row;
        }
        
        echo json_encode([
            'success' => true, 
            'files' => $files,
            'count' => count($files) // For debugging
        ]);
        
        $stmt->close();
        
    } else {
        echo json_encode(['success' => false, 'message' => 'No subject code provided']);
    }

    $conn->close();
?>