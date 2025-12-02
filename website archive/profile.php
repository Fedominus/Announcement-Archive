<?php
  session_start();
  include ("userdb.php");

  // CHECK IF LOGGED IN
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
  mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
  <link rel="stylesheet" href="profile.css" />
  <link rel="stylesheet" href="nav-bar.css" />
  <title>&bullet; Profile Information</title>
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
            <a href="learning-materials.php"><img src="images/notes.svg" />Learning Materials</a>
          </li>
          <li>
            <a href="logout.php"><img src="images/user-logout.svg" />Log Out</a>
          </li>
        </ul>
      </div>
    </section>

    <!-- PROFILE INFORMATION -->
    <section id="profile-info">
      <div class="box">
        <h2>Profile Information</h2>
      </div>

      <!-- MAIN INFORMATION -->
      <main>
        <section class="box1">
          <div class="profile">
            <img src="images/fed.jpg" />
            <h5>Photo: (must be 1x1 size)</h5>
            <input id="pfp-upload" type="file" />
          </div>

          <div class="user-input">
            <div>
              <label for="stud-id">Student ID:</label>
              <input type="number" id="stud-id" placeholder="2024-0****" class="disabled" disabled/>
            </div>
            <div>
              <label for="email">CCC Email:</label>
              <input type="email" id="email" placeholder="@ccc.edu.ph" class="disabled" disabled/>
            </div>
          </div>
        </section>

        <!-- BASIC INFORMATION -->
        <section class="box2">
          <div class="scroll-box">
            <main class="box2-b-info">
              <fieldset>
                <legend>Basic Information</legend>
                <div class="info">
                  <div>
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="first-name">First Name:</label>
                    <input type="name" id="first-name" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="middle-ini">Middle Name:</label>
                    <input type="name" id="middle-ini" class="disabled" disabled/>
                  </div>
                </div>

                <div class="info">
                  <div>
                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender" class="disabled" disabled>
                      <option value="">Male</option>
                      <option value="">Female</option>
                    </select>
                  </div>
                  <div>
                    <label for="age">Age:</label>
                    <input type="number" id="age" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="birth-date">Date of Birth:</label>
                    <input type="date" id="birth-date" class="disabled" disabled/>
                  </div>
                </div>

                <div class="info">
                  <div>
                    <label for="program">Program:</label>
                    <select name="program" id="program" class="disabled" disabled>
                      <optgroup label="Department of Teacher Education (DTE)">
                        <option value="">
                          &bullet; Bachelor of Elementary Education
                        </option>
                        <option value="">
                          &bullet; Bachelor of Early Childhood Education
                        </option>
                        <option value="">
                          &bullet; Bachelor of Secondary Education
                        </option>
                      </optgroup>

                      <optgroup label="Department of Business and Accountancy (DBA)">
                        <option value="">
                          &bullet; Bachelor of Science in Accountancy
                        </option>
                        <option value="">
                          &bullet; Bachelor of Science in Accounting
                          Information System
                        </option>
                        <option value="">
                          &bullet; Bachelor of Science in Entrepreneurship
                        </option>
                      </optgroup>

                      <optgroup label="Department of Computing and Informatics (DCI)">
                        <option value="">
                          &bullet; Bachelor of Science in Computer Science
                        </option>
                        <option value="">
                          &bullet; Bachelor of Science in Information
                          Technology
                        </option>
                      </optgroup>

                      <optgroup label="Department of Arts and Sciences (DAS)">
                        <option value="">
                          &bullet; Bachelor of Science in Psychology
                        </option>
                        <option value="">
                          &bullet; Bachelor of Arts in Communication
                        </option>
                        <option value="">
                          &bullet; Bachelor of Science in Public
                          Administration
                        </option>
                        <option value="">
                          &bullet; Bachelor of Science in Social Work
                        </option>
                      </optgroup>
                    </select>
                  </div>

                  <div>
                    <label for="year-level">Year Level:</label>
                    <select name="year-level" id="year-level" class="disabled" disabled>
                      <option value="">1st Year</option>
                      <option value="">2nd Year</option>
                      <option value="">3rd Year</option>
                      <option value="">4th Year</option>
                    </select>
                  </div>
                </div>
              </fieldset>
            </main>

            <!-- CONTACT INFORMATION -->
            <main class="box2-c-info">
              <fieldset>
                <legend>Contact Information</legend>
                <div class="info">
                  <div>
                    <label for="parent">Parent Name:</label>
                    <input type="text" id="parent" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="phone-no">Contact No.:</label>
                    <input type="number" id="phone-no" placeholder="09**-****-****" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="address">Address:</label>
                    <input type="text" id="address" class="disabled" disabled>
                  </div>
                </div>
              </fieldset>
            </main>

            <!-- PASSWORD -->
            <main class="box2-password">
              <fieldset>
                <legend>Password</legend>
                <button type="button" id="toggleBtn"><img src="images/hide.png" id="toggleImg"/></button>
                <div class="info">
                  <div>
                    <label for="c-password">Current Password:</label>
                    <input type="password" id="c-password" placeholder="**********" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="n-password">New Password:</label>
                    <input type="password" id="n-password" placeholder="**********" class="disabled" disabled/>
                  </div>
                  <div>
                    <label for="cf-password">Confirm Password:</label>
                    <input type="password" id="cf-password" placeholder="**********" class="disabled" disabled/>
                  </div>
                </div>
              </fieldset>
            </main>
          </div>

          <div class="button">
            <button type="button" id="edit-but">Edit Information 🗒️</button>
          </div>
        </section>
      </main>
    </section>
  </main>
  <!-- COPYRIGHT -->
  <footer>
    <p>&copy; 2025 Announcement Archive. All rights reserved.</p>
  </footer>
  <script src="profile.js"></script>
</body>

</html>
