<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$idUser = $_SESSION['id_user'];

$sqlCart = "SELECT 
                cart.id_cart, 
                produk.id_produk, 
                produk.nama, 
                produk.harga, 
                produk.gambar, 
                cart.jumlah_barang 
            FROM 
                cart 
            JOIN 
                produk 
            ON 
                cart.id_produk = produk.id_produk 
            WHERE 
                cart.id_user = ?";
$stmt = $conn->prepare($sqlCart);
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$subtotal = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $subtotal += $row['jumlah_barang'] * $row['harga'];
}

$discountAmount = 0;

$grandTotal = $subtotal - $discountAmount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | Morning Rise</title>
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
    <div class="outer-cart-area w-full min-h-screen h-full flex justify-evenly">
        <div class="outer-produk-cart-area w-8/12 flex flex-col">
            <div class="head-produk-cart-area flex">
                <h6 class="w-7/12 h-fit py-3 flex font-semibold text-gray-500">PRODUK</h6>
                <h6 class="w-3/12 h-fit py-3 flex font-semibold text-gray-500">QUANTITY</h6>
                <h6 class="w-2/12 h-fit py-3 flex font-semibold text-gray-500">PRICE</h6>
            </div>
            <div class="area-cart-produk w-full h-full flex flex-col">
            <?php if (count($cartItems) > 0): ?>
                <?php foreach ($cartItems as $item): ?>
                <div class="box-cart-produk w-full h-fit flex py-6" data-id-cart="<?php echo $item['id_cart']; ?>" data-id-produk="<?php echo $item['id_produk']; ?>">
                    <div class="title-cart-produk w-7/12 flex">
                        <img src="media/uploads/<?php echo $item['gambar']; ?>" alt="" class="img-cart-produk mr-4">
                        <div class="inner-title-cart-produk flex flex-col">
                            <h1 class="mb-1"><?php echo $item['nama']; ?></h1>
                            <h2 class="mb-3">Pastry</h2>
                            <h1>Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></h1>
                        </div>
                    </div>
                    <div class="area-quantity-cart-produk h-fit flex items-center w-3/12">
                        <div class="quantity-cart-produk w-fit h-fit flex items-center">
                            <h6 class="KurangProduk flex justify-center items-center">-</h6>
                            <input type="number" value="<?php echo $item['jumlah_barang']; ?>" name="" value="1" min="1" max="1000" class="banyakProduk border-none focus:outline-none focus:ring-0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <h6 class="tambahProduk flex justify-center items-center">+</h6>
                        </div>
                        <img class="delete-cart ml-4" src="media/delete-cart.png" alt="">
                    </div>
                    <div class="price-cart-produk">
                        <h6>Rp. <?php echo number_format($item['jumlah_barang'] * $item['harga'], 0, ',', '.'); ?></h6>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Keranjang belanja Anda kosong.</p>
            <?php endif; ?>
            </div>
        </div>
        <div class="outer-checkout-cart w-3/12 h-fit border border-gray-300 rounded">
            <h1 class="header-checkout-cart p-4">Order Summary</h1>
            <div class="area-summary-produk p-4">
                <?php foreach ($cartItems as $item): ?>
                    <p><?php echo $item['jumlah_barang']; ?> x <?php echo $item['nama']; ?></p>
                <?php endforeach; ?>
            </div>
            <div class="area-promo-summary p-4 flex">
                <input type="text" id="diskonCode" class="rounded bg-gray-100 border-none focus:outline-none focus:ring-0" placeholder="Your Dicount Code">
                <h1 id="apply-diskon">Apply</h1>
            </div>
            <div class="subtotal-summary p-4">
                <div class="w-full flex justify-between">
                    <h1>Subtotal</h1>
                    <h1 class="value-subtotal">Rp. <?php echo number_format($subtotal, 0, ',', '.'); ?></h1>
                </div>
                <div class="w-full flex justify-between">
                    <h1>Discount</h1>
                    <h1 class="value-diskon">- Rp. <?php echo number_format($discountAmount, 0, ',', '.'); ?></h1>
                </div>
            </div>
            <div class="grand-total-summary flex justify-between p-4">
                <h1>Grand Total</h1>
                <h1>Rp. <?php echo number_format($grandTotal, 0, ',', '.'); ?></h1>
            </div>
            <div id="checkout-btn" class="m-4 flex justify-center items-center p-4 rounded bg-amber-400">
                <h1>Checkout Now</h1>
            </div>
        </div>
    </div>

    <script src="plugins/jquery.js"></script>
    <script>
        let isDiskon = 0;
        $("#apply-diskon").on("click", function() {
            isDiskon = 1;
            updateSummary();
        });
        $(".tambahProduk").click(function(){
            let cartItem = $(this).closest('.box-cart-produk');
            let inputJumlah = cartItem.find('.banyakProduk');
            let jumlahSekarang = parseInt(inputJumlah.val());
            let idCart = cartItem.data('id-cart');
            let idProduk = cartItem.data('id-produk');

            if (jumlahSekarang < 1000) {
                let jumlahBaru = jumlahSekarang + 1;
                inputJumlah.val(jumlahBaru);

                $.ajax({
                    url: 'prosesProduk.php',
                    type: 'POST',
                    data: {
                        action: 'updateJumlah',
                        idCart: idCart,
                        jumlahBaru: jumlahBaru
                    },
                    success: function (response) {
                        console.log("Jumlah diperbarui:", response);
                        updatePrice(cartItem, idProduk, jumlahBaru);
                        updateSummary();
                    },
                    error: function () {
                        console.log('Gagal memperbarui jumlah.');
                    }
                });
            }
        });
        
        $(".KurangProduk").click(function () {
            let cartItem = $(this).closest('.box-cart-produk');
            let inputJumlah = cartItem.find('.banyakProduk');
            let jumlahSekarang = parseInt(inputJumlah.val());
            let idCart = cartItem.data('id-cart');
            let idProduk = cartItem.data('id-produk');

            if (jumlahSekarang > 1) {
                let jumlahBaru = jumlahSekarang - 1;
                inputJumlah.val(jumlahBaru);

                $.ajax({
                    url: 'prosesProduk.php',
                    type: 'POST',
                    data: {
                        action: 'updateJumlah',
                        idCart: idCart,
                        jumlahBaru: jumlahBaru
                    },
                    success: function (response) {
                        console.log("Jumlah diperbarui:", response);
                        updatePrice(cartItem, idProduk, jumlahBaru);
                        updateSummary();
                    },
                    error: function () {
                        console.log('Gagal memperbarui jumlah.');
                    }
                });
            }
        });

        function updatePrice(cartItem, idProduk, jumlahBaru) {
            $.ajax({
                url: 'getProduk.php',
                type: 'GET',
                data: { action: 'updateCartView', idProduk: idProduk },
                success: function (response) {
                    let data = JSON.parse(response);
                    let newPrice = data.harga * jumlahBaru;
                    cartItem.find('.price-cart-produk h6').text('Rp. ' + newPrice.toLocaleString());
                },
                error: function () {
                    console.log('Gagal mengambil harga produk.');
                }
            });
        }

        $(".delete-cart").click(function () {
            let cartItem = $(this).closest('.box-cart-produk');
            let idCart = cartItem.data('id-cart');
            console.log(idCart);
            $.ajax({
                url: 'prosesProduk.php',
                type: 'POST',
                data: {
                    action: 'deleteItem',
                    idCart: idCart
                },
                success: function (response) {
                    console.log("Item dihapus:", response);
                    cartItem.remove(); 
                    updateSummary();
                },
                error: function () {
                    console.log('Gagal menghapus item.');
                }
            });
        });

        function updateSummary() {
            let diskonKode;
            if(isDiskon == 1){
                diskonKode = $("#diskonCode").val().trim();
            }else{
                diskonKode = null;
            }
            $.ajax({
                url: 'prosesProduk.php',
                type: 'POST',
                data: {
                    action: 'updateSummary',
                    diskonKode: diskonKode
                },
                success: function(response) {
                    let summary = JSON.parse(response);
                    $('.value-subtotal').text('Rp. ' + summary.subtotal.toLocaleString());
                    $('.value-diskon').text('- Rp. ' + summary.diskon.toLocaleString());
                    $('.grand-total-summary h1:last-child').text('Rp. ' + summary.grandTotal.toLocaleString());
                    let summaryArea = $(".area-summary-produk");
                    summaryArea.empty();

                    summary.cartItems.forEach(item => {
                        summaryArea.append(`<p>${item.jumlah_barang} x ${item.nama}</p>`);
                    });
                },
                error: function() {
                    console.log('Gagal memperbarui order summary.');
                }
            });
        }
    </script>
</body>
</html>