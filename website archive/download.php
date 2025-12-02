<?php
// download.php
session_start();
include("userdb.php");

// Check if user is logged in
if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    die('Please login first!');
}

// Check if material ID is provided
if(!isset($_GET['id'])) {
    die('Invalid request');
}

$material_id = intval($_GET['id']);

// Get file details from database
$sql = "SELECT * FROM learning_materials WHERE id = ? AND status = 'active'";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $material_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$material = mysqli_fetch_assoc($result);

if($material) {
    $file_path = $material['file_path'];
    
    // Check if file exists
    if(file_exists($file_path)) {
        
        // Optional: Log the download (for tracking)
        $log_sql = "INSERT INTO download_logs (user_id, material_id, download_date) VALUES (?, ?, NOW())";
        $log_stmt = mysqli_prepare($conn, $log_sql);
        if($log_stmt) {
            mysqli_stmt_bind_param($log_stmt, "ii", $_SESSION['user_id'], $material_id);
            mysqli_stmt_execute($log_stmt);
            mysqli_stmt_close($log_stmt);
        }
        
        // Set headers for download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($material['file_name']) . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        // Clear output buffer
        ob_clean();
        flush();
        
        // Read and output file
        readfile($file_path);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit;
        
    } else {
        die('File not found on server');
    }
} else {
    die('Material not found or inactive');
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>