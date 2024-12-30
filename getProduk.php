<?php
include 'koneksi.php';

$action = $_GET['action'];

if($action == 'allProduk'){
    $sql = "SELECT * FROM produk";
    $result = $conn->query($sql);

    $data = [];
    if ($result && $result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'no' => $no,
                'id' => $row['id_produk'],
                'nama' => $row['nama'],
                'harga' => $row['harga'],
                'desk' => $row['desk'],
                'stok' => $row['stok'],
                'kalori' => $row['kalori'],
                'karbo' => $row['karbo'],
                'protein' => $row['protein'],
                'lemak' => $row['lemak'],
                'gambar' => $row['gambar']
            ];
            $no++;
        }
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Tidak ada data yang ditemukan.']);
    }
    exit;
}

if($action = 'getUpdateProduk'){
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM produk WHERE id_produk = '$id'";
        $query = $conn->query($sql);
        $data = $query->fetch_assoc();
    
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'ID tidak ditemukan']);
    }
    exit;
}

?>