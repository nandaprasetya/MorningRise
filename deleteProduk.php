<?php

include 'koneksi.php';

$id = $_GET['id'];
$sql = "DELETE FROM produk WHERE id_produk = $id";
$query = $conn->query($sql);
if($conn->query($sql) === TRUE){
    header("Location: dashboardProduk.php");
}
?>