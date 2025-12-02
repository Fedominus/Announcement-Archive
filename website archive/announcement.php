<?php
  session_start();
  include("userdb.php");

  // Check if user is logged in
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }

  // CHECK IF ADMIN
  if ($_SESSION['role'] != 'Admin') {
      echo "<script>
          alert('Access Denied! This page is for Admins only.');
          window.location='user-announcement.php';
      </script>";
      exit;
  }

  //  AJAX ANNOUNCEMENT SUBMISSION
  if (isset($_POST['add_announcement_ajax'])) {
      $announcement = trim($_POST['announcement']);
      $expiration = $_POST['expiration'];

      $response = ['success' => false, 'message' => ''];

      if ($announcement === "") {
          $response['message'] = 'Announcement text is empty';
          echo json_encode($response);
          exit;
      }

      if ($expiration === "") {
          $response['message'] = 'Please select expiration date';
          echo json_encode($response);
          exit;
      }

      // this is to check if expired the entered date
      $expiry_date = $expiration; // Already have this from $_POST['expiration']
      $current_date = date('Y-m-d H:i:s');
    
      if ($expiry_date < $current_date) {
          $response['message'] = 'Expired date is not allowed.';
          echo json_encode($response);
          exit;
      }

      $insert = $conn->prepare("INSERT INTO announcements (user_id, message, expires_at) VALUES (?, ?, ?)");
      $insert->bind_param("iss", $_SESSION['user_id'], $announcement, $expiration);
      
      if ($insert->execute()) {
          // Get user details for display
          $user_query = $conn->prepare("SELECT full_name, section FROM userdata WHERE id = ?");
          $user_query->bind_param("i", $_SESSION['user_id']);
          $user_query->execute();
          $user_result = $user_query->get_result();
          $user = $user_result->fetch_assoc();
          
          $response['success'] = true;
          $response['full_name'] = $user['full_name'];
          $response['section'] = $user['section'];
          $response['expires_at'] = $expiration;
          $response['message_text'] = $announcement;
      } else {
          $response['message'] = 'Database error: ' . $conn->error;
      }
      
      $insert->close();
      echo json_encode($response);
      exit;
  }

  // check if expired yung iaadd na announcement
  // $title = $_POST['title'];
  // $content = $_POST['content'];
  // $expiry_date = $_POST['expiry_date'];
  //($expiry_date < $current_date) {
  // $current_date = date('Y-m-d');


  // FETCH ANNOUNCEMENTS FOR DISPLAY
  $current_date = date('Y-m-d H:i:s');
  $announcements_query = "SELECT a.*, u.full_name, u.section 
                        FROM announcements a 
                        JOIN userdata u ON a.user_id = u.id 
                        WHERE expires_at > ? 
                        ORDER BY a.created_at DESC";
  $stmt = $conn->prepare($announcements_query);
  $stmt->bind_param("s", $current_date);
  $stmt->execute();
  $announcements_result = $stmt->get_result();
  $announcements = [];

  while ($row = $announcements_result->fetch_assoc()) {
      $announcements[] = $row;
  }
  $stmt->close();

  


  // Fetch user data from database using session user_id
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT full_name, student_id, section FROM userdata WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
  }

  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Get user data
  if ($row = mysqli_fetch_assoc($result)) {
    $full_name = htmlspecialchars($row['full_name']);
    $student_id = htmlspecialchars($row['student_id']);
    $section = htmlspecialchars($row['section']);
  } else {
    // If user not found, redirect to login
    session_destroy();
    header("Location: login.php");
    exit;
  }
  
  // Delete expired announcements
  $sql = "DELETE FROM announcements WHERE expires_at < CURDATE()";
  $conn->query($sql);



  mysqli_stmt_close($stmt);
  mysqli_close($conn);



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
  <link rel="stylesheet" href="announcement.css" />
  <link rel="stylesheet" href="nav-bar.css" />
  <title>&bullet; Announcement Archive</title>
</head>

<body>
  <!-- -----------------------NAV BAR--------------------- -->
  <nav>
    <div class="nav1">
      <a href="announcement.php"><img src="images/logo-icon.png" class="logo-icon" /></a>
      <h2>Announcement Archive</h2>
    </div>

    <ul class="nav2">
      <li><a href="">UniChat</a></li>
      <li><a href="">Students</a></li>
      <li><a href="about.php">About Us</a></li>
    </ul>
  </nav>

  <!-- ---------------------MAIN FRAME---------------------- -->
  <main id="main-frame">
    <!--PROFILE SECTION -->
    <section id="profile">
      <div class="main-profile">
        <img src="images/fed.jpg" />
        <p> <?php echo $full_name; ?> </p>
        <p><strong><?php echo $student_id; ?></strong></p>
        <p><strong><?php echo $section; ?></strong></p>
        <hr />
      </div>

      <div class="navigations">
        <ul>
          <li>
            <a href="announcement.php"><img src="images/announcement.svg" />Announcements</a>
          </li>
          <li>
            <a href="profile.php"><img src="images/profile.svg" />Profile</a>
          </li>
          <li>
            <a href="colleagues.php"><img src="images/colleagues.svg" />Colleagues</a>
          </li>
          <li>
            <a href="learning-materials.php"><img src="images/learning- material.png" />Learning Materials</a>
          </li>
           <li>
            <a href="logout.php"><img src="images/user-logout.svg" />Log Out</a>
          </li>
        </ul>
      </div>
    </section>

    <!--ANNOUNCEMENT SECTION -->
    <section id="announcement-board">
      <div class="add-ann">
        <h2>Announcements🎉</h2>
        <button type="button" id="addAnns"><img src="images/add-button.svg"> Add Announcement</button>
      </div>
      
      <div class="scroll-box">
        <div class="board" id="hidden">
          <div id="formContainer" class="hidden form-container">
            <form id="announcementForm">
              <textarea type="text" name="announcement" id="textBox" placeholder="Add Announcement"></textarea>
              <label>Expiration Date</label>
              <input type="date" name="expiration" id="expirationDate">
              <button type="button" class="formInputs" id="cancelBtn">Cancel</button>
              <button type="button" id="uploadBtn" class="formInputs">Upload</button>
            </form>
          </div>
        </div>
      
        <!-- Mga existing announcements dito -->
        <?php if (!empty($announcements)): ?>
          <?php foreach ($announcements as $ann): ?>
          <div class="board">
            <h3>(<?php echo htmlspecialchars($ann['section']); ?>) <?php echo htmlspecialchars($ann['full_name']); ?></h3>
            <div class="container">
              <p><?php echo nl2br(htmlspecialchars($ann['message'])); ?></p>
              <small>Expires: <?php echo date('M d, Y', strtotime($ann['expires_at'])); ?></small>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>
    </section>
  </main>

  <!-- ---------------------COPYRIGHT---------------------- -->
  <footer>
    <p>COPYRIGHT &copy; 2025 Announcement Archive. All rights reserved.</p>
  </footer>
  
  <script src="announcement.js"></script>
  
  
</body>

</html>