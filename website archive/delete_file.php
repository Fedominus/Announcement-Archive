<?php
    session_start();
    include 'userdb.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }


    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['file_id'])) {
        
        $file_id = intval($_POST['file_id']);
        $success = false;
        $message = '';
        
        try {
            // Get file path from database
            $stmt = $conn->prepare("SELECT file_path FROM learning_materials WHERE id = ?");
            $stmt->bind_param("i", $file_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $file_path = $row['file_path'];
                
                // Delete physical file if it exists
                if (file_exists($file_path)) {
                    if (!unlink($file_path)) {
                        $message = 'Could not delete physical file';
                    }
                }
                
                // Delete database record
                $stmt2 = $conn->prepare("DELETE FROM learning_materials WHERE id = ?");
                $stmt2->bind_param("i", $file_id);
                
                if ($stmt2->execute()) {
                    $success = true;
                    $message = 'File deleted successfully';
                } else {
                    $message = 'Database error: ' . $stmt2->error;
                }
                
                $stmt2->close();
            } else {
                $message = 'File not found in database';
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
        }
        
        echo json_encode(['success' => $success, 'message' => $message]);
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }

    $conn->close();
?>