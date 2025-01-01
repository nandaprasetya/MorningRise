<?php
    include 'koneksi.php';
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

    $sqlProduk = "SELECT p.* 
    FROM produk p
    JOIN likes l ON p.id_produk = l.id_produk
    WHERE l.id_user = $id_user ";
    $resultProduk = $conn->query($sqlProduk);

    $sqlKategori = "SELECT * FROM kategori";
    $resultKategori = $conn->query($sqlKategori);

    $likedProduk = [];
    if (isset($_SESSION['id_user'])) {
        $idUser = $_SESSION['id_user'];
        $sqlLikes = "SELECT id_produk FROM likes WHERE id_user = $idUser";
        $resultLikes = $conn->query($sqlLikes);
        while ($row = $resultLikes->fetch_assoc()) {
            $likedProduk[] = $row['id_produk'];
        }
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
                    <img class="mr-4" src="media/dashboard.png" alt="">
                    <h1 class="font-medium">Dashboard User</h1>
                </a>
                <a href="whishlist.php" class="flex items-center">
                    <img class="mr-4" src="media/like-active.png" alt="">
                    <h1 class="font-medium text-amber-400">Whishlist</h1>
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
            <h1>Whishlist</h1>
            <div id="produkContainer" class="area-whishlist-order flex justify-start flex-wrap">
            <?php 
            if($resultProduk->num_rows > 0){ 
                while ($row = $resultProduk->fetch_assoc()) {
            ?>
            <div class="box-produk w-2/6 flex flex-col relative p-2">
                <div class="img-box-produk relative w-full">
                    <div class="outer-like-produk w-full flex justify-end">
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <div data-like="like" data-id-produk="<?php echo $row['id_produk']; ?>" class="like-produk bg-white m-3 flex justify-center items-center">
                            <img src="media/<?php echo in_array($row['id_produk'], $likedProduk) ? 'liked.png' : 'like.png'; ?>" alt="">
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="like-produk bg-white m-3 flex justify-center items-center">
                            <img src="media/like.png" alt="">
                        </a>
                    <?php endif; ?>
                    </div>
                    <div class="outer-gizi-produk w-full flex justify-center">
                        <div class="box-gizi-produk w-10/12 px-3 py-4 mb-4 rounded bg-white flex">
                            <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                <img src="media/kalori.png" class="h-5 w-5 mr-2" alt="">
                                <h6 class="font-semibold"><?php echo $row['kalori']; ?></h6>
                            </div>
                            <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                <img src="media/karbo.png" class="h-5 w-5 mr-2" alt="">
                                <h6 class="font-semibold"><?php echo $row['karbo']; ?></h6>
                            </div>
                            <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                <img src="media/protein.png" class="h-5 w-5 mr-2" alt="">
                                <h6 class="font-semibold"><?php echo $row['protein']; ?></h6>
                            </div>
                            <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                <img src="media/lemak.png" class="h-5 w-5 mr-2" alt="">
                                <h6 class="font-semibold"><?php echo $row['lemak']; ?></h6>
                            </div>
                        </div>
                    </div>
                    <img class="preview-produk w-full" data-id-produk="<?php echo $row['id_produk']; ?>" src="media/uploads/<?php echo $row['gambar']; ?>" alt="">
                </div>
                <h5 class="judul-produk mt-2 font-semibold" data-id-produk="<?php echo $row['id_produk']; ?>"><?php echo $row['nama'] ?></h5>
                <h6 class="harga-produk font-semibold" data-id-produk="<?php echo $row['id_produk']; ?>">Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></h5>
            </div>

            <?php } } ?>
            </div>
        </div>
    </div>
    <script src="plugins/jquery.js"></script>
    <script src="plugins/gsap.min.js"></script>
    <script src="plugins/scrollTrigger.min.js"></script>
    <script src="plugins/lenis.min.js"></script>
    <script>
        const lenis = new Lenis();
        lenis.on('scroll', (e) => {
        });
        function raf(time) {
            lenis.raf(time)
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);
        $(document).on('mouseenter', '.box-produk', function() {
            $(this).find(".box-gizi-produk").addClass("active");
            $(this).find(".col-gizi-produk").addClass("active");
            $(this).find(".like-produk").addClass("active");
            $(this).find(".like-produk img").addClass("active");
        });

        $(document).on('mouseleave', '.box-produk', function() {
            $(this).find(".box-gizi-produk").removeClass("active");
            $(this).find(".col-gizi-produk").removeClass("active");
            $(this).find(".like-produk").removeClass("active");
            $(this).find(".like-produk img").removeClass("active");
        });

        $(document).on('click', '.like-produk', function(){
            let $this = $(this);
            let idProduk = $(this).attr('data-id-produk');
            $.ajax({
                url: 'prosesProduk.php', 
                type: 'POST',
                data: { action: 'like', idProduk: idProduk}, 
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'error') {
                        console.log('error');
                    } else if (response.status === 'success') {
                        if (response.liked) {
                            $this.find("img").attr("src", "media/liked.png");
                            $this.attr('data-like', 'liked');
                        } else {
                            $this.find("img").attr("src", "media/like.png");
                            $this.attr('data-like', 'like');
                        }
                    }
                },
                error: function () {
                    console.log('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
            $.ajax({
                url: 'getProduk.php?action=getLikeProduk',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    $('#produkContainer').empty(); 
                    if (response.length > 0) {
                        response.forEach(function(produk) {
                            var isLiked = produk.liked_by_user ? 'liked.png' : 'like.png';
                            var produkHTML = `
                                <div class="box-produk w-2/6 flex flex-col relative p-2">
                                    <div class="img-box-produk relative w-full">
                                        <div class="outer-like-produk w-full flex justify-end">
                                                <div data-like="like" data-id-produk="${produk.id_produk}" class="like-produk bg-white m-3 flex justify-center items-center">
                                                    <img src="media/${isLiked}" alt="">
                                                </div>
                                        </div>
                                        <div class="outer-gizi-produk w-full flex justify-center">
                                            <div class="box-gizi-produk w-10/12 px-3 py-4 mb-4 rounded bg-white flex">
                                                <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                                    <img src="media/kalori.png" class="h-5 w-5 mr-2" alt="">
                                                    <h6 class="font-semibold">${produk.kalori}</h6>
                                                </div>
                                                <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                                    <img src="media/karbo.png" class="h-5 w-5 mr-2" alt="">
                                                    <h6 class="font-semibold">${produk.karbo}</h6>
                                                </div>
                                                <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                                    <img src="media/protein.png" class="h-5 w-5 mr-2" alt="">
                                                    <h6 class="font-semibold">${produk.protein}</h6>
                                                </div>
                                                <div class="col-gizi-produk flex w-fit mx-2 items-center">
                                                    <img src="media/lemak.png" class="h-5 w-5 mr-2" alt="">
                                                    <h6 class="font-semibold">${produk.lemak}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <img class="preview-produk w-full" data-id-produk="${produk.id_produk}" src="media/uploads/${produk.gambar}" alt="">
                                    </div>
                                    <h5 class="judul-produk mt-2 font-semibold" data-id-produk="${produk.id_produk}">${produk.nama}</h5>
                                    <h6 class="harga-produk font-semibold" data-id-produk="${produk.id_produk}">Rp. ${produk.harga.toLocaleString()}</h6>
                                </div>
                            `;

                            
                            $('#produkContainer').append(produkHTML); 
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $(".preview-produk").click(function(){
            let idProduk = $(this).attr('data-id-produk');
            window.location.href = "detailProduk.php?id_produk=" + idProduk +"";
        });
        $(".judul-produk").click(function(){
            let idProduk = $(this).attr('data-id-produk');
            window.location.href = "detailProduk.php?id_produk=" + idProduk +"";
        });
        $(".harga-produk").click(function(){
            let idProduk = $(this).attr('data-id-produk');
            window.location.href = "detailProduk.php?id_produk=" + idProduk +"";
        });
    </script>
</body>
</html>