<?php
// learning-materials.php
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

// CHECK IF ADMIN
if ($_SESSION['role'] != 'Admin') {
    echo "<script>
        alert('Access Denied! This page is for Admins only.');
        window.location='user-announcement.php';
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

  mysqli_stmt_close($stmt);

// ============ FETCH LEARNING MATERIALS ============
$materials_sql = "SELECT * FROM learning_materials ORDER BY uploaded_date DESC";
$materials_result = mysqli_query($conn, $materials_sql);

// Store materials in array grouped by subject
$materials_by_subject = [];
if($materials_result) {
    while($material = mysqli_fetch_assoc($materials_result)) {
        $subject = $material['subject_code'];
        if(!isset($materials_by_subject[$subject])) {
            $materials_by_subject[$subject] = [];
        }
        $materials_by_subject[$subject][] = $material;
    }
}

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
            <a href="announcement.php"><img src="images/logo-icon.png" class="logo-icon" /></a>
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
                        <a href="announcement.php"><img src="images/announcement.svg" />Announcements</a>
                    </li>
                    <li>
                        <a href="profile.php"><img src="images/profile.svg" />Profile</a>
                    </li>
                    <li>
                        <a href="colleagues.php"><img src="images/colleagues.svg" />Colleagues</a>
                    </li>
                    <li>
                        <a href="learning-materials.php"><img src="images/learning-m.svg" />Learning Materials</a>
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
                    <select class="course" id="select69" name="course-sub">
                        <option value="">Select</option>
                        <optgroup label="Major Subjects">
                            <option value="container2">IT-221 (WebDev)</option>
                            <option value="container3">IT-211 (DBMS)</option>
                            <option value="container4">NET-201</option>
                        </optgroup>

                        <optgroup label="Minor Subjects">
                            <option value="container5">RIZAL-201</option>
                            <option value="container6">ACCTG-201</option>
                            <option value="container7">ENV-201</option>
                            <option value="container8">DIS-201</option>
                            <option value="container9">RPH-201</option>
                            <option value="container10">PATHFit 3</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="scroll-box">

                <div id="container2" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('IT-221')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-IT-221" class="files-container"></div>
                </div>

                <div id="container3" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('IT-211')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-IT-211" class="files-container"></div>
                </div>


                <div id="container4" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('NET-201')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-NET-201" class="files-container"></div>
                </div>

                <div id="container5" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('RIZAL-201')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-RIZAL-201" class="files-container"></div>
                </div>

                <div id="container6" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('ACCTG-201')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-ACCTG-201" class="files-container"></div>
                </div>

                <div id="container7" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('ENV-201')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-ENV-201" class="files-container"></div>
                </div>

                <div id="container8" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('DIS-201')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-DIS-201" class="files-container"></div>
                </div>

                <div id="container9" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('RPH-201')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-RPH-201" class="files-container"></div>
                </div>

                <div id="container10" class="content-div" style="display: none;">
                    <div id="add-file">
                        <button type="button" onclick="openUploadModal('PATHFit-3')"><img src="images/add-button.svg"> Add File</button>
                    </div>

                    <!-- Files will be loaded here dynamically -->
                    <div id="files-PATHFit-3" class="files-container"></div>
                </div>


            </div>

        </section>
    </main>

    <!-- ==================== UPLOAD MODAL ==================== -->
    <div id="uploadModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
            <h3 style="margin-bottom: 20px;">Upload File</h3>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" id="subjectCode" name="subject_code">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Select File:</label>
                    <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
                    <p style="margin-top: 5px; font-size: 12px; color: #666;">Allowed: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR (Max 10MB)</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="flex: 1; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Upload</button>
                    <button type="button" onclick="closeUploadModal()" style="flex: 1; padding: 12px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cancel</button>
                </div>
            </form>
            <div id="uploadProgress" style="display: none; margin-top: 20px;">
                <div style="width: 100%; background: #ddd; border-radius: 5px; overflow: hidden;">
                    <div id="progressBar" style="width: 0%; height: 30px; background: #4CAF50; transition: width 0.3s; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="learning-materials.js"></script>
</body>
</html>
<?php   






?>