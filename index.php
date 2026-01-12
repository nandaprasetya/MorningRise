<?php
    require 'koneksi.php';
    session_start();

    $sqlProduk = "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 4";
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
    <title>Morning Rise</title>
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
    <div class="home">
        <div class="text-home">
            <div class=""></div>
            <h1>Happiness is Fresh Out <br> of the Oven Testing MJS</h1>
            <div class="scroll-bottom">
                <div class="inner-scroll-bottom">
                    <p>SCROLL</p>
                </div>
            </div>
        </div>
        <video id="home-video" src="media/background-home.webm" muted autoplay loop></video>
    </div>
    <div class="about-home">
        <div class="left-img-home-about">
            <img src="media/bg-login.jpg" alt="">
        </div>
        <div class="text-home-about">
            <h1>MORNING RISE</h1>
            <p>Discover the heart and passion behind our bakery. Learn about our journey, our values, and what makes us special. Find out how we're making a difference, one pastry at a time.</p>
            <a href="about.php">
                <div class="about-home-circle"></div>
                <span>Learn About Us</span>
            </a>
        </div>
        <div class="right-img-home-about">
            <img src="media/background-about-home.jpg" alt="">
        </div>
    </div>
    <div class="header-area-produk w-full px-10 flex justify-between">
        <h1>Experience the Freshness <br> of Our New Creations</h1>
        <p class="w-4/12 text-end">Our new products are here! Freshly baked, full of flavors, and ready to satisfy your cravings. Experience something special today!</p>
    </div>
    <div class="area-produk w-full px-8 py-8 flex justify-start flex-wrap">
        <?php 
            if($resultProduk->num_rows > 0){ 
                while ($row = $resultProduk->fetch_assoc()) {
        ?>
        <div class="box-produk w-1/4 flex flex-col relative p-2">
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
    <div class="more-produk-home w-full h-fit flex justify-center">
        <a href="produk.php">
            <div class="produk-home-circle"></div>
            <span>More Product</span>
        </a>
    </div>
    <script src="plugins/jquery.js"></script>
    <script src="plugins/gsap.min.js"></script>
    <script src="plugins/ScrollTrigger.min.js"></script>
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
        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray(".home").forEach(function(container) {
        let videoHome = container.querySelector("video");
  
        gsap.to(videoHome, {
            y: () => videoHome.offsetHeight - container.offsetHeight,
            ease: "none",
            scrollTrigger: {
            trigger: container,
            scrub: true,
            pin: false,
            invalidateOnRefresh: true
            },
        }); 
        });

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
