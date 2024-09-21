<?php
session_start(); // เริ่มต้น session ที่จุดเริ่มต้นของไฟล์

include_once 'database.php';
include_once 'user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    // พยายามล็อกอิน
    if ($user->login()) {
        $_SESSION['username'] = $user->username; // เริ่มต้น session สำหรับผู้ใช้ที่ล็อกอิน
        header('Location: homepage.php');
        exit();
    } else {
        echo "<p style='color: red;'>Login failed. Please check your username and password.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <h2 id="form-title">Login</h2>
            <form id="auth-form" method="POST">
                <div class="input-container">
                    <input type="text" id="username" name="username" placeholder="Type your username" required>
                    <span class="icon">&#128100;</span>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="Type your password" required>
                    <span class="icon">&#128274;</span>
                </div>
                <button type="submit" class="btn" id="submit-btn">Login</button>
            
                <p class="signup-text" id="toggle-text">Or Sign Up Using <a href="#" id="toggle-form">SIGN UP</a></p>
            </form>
            
        </div>
    </div>

    <script src="script.js" defer></script>
</body>
</html>
