<?php
    require 'koneksi.php';
    session_start();

    $id_user = $_SESSION['id_user'];
    $sql = "SELECT * FROM users WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
    <div class="outer-profile-area min-h-screen flex justify-evenly">
        <div class="header-profile-area w-3/12 min-w-fit h-fit flex flex-col flex py-6 px-7">
            <div class="innner-header-profile w-fit flex items-center">
                <img class="rounded-full mr-4" src="media/user.png" alt="">
                <div class="text-header-profile flex flex-col">
                    <h1><?php echo $user['username'] ?></h1>
                </div>
            </div>
            <div class="menu-profile w-fit flex flex-col">
                <a href="profile.php" class="flex items-center">
                    <img class="mr-4" src="media/dashboard-active.png" alt="">
                    <h1 class="font-medium text-amber-400">Dashboard User</h1>
                </a>
                <a href="whishlist.php" class="flex items-center">
                    <img class="mr-4" src="media/like.png" alt="">
                    <h1 class="font-medium">Whishlist</h1>
                </a>
                <a href="oreder.php" class="flex items-center">
                    <img class="mr-4" src="media/order.png" alt="">
                    <h1 class="font-medium">Order</h1>
                </a>
                <a href="user.php?action=logout" class="flex items-center">
                    <img class="mr-4" src="media/logout.png" alt="">
                    <h1 class="font-medium">Log out</h1>
                </a>
            </div>
        </div>
        <div class="area-profile-content flex flex-col">
            <h1>User Dashboard</h1>
            <div class="area-total-order flex justify-around">
                <div class="box-total-order">
                    <h2>0</h2>
                    <h3>Total Order</h3>
                </div>
                <div class="box-total-order">
                    <h2>0</h2>
                    <h3>Order Complete</h3>
                </div>
                <div class="box-total-order">
                    <h2>0</h2>
                    <h3>Order Pending</h3>
                </div>
            </div>
        </div>
    </div>
</body>
</html>