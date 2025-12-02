<?php
    session_start();
    include 'userdb.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
        
        $subject_code = $_POST['subject_code'];
        $file = $_FILES['file'];
        
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        $file_type = $file['type'];
        
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar');
        
        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {
                if ($file_size <= 10485760) {
                    
                    $upload_dir = 'uploads/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $new_file_name = uniqid('', true) . '_' . $file_name;
                    $file_destination = $upload_dir . $new_file_name;
                    
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        
                        $uploaded_by = $_SESSION['user_id'];
                        $stmt = $conn->prepare("INSERT INTO learning_materials (subject_code, file_name, file_path, file_type, file_size, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
                        
                        if (!$stmt) {
                            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
                            exit;
                        }
                        
                        $stmt->bind_param("ssssis", $subject_code, $file_name, $file_destination, $file_type, $file_size, $uploaded_by);
                        
                        if ($stmt->execute()) {
                            echo json_encode([
                                'success' => true, 
                                'message' => 'File uploaded successfully!',
                                'file_id' => $stmt->insert_id,
                                'file_name' => $file_name
                            ]);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
                        }
                        
                        $stmt->close();
                        
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
                    }
                    
                } else {
                    echo json_encode(['success' => false, 'message' => 'File too large. Max 10MB']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Upload error code: ' . $file_error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File type not allowed']);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    }

    $conn->close();
?>