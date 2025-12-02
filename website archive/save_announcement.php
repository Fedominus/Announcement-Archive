<?php
    session_start();
    include("userdb.php");

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Not logged in']);
        exit;
    }

    if (isset($_POST['add_announcement_ajax'])) {
        $announcement = trim($_POST['announcement']);
        $expiration = $_POST['expiration'];
        
        $response = ['success' => false, 'message' => ''];
        
        if ($announcement === "" || $expiration === "") {
            $response['message'] = 'Please fill all fields';
            echo json_encode($response);
            exit;
        }
        
        // I-save sa database
        $insert = $conn->prepare("INSERT INTO announcements (user_id, message, expires_at) VALUES (?, ?, ?)");
        $insert->bind_param("iss", $_SESSION['user_id'], $announcement, $expiration);
        
        if ($insert->execute()) {
            // kukunin yung user details para sa display
            $user_query = $conn->prepare("SELECT full_name, section FROM userdata WHERE id = ?");
            $user_query->bind_param("i", $_SESSION['user_id']);
            $user_query->execute();
            $user_result = $user_query->get_result();
            $user = $user_result->fetch_assoc();
            
            $response['success'] = true;
            $response['full_name'] = $user['full_name'];
            $response['section'] = $user['section'];
            $response['expires_at'] = $expiration;
        } else {
            $response['message'] = 'Database error: ' . $conn->error;
        }
        
        $insert->close();
        echo json_encode($response);
        exit;
    }
?>