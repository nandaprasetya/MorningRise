<?php
session_start();

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
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
    <div class="outer-login">
        <div class="outer-login-form">
            <div class="login-form">
                <h1>Let's Get Started</h1>
                <p>Welcome to Morning Rise! Let's Create your Account</p>
                <form action="user.php?action=signup" method="POST">
                    <label for="login-username">Username</label>
                    <input type="text" name="username" id="login-username" placeholder="Create Your Username">
                    <label for="login-email">Email</label>
                    <input type="email" name="email" id="login-email" placeholder="Create Your Email">
                    <label for="login-password">Password</label>
                    <input type="password" name="password" id="login-password" placeholder="Create Your Password">
                    <button type="submit">Sign Up</button>
                    <p>Already have an account? <a href="signup.php">Log in</a></p>
                </form>
            </div>
        </div>
        <div class="image-login">
            <img src="media/bg-login.jpg" alt="">
        </div>
    </div>
</body>
</html> 