<?php
    session_start();
    include("userdb.php");

    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'User') {
        header("HTTP/1.1 403 Forbidden");
        exit;
    }

    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        
        // Fetch file information
        $sql = "SELECT file_name, file_path FROM learning_materials WHERE id = '$id' AND upload_date";
        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $file_path = $row['file_path'];
            $file_name = $row['file_name'];
            
            // Check if file exists
            if(file_exists($file_path)) {
                // Set headers for download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                readfile($file_path);
                exit;
            } else {
                echo "File not found.";
            }
        } else {
            echo "File not found or access denied.";
        }
    }
?>