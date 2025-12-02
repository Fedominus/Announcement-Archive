<?php
    session_start();
?>
   
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
  <link rel="stylesheet" href="sign-up.css" />
  <link rel="stylesheet" href="nav-bar.css" />
  <title>&bullet; AA Sign Up</title>
</head>

<body>
  <main id="sign-up-main">
    <div id="sign-up">
      <section class="sign-up-header">
        <a href="index.php"><img src="images/logo-icon.png" /></a>
        <div>
          <h1>Announcement Archive</h1>
          <h2>Create Account:</h2>
        </div>
      </section>

      <section class="sign-up-page">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="box">
          <div>
            <img src="images/profile.svg" />
            <label for="fullName">Full Name:</label>
          </div>
          <input type="text" name="fullName" placeholder="Lastname, Firstname MI." />
        </div>
        <div class="box">
          <div>
            <img src="images/profile.svg" />
            <label for="SchlID">ID Number:</label>
          </div>
          <input type="text" name="studentID" id="SchlID" placeholder="Ex. 2024-0000" />
        </div>
        <div class="box">
          <div>
            <img src="images/profile.svg" />
            <label for="YandS">Section:</label>
          </div>
          <input type="text" name ="section" id="YandS" placeholder="Year & Section" />
        </div>


        <div class="box">
          <div>
            <img src="images/profile.svg" />
            <label for="username">Username:</label>
          </div>
          <input type="text" id="username" name="username"/>
        </div>
        <div class="box">
          <div>
            <img src="images/password.svg" />
            <label for="password">Password:</label>
          </div>
          <input type="password" id="password" name="password"/>
          <span id="toggle">Show</span>
        </div>
        <div class="box">
          <div>
            <img src="images/password.svg" />
            <label for="c-password">Confirm Password:</label>
          </div>
          <input type="password" id="c-password" name="c-password" />
        </div>
        <div class="box">
          <div>
            <img src="images/profile.svg" />
            <label for="Role" id="Roles">Role: </label>
          </div>
        
          <label>Admin</label>
          <input type="radio" name="choice" value="Admin" class="Role" />
          <label id="ihhh">User</label>
          <input type="radio" name="choice" value="user" class="Role" />
        </div>

        <div>
          <button type="submit" id="sign-up" name="sign-up">Sign Up</button>
        </div>
        <div class="sign-in">
          <p>Already have an account? <a href="login.php">Log In</a></p>
        </div>

        <div id="requirements">
          <ul>
            <li id="length">At least 8 characters</li>
            <li id="upper">One uppercase letter (A-Z)</li>
            <li id="lower">One lowercase letter (a-z)</li>
            <li id="number">One number (0-9)</li>
            <li id="special">One special symbol (e.g., !@#$)</li>
          </ul>
        </div>

      </section>
    </div>
  </main>

  <footer>
    <p>COPYRIGHT &copy; 2025 Announcement Archive. All rights reserved.</p>
  </footer>

  <script src="sign-up.js"></script>
</body>

</html>


<?php

include("userdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $full_name = filter_input(INPUT_POST, "fullName", FILTER_SANITIZE_SPECIAL_CHARS);
    $student_id = filter_input(INPUT_POST, "studentID", FILTER_SANITIZE_SPECIAL_CHARS);
    $section = filter_input(INPUT_POST, "section", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $confirm_password = filter_input(INPUT_POST, "c-password", FILTER_SANITIZE_SPECIAL_CHARS);
    $role = isset($_POST['choice']) ? $_POST['choice'] :'';

    // Validate inputs
    if (empty($full_name)) {
        echo "<script>alert('Please enter your Full Name.');</script>";
    }
    elseif (empty($student_id)) {
        echo "<script>alert('Please enter your Student ID.');</script>";
    }
    elseif (empty($section)) {
        echo "<script>alert('Please enter your section.');</script>";
    }
    elseif (empty($username)) {
        echo "<script>alert('Please enter a username.');</script>";
    }
    elseif (empty($password)) {
        echo "<script>alert('Please enter a password.');</script>";
    }
    elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    }
    elseif (empty($role)) {
        echo "<script>alert('Please select your role.');</script>";
    }
    else {
        // Check if trying to register as admin
        if ($role === 'admin') {
            // Check if an admin already exists
            $check_admin = "SELECT id FROM userdata WHERE role = 'admin' LIMIT 1";
            $result = mysqli_query($conn, $check_admin);
            
            if (mysqli_num_rows($result) > 0) {
              echo
              "<script>
                alert('Admin account already exists. Please sign up as a User.');
                document.addEventListener('DOMContentLoaded', function() {
                  document.querySelector('input[value=\"user\"]').checked = true;
            
                });
                
              </script>";
              mysqli_close($conn);
              exit();
            }
        }

        // Check if username already exists
        $check_username = "SELECT id FROM userdata WHERE username = ? LIMIT 1";
        $stmt_check = mysqli_prepare($conn, $check_username);
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            echo "<script>alert('Username already taken. Please choose another username.');</script>";
            mysqli_stmt_close($stmt_check);
            mysqli_close($conn);
            exit();
        }
        mysqli_stmt_close($stmt_check);

        // Hash the password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement with placeholders
        $sql = "INSERT INTO userdata (full_name, student_id, section, username, password, role) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $full_name, $student_id, $section, $username, $hash, $role);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Account created successfully!'); window.location.href = 'login.php';</script>";
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                exit();
            } else {
                $error = mysqli_stmt_error($stmt);
                echo "<script>alert(" . json_encode("Database Error: " . $error) . ");</script>";
                error_log("Database Error: " . $error);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            $error = mysqli_error($conn);
            echo "<script>alert(" . json_encode("Prepare Error: " . $error) . ");</script>";
            error_log("Prepare Error: " . $error);
        }
    }
}

?>