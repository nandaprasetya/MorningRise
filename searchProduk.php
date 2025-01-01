<?php
include 'koneksi.php';
session_start();
$action = $_GET['action'];

if($action == 'search'){
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        $kategori = $_GET['kategori'];
        $sort = $_GET['sort'];
        if($sort == 'terlaris'){
            $sortSql = "ORDER BY p.terjual DESC";
        }else if($sort == 'termurah'){
            $sortSql = "ORDER BY p.harga ASC";
        }else if($sort == 'termahal'){
            $sortSql = "ORDER BY p.harga DESC";
        }else if($sort == 'terbaru'){
            $sortSql = "ORDER BY p.id_produk DESC";
        }

        $minHarga = $_GET['minHarga'];
        $maxHarga = $_GET['maxHarga'];
        $sqlHarga = '';
        if($minHarga != null && $maxHarga != null){
            $sqlHarga = "AND p.harga BETWEEN $minHarga AND $maxHarga";
        }
        
        if($kategori == 'semua'){
            $sql = "SELECT p.* FROM produk p WHERE nama LIKE '%$query%' $sqlHarga $sortSql";
            $result = $conn->query($sql);
        }else{
            $sql = "SELECT p.* FROM produk p 
            JOIN produk_kategori pk ON p.id_produk = pk.id_produk 
            JOIN kategori k ON pk.id_kategori = k.id_kategori
            WHERE k.nama_kategori = '$kategori' AND p.nama LIKE '%$query%' $sqlHarga $sortSql";
            $result = $conn->query($sql);
        }

        $likedProduk = [];
        if (isset($_SESSION['id_user'])) {
            $idUser = $_SESSION['id_user'];
            $sqlLikes = "SELECT id_produk FROM likes WHERE id_user = $idUser";
            $resultLikes = $conn->query($sqlLikes);
            while ($row = $resultLikes->fetch_assoc()) {
                $likedProduk[] = $row['id_produk'];
            }
        }
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="box-produk w-1/4 flex flex-col relative p-2">';
                    echo '<div class="img-box-produk relative w-full">';
                        echo '<div class="outer-like-produk w-full flex justify-end">';
                            if (isset($_SESSION['id_user'])) {
                            echo '<div data-like="like" data-id-produk="' . $row['id_produk'] . '" class="like-produk bg-white m-3 flex justify-center items-center">';
                                echo '<img src="media/' . (in_array($row['id_produk'], $likedProduk) ? 'liked.png' : 'like.png') . '" alt="">';
                            echo '</div>';
                            } else {
                                echo '<a href="login.php" class="like-produk bg-white m-3 flex justify-center items-center">';
                                    echo '<img src="media/like.png" alt="">';
                                echo '</a>';
                            }
                        echo '</div>';
                        echo '<div class="outer-gizi-produk w-full flex justify-center">';
                            echo '<div class="box-gizi-produk w-10/12 px-3 py-4 mb-4 rounded bg-white flex">';
                                echo '<div class="col-gizi-produk flex w-fit mx-2 items-center">';
                                    echo '<img src="media/kalori.png" class="h-5 w-5 mr-2" alt="">';
                                    echo '<h6 class="font-semibold">' . $row['kalori'] . '</h6>';
                                echo '</div>';
                                echo '<div class="col-gizi-produk flex w-fit mx-2 items-center">';
                                    echo '<img src="media/karbo.png" class="h-5 w-5 mr-2" alt="">';
                                    echo '<h6 class="font-semibold">' . $row['karbo'] . '</h6>';
                                echo '</div>';
                                echo '<div class="col-gizi-produk flex w-fit mx-2 items-center">';
                                    echo '<img src="media/protein.png" class="h-5 w-5 mr-2" alt="">';
                                    echo '<h6 class="font-semibold">' . $row['protein'] . '</h6>';
                                echo '</div>';
                                echo '<div class="col-gizi-produk flex w-fit mx-2 items-center">';
                                    echo '<img src="media/lemak.png" class="h-5 w-5 mr-2" alt="">';
                                    echo '<h6 class="font-semibold">' . $row['lemak'] . '</h6>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        echo '<img class="preview-produk w-full" src="media/uploads/' . $row['gambar'] . '" alt="">';
                    echo '</div>';
                    echo '<h5 class="judul-produk mt-2 font-semibold">' . $row['nama'] . '</h5>';
                    echo '<h6 class="harga-produk font-semibold">Rp. ' . number_format($row['harga'], 0, ',', '.') . '</h6>';
                echo '</div>';
            }
        } else {
            echo '<div class="w-full py-7 h-full flex items-center justify-center font-medium">';
            echo '<h1 class="text-2xl">Product Not Found.</h1>';
            echo '</div>';
        }
    }
}

?>