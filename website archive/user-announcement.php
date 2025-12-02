<?php
  session_start();
  include ("userdb.php");

  // Check if user is logged in
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }

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

  mysqli_stmt_close($stmt);
   
  // Fetch announcements from database
  $announcements_query = "SELECT a.*, u.full_name, u.section 
                        FROM announcements a 
                        JOIN userdata u ON a.user_id = u.id 
                        WHERE expires_at > NOW() 
                        ORDER BY a.created_at DESC";
  $announcements_result = mysqli_query($conn, $announcements_query);

  // Check kung may error
  if (!$announcements_result) {
      die("Error fetching announcements: " . mysqli_error($conn));
  }

  // Initialize an array to store announcements
  $announcements = [];
  while ($row = mysqli_fetch_assoc($announcements_result)) {
      $announcements[] = $row;
  }

  mysqli_close($conn);
?>


<!DOCTYPE html> <!--USER-ANNOUNCEMENT.PHP-->
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
  <link rel="stylesheet" href="user-announcement.css" />
  <link rel="stylesheet" href="nav-bar.css" />
  <title>&bullet; Announcement Archive</title>
</head>

<body>
  <!-- -----------------------NAV BAR--------------------- -->
  <nav>
    <div class="nav1">
      <a href="user-announcement.php"><img src="images/logo-icon.png" class="logo-icon" /></a>
      <h2>Announcement Archive</h2>
    </div>

    <ul class="nav2">
      <li><a href="">UniChat</a></li>
      <li><a href="">Students</a></li>
      <li><a href="about.html">About Us</a></li>
    </ul>
  </nav>

  <!-- ---------------------MAIN FRAME---------------------- -->
  <main id="main-frame">
    <!--PROFILE SECTION -->
    <section id="profile">
      <div class="main-profile">
        <img src="images/fed.jpg" />
        <p><?php echo $full_name; ?></p>
        <p><strong><?php echo $student_id; ?></strong></p>
        <p><strong><?php echo $section; ?></strong></p>
        <hr />
      </div>


      <div class="navigations">
        <ul>
          <li>
            <a href="user-announcement.php"><img src="images/announcement.svg" />Announcements</a>
          </li>
          <li>
            <a href="user-profile.php"><img src="images/profile.svg" />Profile</a>
          </li>
          <li>
            <a href="user-colleagues.php"><img src="images/colleagues.svg" />Colleagues</a>
          </li>
          <li>
            <a href="user-learning_materials.php"><img src="images/learning-m.svg" />Learning Materials</a>
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
      </div>

      <div class="scroll-box">
        <?php if (count($announcements) > 0): ?>
            <?php foreach ($announcements as $ann): ?>
            <div class="board">
                <h3>(<?php echo htmlspecialchars($ann['section']); ?>) <?php echo htmlspecialchars($ann['full_name']); ?></h3>
                <div class="container">
                    <p><?php echo nl2br(htmlspecialchars($ann['message'])); ?></p>
                    <small>Expires: <?php echo date('M d, Y', strtotime($ann['expires_at'])); ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="board">
                <h3>No announcements yet</h3>
                <div class="container">
                    <p>There are no announcements at the moment.</p>
                </div>
            </div>
        <?php endif; ?>
      </div>



    </section>
  </main>

  <!-- ---------------------COPYRIGHT---------------------- -->
  <footer>
    <p>COPYRIGHT &copy; 2025 Announcement Archive. All rights reserved.</p>
  </footer>

</body>

</html>