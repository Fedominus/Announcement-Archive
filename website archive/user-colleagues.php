<?php
    session_start();
    include 'userdb.php';

    // Check if logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login first!'); window.location='login.php';</script>";
        exit;
    }

    // Get current user info
    $current_user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];

    // Fetch current user's details
    $user_sql = "SELECT * FROM userdata WHERE id = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $current_user = $user_result->fetch_assoc();

    $full_name = $current_user['full_name'] ?? $username;
    $student_id = $current_user['student_id'] ?? 'N/A';
    $section = $current_user['section'] ?? 'N/A';

    $stmt->close();
    
    //fetching all the colleagues
    $colleagues_sql = "SELECT * FROM userdata WHERE id != ? ORDER BY created_at DESC"; // All users except current user

    $stmt = $conn->prepare($colleagues_sql);
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $colleagues_result = $stmt->get_result();

    $stmt->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
    <link rel="stylesheet" href="colleagues.css" />
    <link rel="stylesheet" href="nav-bar.css" />
    <title>&bullet; Colleagues</title>
    
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
                <p><?php echo htmlspecialchars($full_name); ?></p>
                <p><strong><?php echo htmlspecialchars($student_id); ?></strong></p>
                <p><strong><?php echo htmlspecialchars($section); ?></strong></p>
                <hr/>
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

        <!--COLLEAGUES SECTION -->
        <section id="colleagues-section" style="flex: 1; padding: 30px; overflow-y: auto;">
            <h2 style="margin-bottom: 20px; color: #333;">👥 Colleagues</h2>
            
            <!-- Search Box -->
            <input type="text" 
                   id="searchBox" 
                   class="search-box" 
                   placeholder="🔍 Search by name, student ID, or section..." 
                   onkeyup="searchColleagues()">
            
            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterColleagues('all')">All</button>
            </div>
            
            <!-- Total Count -->
            <div class="total-count">
                Showing <strong id="displayCount"><?php echo mysqli_num_rows($colleagues_result); ?></strong> colleague(s)
            </div>
            
            <!-- Colleagues List -->
            <div id="colleaguesList">
                <?php if (mysqli_num_rows($colleagues_result) > 0): ?>
                    <?php while ($colleague = mysqli_fetch_assoc($colleagues_result)): ?>
                        <?php
                            // Check if user is new (registered within last 7 days)
                            $created_date = new DateTime($colleague['created_at']);
                            $today = new DateTime();
                            $diff = $today->diff($created_date);
                            $is_new = ($diff->days <= 7);
                        ?>
                        
                        <div class="colleague-card" 
                             data-name="<?php echo strtolower($colleague['full_name'] ?? $colleague['username']); ?>"
                             data-id="<?php echo strtolower($colleague['student_id'] ?? ''); ?>"
                             data-section="<?php echo strtolower($colleague['section'] ?? ''); ?>"
                             data-role="<?php echo strtolower($colleague['role']); ?>"
                             data-new="<?php echo $is_new ? '1' : '0'; ?>"
                             data-user-section="<?php echo strtolower($section); ?>">
                            
                            <img src="images/fed.jpg" 
                                 alt="Avatar" 
                                 class="colleague-avatar">
                            
                            <div class="colleague-info">
                                <div class="colleague-name">
                                    <?php echo htmlspecialchars($colleague['full_name'] ?? $colleague['username']); ?>
                                </div>
                                
                                <div class="colleague-details">
                                    📝 <?php echo htmlspecialchars($colleague['student_id'] ?? 'N/A'); ?>
                                </div>
                                
                                <div class="colleague-details">
                                    🏫 <?php echo htmlspecialchars($colleague['section'] ?? 'N/A'); ?>
                                </div>
                                
                                <div class="colleague-details">
                                    📅 Joined: <?php echo date('M d, Y', strtotime($colleague['created_at'])); ?>
                                </div>
                                
                                <div>
                                    <span class="colleague-badge <?php echo $colleague['role'] == 'Admin' ? 'badge-admin' : 'badge-user'; ?>">
                                        <?php echo $colleague['role'] == 'Admin' ? '👑 Admin' : '👤 Student'; ?>
                                    </span>
                                    
                                    <?php if ($is_new): ?>
                                        <span class="colleague-badge badge-new">✨ New</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-colleagues">
                        <h3>👥 No colleagues yet</h3>
                        <p>Be the first to join!</p>
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
<!-- <script src="announcement.js"></script> -->
</html>

<?php $conn->close(); ?>