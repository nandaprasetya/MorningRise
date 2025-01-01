<?php
    include 'koneksi.php';
    session_start();
    $sqlProduk = "SELECT * FROM produk ORDER BY terjual DESC";
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
    <title>Morning Rise | Produk</title>
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
    <div class="header-view-produk px-10 w-full h-fit">
        <div class="search-area w-full h-fit flex justify-center">
            <form id="searchForm" class="outer-search rounded-md bg-gray-100 w-3/6 relative flex items-center">
                <input id="search" class="py-4 pl-5 focus:outline-none focus:ring-0 rounded-md w-full bg-gray-100" type="text" name="search" placeholder="What do you want to eat?">
                <button type="submit" class="search-btn h-full flex items-center justify-center">
                    <img src="media/search.png" alt="">
                </button>
            </form>
        </div>
        <div class="sort-produk mt-10 flex items-center justify-between">
        <div class="outer-filter w-fit relative">
                <div class="filter-search w-fit flex items-center px-3 py-3 rounded-md focus:outline-none focus:ring-0 border border-slate-200 border-solid font-medium">
                    <img src="media/filter.png" class="w-5 h-5 mr-3" alt="">
                    <h5>Filter</h5>
                </div>
                <div class="filter-popup hidden w-fit h-fit flex flex-col absolute bg-white border border-solid border-slate-200 rounded mt-1 p-4 z-[12]">
                    <h6 class="py-1 w-fit mb-2 border-b-2 border-black border-solid">PRICE</h6>
                    <div class="area-filter-harga flex">
                        <div class="min-harga mr-2">
                            <p>Minimum</p>
                            <div class="box-filter-harga w-fit pl-3 flex items-center border border-solid border-slate-300 rounded">
                                <p>Rp.</p>
                                <input id="min-harga" class="border-none rounded focus:outline-none focus:ring-0" style="width: 150px;" type="number" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                        <div class="max-harga ml-2">
                            <p>Maximum</p>
                            <div class="box-filter-harga w-fit pl-3 flex items-center border border-solid border-slate-300 rounded">
                                <p>Rp.</p>
                                <input id="max-harga" class="border-none rounded focus:outline-none focus:ring-0" style="width: 150px;" type="number" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex">
                        <div id="apply-filter" class="flex mr-1 w-3/6 justify-center items-center mt-3 py-3 bg-amber-300 text-sm tracking-wider font-semibold" style="cursor: pointer;">
                            <p>APPLY</p>
                        </div>
                        <div id="reset-filter" class="flex ml-1 w-3/6 justify-center items-center mt-3 py-3 bg-red-500 text-white text-sm tracking-wider font-semibold" style="cursor: pointer;">
                            <p>DELETE</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kategori-produk w-fit flex justify-center">
                <h6 data-kategori="semua" class="list-kategori kategori-active text-sm mx-1 font-medium px-6 py-2 rounded-md bg-gray-100">All</h6>
                <?php  
                    if($resultKategori->num_rows > 0){ 
                        while ($rowKategori = $resultKategori->fetch_assoc()) {
                ?>
                    <h6 data-kategori="<?php echo $rowKategori['nama_kategori']; ?>" class="list-kategori text-sm mx-1 font-medium px-6 py-2 rounded-md "><?php echo $rowKategori['nama_kategori'] ?></h6>
                <?php } }?>
            </div>
            <select name="sorting" id="sorting-section" class="px-3 py-3 rounded-md focus:outline-none focus:ring-0 border border-slate-200 border-solid font-medium">
                <option value="terlaris">Recommended</option>
                <option value="terbaru">Newest</option>
                <option value="termurah">Lowest Price</option>
                <option value="termahal">Highest Price</option>
            </select>
        </div>
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

        let isFilter = 0;

        $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var query = $("#search").val();
        let sort = $("#sorting-section").val();
        let kategori = $(".kategori-active").attr('data-kategori');
        let minHarga = $("#min-harga").val();
            let maxHarga = $("#max-harga").val();
            if(isFilter === 0){
                minHarga = null;
                maxHarga = null;
            }
        
            $.ajax({
                url: 'searchProduk.php', 
                type: 'GET',
                data: { query: query, action: 'search', kategori: kategori, sort: sort, minHarga: minHarga, maxHarga: maxHarga}, 
                success: function(response) {
                    $('.area-produk').html(response); 
                },
                error: function() {
                    $('.area-produk').html('<p>Terjadi kesalahan.</p>');
                }
            });
        });

        $(".list-kategori").click(function(){
            $(".list-kategori").removeClass("kategori-active");
            $(".list-kategori").removeClass("bg-gray-100");
            $(this).addClass("kategori-active");
            $(this).addClass("bg-gray-100");
            var query = $("#search").val();
            let sort = $("#sorting-section").val();
            let kategori = $(".kategori-active").attr('data-kategori');
            let minHarga = $("#min-harga").val();
            let maxHarga = $("#max-harga").val();
            if(isFilter === 0){
                minHarga = null;
                maxHarga = null;
            }
            $.ajax({
                url: 'searchProduk.php', 
                type: 'GET',
                data: { query: query, action: 'search', kategori: kategori, sort: sort, minHarga: minHarga, maxHarga: maxHarga}, 
                success: function(response) {
                    $('.area-produk').html(response); 
                },
                error: function() {
                    $('.area-produk').html('<p>Terjadi kesalahan.</p>');
                }
            });
        });
        
        $("#sorting-section").change(function(){
            var query = $("#search").val();
            let sort = $("#sorting-section").val();
            let kategori = $(".kategori-active").attr('data-kategori');
            let minHarga = $("#min-harga").val();
            let maxHarga = $("#max-harga").val();
            if(isFilter === 0){
                minHarga = null;
                maxHarga = null;
            }
            $.ajax({
                url: 'searchProduk.php', 
                type: 'GET',
                data: { query: query, action: 'search', kategori: kategori, sort: sort, minHarga: minHarga, maxHarga: maxHarga}, 
                success: function(response) {
                    $('.area-produk').html(response); 
                },
                error: function() {
                    $('.area-produk').html('<p>Terjadi kesalahan.</p>');
                }
            });
        });

        $(".filter-search").click(function(){
            $(".filter-popup").toggleClass("hidden");
        });

        $("#apply-filter").click(function(){
            var query = $("#search").val();
            let sort = $("#sorting-section").val();
            let kategori = $(".kategori-active").attr('data-kategori');
            let minHarga = $("#min-harga").val();
            let maxHarga = $("#max-harga").val();
            $.ajax({
                url: 'searchProduk.php', 
                type: 'GET',
                data: { query: query, action: 'search', kategori: kategori, sort: sort, minHarga: minHarga, maxHarga: maxHarga}, 
                success: function(response) {
                    $('.area-produk').html(response); 
                },
                error: function() {
                    $('.area-produk').html('<p>Terjadi kesalahan.</p>');
                }
            });

            isFilter = 1;
        });

        $("#reset-filter").click(function(){
            $("#min-harga").val('');
            $("#max-harga").val('');
            var query = $("#search").val();
            let sort = $("#sorting-section").val();
            let kategori = $(".kategori-active").attr('data-kategori');
            let minHarga = $("#min-harga").val();
            let maxHarga = $("#max-harga").val();
            $.ajax({
                url: 'searchProduk.php', 
                type: 'GET',
                data: { query: query, action: 'search', kategori: kategori, sort: sort, minHarga: minHarga, maxHarga: maxHarga}, 
                success: function(response) {
                    $('.area-produk').html(response); 
                },
                error: function() {
                    $('.area-produk').html('<p>Terjadi kesalahan.</p>');
                }
            });
            isFilter = 0;
        });
    </script>
</body>
</html>