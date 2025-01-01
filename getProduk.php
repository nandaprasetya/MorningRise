<?php
include 'koneksi.php';
session_start();

$action = $_GET['action'];


if($action == 'updateCartView'){
    $idProduk = $_GET['idProduk'];
    $sql = "SELECT harga FROM produk WHERE id_produk = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProduk);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode(['harga' => $data['harga']]);
        exit;
    } else {
        echo json_encode(['error' => 'Produk tidak ditemukan']);
        exit;
    }
}

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

if($action == 'getUpdateProduk'){
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM produk WHERE id_produk = '$id'";
        $query = $conn->query($sql);
        $data = $query->fetch_assoc();
    
        echo json_encode($data);
        exit;
    } else {
        echo json_encode(['error' => 'ID tidak ditemukan']);
        exit;
    }
}

if($action == 'getUpdateKategori'){
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM kategori WHERE id_kategori = '$id'";
        $query = $conn->query($sql);
        $data = $query->fetch_assoc();
    
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'ID tidak ditemukan']);
    }
    exit;
}

if($action == 'allKategori'){
    $sql = "SELECT * FROM kategori";
    $result = $conn->query($sql);

    $data = [];
    if ($result && $result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'no' => $no,
                'id' => $row['id_kategori'],
                'nama' => $row['nama_kategori'],
            ];
            $no++;
        }
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Tidak ada data yang ditemukan.']);
    }
    exit;
}

if ($action == 'getProdukKategori') {
    $sqlProduk = "SELECT id_produk, nama FROM produk";
    $sqlKategori = "SELECT id_kategori, nama_kategori FROM kategori";

    $resultProduk = $conn->query($sqlProduk);
    $resultKategori = $conn->query($sqlKategori);

    $produk = [];
    $kategori = [];

    if ($resultProduk && $resultProduk->num_rows > 0) {
        while ($row = $resultProduk->fetch_assoc()) {
            $produk[] = [
                'id_produk' => $row['id_produk'],
                'nama_produk' => $row['nama'],
            ];
        }
    }

    if ($resultKategori && $resultKategori->num_rows > 0) {
        while ($row = $resultKategori->fetch_assoc()) {
            $kategori[] = [
                'id_kategori' => $row['id_kategori'],
                'nama_kategori' => $row['nama_kategori'],
            ];
        }
    }

    echo json_encode(['success' => true, 'produk' => $produk, 'kategori' => $kategori]);
    exit;
}

if($action == 'getLikeProduk'){
    $user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
    $sqlProduk = "
        SELECT p.*, l.id_user AS liked_by_user
        FROM produk p
        LEFT JOIN likes l ON p.id_produk = l.id_produk AND l.id_user = ?
        WHERE l.id_user IS NOT NULL
    ";

    $stmt = $conn->prepare($sqlProduk);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultProduk = $stmt->get_result();

    $produkData = [];
    if ($resultProduk->num_rows > 0) {
        while ($row = $resultProduk->fetch_assoc()) {
            $produkData[] = $row;
        }
    }

    echo json_encode($produkData);
    exit;
}
?>