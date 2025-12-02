<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="images/logo-icon.png" />
    <link rel="stylesheet" href="login.css" />
    <link rel="stylesheet" href="nav-bar.css" />
    <title>&bullet; AArchive Login</title>
</head>

<body>
    <main id="login-main">
        <div id="login">
            <form method="POST" action="login.php">
                <section class="login-header">
                    <a href="login.php"><img src="images/logo-icon.png" /></a>
                    <h1>Announcement Archive</h1>
                </section>

                <section class="login-page">
                    <div>
                        <img src="images/profile.svg"/>
                        <label >Username:</label>
                        <input type="text" name ="username" id="username" required />
                    </div>
                    <div id="passbox">
                        <img src="images/password.svg" />
                        <label>Password:</label>
                        <input type="password" name="password" id="password" required />
                        <span id="toggle">Show</span>
                    </div>
                    
                    <div>
                        <button type="submit" name="login" id="sign-in">Login</button>
                    </div>
                    <div class="sign-up">
                        <p>Dont have an account? <a href="index.php">Sign Up</a></p>
                    </div>
                </section>
            </form>
        </div>
    </main>

    <footer>
      <p>&copy; 2025 Announcement Archive. All rights reserved.</p>
    </footer>

    <script src="login.js"></script>
</body>

</html>



<?php
    include 'userdb.php'; // Database connection ($conn)

    // Check if form was submitted
    if (isset($_POST['login'])) {

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Check empty fields
        if (empty($username) || empty($password)) {
            echo "<script>alert('Please fill in all fields.'); window.location='login.php';</script>";
            exit;   
        }

        $stmt = $conn->prepare("SELECT id, username, password, role FROM userdata WHERE username = ?");
        
        if (!$stmt) {
            echo "<script>alert('Database error: " . json_encode($conn->error) . "'); window.location='login.php';</script>";
            exit;
        }
        
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows === 0) {
            echo "<script>alert('Username not found. Please check your credentials.'); window.location='login.php';</script>";
            exit;
        } 

        // User found - get data
        $row = $result->fetch_assoc();

        // Verify password using password_verify (matches password_hash from signup)
        if (password_verify($password, $row['password'])) {
            // Login successful - Store user info in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];



            // ✅ ROLE-BASED REDIRECT - CHECK ang role from DATABASE
            if ($row['role'] == 'Admin') {
                // ADMIN → announcement.php
                $username = htmlspecialchars($_SESSION['username']);
                echo "<script>
                    alert('Welcome, {$username}!\\nYou are logged in as ADMIN.');
                    window.location='announcement.php';
                </script>";
            }
            else {
                // USER → user-announcement.php
                $username = htmlspecialchars($_SESSION['username']);
                echo "<script>
                    alert('Welcome, {$username}!\\nYou are logged in as USER.');
                    window.location='user-announcement.php';
                </script>";
            }
            exit;

        } 
        else {
            echo "<script>alert('Incorrect password. Please try again.'); window.location='login.php';</script>";
            exit;
        }
        
        $stmt->close();
    }

    $conn->close();
?>


