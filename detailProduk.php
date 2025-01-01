<?php
    include 'koneksi.php';
    session_start();
    $idProduk = $_GET['id_produk'];
    if($idProduk == null){
        header("Location: index.php");
    }

    $result = $conn->query("SELECT * FROM produk WHERE id_produk = $idProduk");
    if ($result->num_rows > 0) {
        $produk = $result->fetch_assoc();
    }else{
        header("Location: index.php");
    }
    $idUser = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

    if ($idUser) {
    $idProduk = $produk['id_produk'];
    $sqlLikes = "SELECT id_like FROM likes WHERE id_user = $idUser AND id_produk = $idProduk";
    $resultLikes = $conn->query($sqlLikes);
    $like = $resultLikes->fetch_assoc();
    } else {
        $like = null;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk | <?php echo $produk['nama']; ?></title>
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
    <div class="outer-detail-produk w-full min-h-screen h-full flex justify-evenly">
        <div class="img-preview-detail-produk w-6/12">
            <img src="media/uploads/<?php echo $produk['gambar']; ?>" alt="">
        </div>
        <div class="text-preview-detail-produk flex flex-col w-4/12">
            <h1><?php echo $produk['nama']; ?></h1>
            <h2>Rp. <?php echo number_format($produk['harga'], 0, ',', '.'); ?></h2>
            <p><?php echo $produk['desk']; ?></p>
            <div class="input-qty flex items-center mt-10 mb-5">
                <h6 id="kurangJumlah" class="font-semibold flex items-center justify-center">-</h6>
                <input type="number" name="" id="banyakProduk" value="1" min="1" max="1000" class="border-none focus:outline-none focus:ring-0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <h6 id="tambahJumlah" class="font-semibold flex items-center justify-center">+</h6>
            </div>
            <div id="addCartBtn" data-id-produk="<?php echo $produk['id_produk']; ?>" class="w-full h-fit flex justify-center items-center px-5 py-4 bg-amber-300">
                <img src="media/bag.png" alt="">
                <h6>ADD TO BAG</h6>
            </div>
            
            <div id="likeBtn" data-id-produk="<?php echo $produk['id_produk']; ?>" class="w-full h-fit flex justify-center items-center px-5 py-4 bg-gray-100">
                <?php if($like): ?>
                <img class="like-icon" src="media/liked.png" alt="">
                <?php else: ?>
                <img class="like-icon" src="media/like.png" alt="">
                <?php endif; ?>
                <h6>ADD TO WHISHLIST</h6>
            </div>
        </div>
    </div>
    <script src="plugins/jquery.js"></script>
    <script>
        $("#tambahJumlah").click(function(){
            if(parseInt($("#banyakProduk").val()) < 1000){
                let tambahProduk = parseInt($("#banyakProduk").val()) + 1;
                $("#banyakProduk").val(tambahProduk);
            }
        });
        
        $("#kurangJumlah").click(function(){
            if(parseInt($("#banyakProduk").val()) > 1){
                let kurangProduk = parseInt($("#banyakProduk").val()) - 1;
                $("#banyakProduk").val(kurangProduk);
            }
        });
        $("#addCartBtn").click(function(){
            let idProduk = $(this).attr('data-id-produk');
            let jmlBarang = $("#banyakProduk").val();
            $.ajax({
                url: 'prosesProduk.php', 
                type: 'POST',
                data: { action: 'addCart', idProduk: idProduk, jmlBarang: jmlBarang }, 
                dataType: 'json',
                success: function (response) {
                    console.log("berhasil");
                },
                error: function () {
                    console.log('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
        $("#likeBtn").click(function(){
            let idProduk = $(this).attr('data-id-produk');
            $.ajax({
                url: 'prosesProduk.php', 
                type: 'POST',
                data: { action: 'like', idProduk: idProduk}, 
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'error') {
                        window.location.href = "index.php";
                    } else if (response.status === 'success') {
                        if (response.liked) {
                            $(".like-icon").attr("src", "media/liked.png");
                        } else {
                            $(".like-icon").attr("src", "media/like.png");
                        }
                    }
                },
                error: function () {
                    console.log('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
    </script>
</body>
</html>