<?php
    require 'koneksi.php';
    session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="plugins/tailwind.css">
    <script src="plugins/tailwindcss.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="flex justify-between">
        <a class="nav-logo font-semibold" href="index.php">MORNING RISE</a>
        <div class="nav-link">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="produk.php">Product</a>
            <?php if (isset($_SESSION['role'])):
                 if ($_SESSION['role'] === 'admin'):
            ?>
                <a href="dashboardProduk.php">Dashboard Produk</a>
                <a href="kategori.php">Dashboard Kategori</a>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['id_user'])): ?>
            <div class="ml-6 flex">
                <a href="cart.php">
                    <img src="media/bag.png" alt="">
                </a>
                <a href="profile.php">
                    <img class="rounded-full" src="media/user.png" alt="">
                </a>
            </div>
            <?php else: ?>
            <a href="login.php">Log in</a>
            <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="about min-w-screen w-full relative min-h-screen h-full flex flex-col items-center justify-end">
        <div class="text-about">
            <div></div>
            <div class="identitas-about">
                <h1>2405551043</h1>
                <h1>I Made Nanda <br> Prasetya Dwipayana</h1>
            </div>
            <div class="outer-title-tugas">
                <div class="inner-title-tugas">
                    <div class="box-title-tugas">
                        <h2>Tugas Tool Teknologi Informasi -</h2>
                    </div>
                    <div class="box-title-tugas">
                        <h2>Tugas Tool Teknologi Informasi -</h2>
                    </div>
                    <div class="box-title-tugas">
                        <h2>Tugas Tool Teknologi Informasi -</h2>
                    </div>
                    <div class="box-title-tugas">
                        <h2>Tugas Tool Teknologi Informasi -</h2>
                    </div>
                    <div class="box-title-tugas">
                        <h2>Tugas Tool Teknologi Informasi -</h2>
                    </div>
                    <div class="box-title-tugas">
                        <h2>Tugas Tool Teknologi Informasi -</h2>
                    </div>
                </div>
            </div>
        </div>
        <img src="media/foto-about.png" alt="">
    </div>
    <script src="plugins/jquery.js"></script>
    <script src="plugins/gsap.min.js"></script>
    <script>
        let tween = gsap.to(".box-title-tugas", {
            xPercent: -100,
            repeat: -1,
            duration: 20,
            ease: "linear"
        }).totalProgress(0.5);
    </script>
</body>
</html>