<?php

include 'koneksi.php';

$id = $_GET['id_kategori'];
$sql = "DELETE FROM kategori WHERE id_kategori = $id";
$query = $conn->query($sql);
if($conn->query($sql) === TRUE){
    header("Location: kategori.php");
}
?>