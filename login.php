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
                <h1>Welcome Back</h1>
                <p>Welcome back! Please enter your details</p>
                <form action="user.php?action=login" method="POST">
                    <label for="login-email">Email</label>
                    <input type="email" name="email" id="login-email" placeholder="Your Email">
                    <label for="login-password">Password</label>
                        <input type="password" name="password" id="login-password" placeholder="Your Password">
                    <button type="submit">Log In</button>
                    <p>Dont't have an account? <a href="signup.php">Sign up for free</a></p>
                </form>
            </div>
        </div>
        <div class="image-login">
            <img src="media/bg-login.jpg" alt="">
        </div>
    </div>
</body>
</html>