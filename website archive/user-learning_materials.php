 <?php
    session_start();
    include ("userdb.php");

    // CHECK IF LOGGED IN
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        echo "<script>
            alert('Please login first!');
            window.location='login.php';
        </script>";
        exit;
    }

    // CHECK IF USER (not Admin)
    if ($_SESSION['role'] != 'User') {
        echo "<script>
            alert('Access Denied! This page is for Users only.');
            window.location='announcement.php';
        </script>";
        exit;
    }

    // LOGOUT HANDLER
    if (isset($_GET['logout'])) {
        session_destroy();
        echo "<script>
            alert('You have been logged out.');
            window.location='login.php';
        </script>";
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

    // Fetch learning materials from database
    $materials = array();
    $materials_sql = "SELECT * FROM learning_materials ORDER BY upload_date DESC";
    $materials_result = mysqli_query($conn, $materials_sql);
    if (!$materials_result) {
        die("Database error: " . mysqli_error($conn));
    }

    while ($material = mysqli_fetch_assoc($materials_result)) {
        $materials[] = $material;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
    <link rel="stylesheet" href="learning-materials.css" />
    <link rel="stylesheet" href="nav-bar.css" />
    <title>&bullet; Learning Materials</title>
</head>

<body>
    <!-- -----------------------NAV BAR--------------------- -->
    <nav>
        <div class="nav1">
            <a href="user-announcement.php"><img src="images/logo-icon.png" class="logo-icon" /></a>
            <h2>Announcement Archive</h2>
        </div>

        <ul class="nav2">
            <li><a href="user-unichat.php">UniChat</a></li>
            <li><a href="user-colleagues.php">Students</a></li>
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

        <!--LM SECTION -->
        <section id="learning-material">
            <div class="box">
                <h2>Learning Materials🗂️</h2>
                <div id="container">
                    <label for="course-sub">Course Subject:</label>
                    <select class="course" id="subjectSelect" name="course-sub" onchange="filterMaterials()">
                        <option value="all">All Subjects</option>
                        <optgroup label="Major Subjects">
                            <option value="IT-221">IT-221 (WebDev)</option>
                            <option value="IT-211">IT-211 (DBMS)</option>
                            <option value="NET-201">NET-201</option>
                        </optgroup>

                        <optgroup label="Minor Subjects">
                            <option value="RIZAL-201">RIZAL-201</option>
                            <option value="ACCTG-201">ACCTG-201</option>
                            <option value="ENV-201">ENV-201</option>
                            <option value="DIS-201">DIS-201</option>
                            <option value="RPH-201">RPH-201</option>
                            <option value="PATHFit-3">PATHFit 3</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="scroll-box" id="materialsContainer">
                <?php if (empty($materials)): ?>
                    <div class="no-materials">
                        <p>No learning materials available yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($materials as $material): ?>
                        <div class="material-item" data-subject="<?php echo htmlspecialchars($material['subject_code']); ?>">
                            <div class="material-content">
                                <?php
                                $file_ext = pathinfo($material['file_name'], PATHINFO_EXTENSION);
                                $icon = 'images/file-icon.png'; // Default icon
                                
                                // Set icon based on file type
                                if (in_array($file_ext, ['pdf'])) {
                                    $icon = 'images/pdf.png';
                                } elseif (in_array($file_ext, ['doc', 'docx'])) {
                                    $icon = 'images/word.png';
                                } elseif (in_array($file_ext, ['ppt', 'pptx'])) {
                                    $icon = 'images/ppt.png';
                                } elseif (in_array($file_ext, ['xls', 'xlsx'])) {
                                    $icon = 'images/excel.png';
                                } elseif (in_array($file_ext, ['zip', 'rar'])) {
                                    $icon = 'images/zip.png';
                                }
                                ?>
                                <img src="<?php echo $icon; ?>" alt="<?php echo $file_ext; ?> file" />
                                <div class="material-info">
                                    <h4><?php echo htmlspecialchars($material['file_name']); ?></h4>
                                    <p class="subject">Subject: <?php echo htmlspecialchars($material['subject_code']); ?></p>
                                    <p class="date">Uploaded: <?php echo date('M d, Y', strtotime($material['upload_date'])); ?></p>
                                </div>
                                <div class="material-actions">
                                    <a href="download_material.php?id=<?php echo $material['id']; ?>" class="download-btn">
                                        <img src="images/download-icon.svg" alt="Download" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="user-learning-materials.js"></script>
</body>

</html>