<?php
session_start();
include 'koneksi.php';
$action = $_POST['action'];

if ($action == 'create') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $desk = $_POST['desk'];
    $stok = $_POST['stok'];
    $kalori = $_POST['kalori'];
    $karbo = $_POST['karbo'];
    $protein = $_POST['protein'];
    $lemak = $_POST['lemak'];

    $nama_file = basename($_FILES['gambar']['name']);
    $temp_file = $_FILES['gambar']['tmp_name'];
    $ext_file =  strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $gambar = uniqid() . '.' . $ext_file;
    $target_dir = "media/uploads/";
    $target_file = $target_dir . $gambar;

    if (move_uploaded_file($temp_file, $target_file)) {
        $sql = "INSERT INTO produk 
        (nama, harga, desk, stok, gambar, kalori, karbo, protein, lemak)
        VALUES ('$nama', $harga, '$desk', $stok, '$gambar', '$kalori', '$karbo', '$protein', '$lemak')";
        if ($conn->query($sql) === TRUE) {
            header("Location: dashboardProduk.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}

if ($action == 'update') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $desk = $_POST['desk'];
    $stok = $_POST['stok'];
    $kalori = $_POST['kalori'];
    $karbo = $_POST['karbo'];
    $protein = $_POST['protein'];
    $lemak = $_POST['lemak'];
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $nama_file = basename($_FILES['gambar']['name']);
        $temp_file = $_FILES['gambar']['tmp_name'];
        $ext_file =  strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $gambar = uniqid() . '.' . $ext_file;
        $target_dir = "media/uploads/";
        $target_file = $target_dir . $gambar;

        if (!move_uploaded_file($temp_file, $target_file)) {
            echo json_encode(['error' => 'Gagal mengupload gambar.']);
            exit;
        }
    }else{
        $sqlGambar = $conn->query("SELECT gambar FROM produk WHERE id_produk = '$id'");
        if ($sqlGambar && $sqlGambar->num_rows > 0) {
            $row = $sqlGambar->fetch_assoc();
            $gambar = $row['gambar'];
        } else {
            echo json_encode(['error' => 'Gambar lama tidak ditemukan.']);
            exit;
        }
    }

    $sql = "UPDATE produk SET nama = '$nama', harga = '$harga', desk = '$desk', stok = '$stok', kalori = '$kalori', karbo = '$karbo', protein = '$protein', lemak = '$lemak', gambar = '$gambar' WHERE id_produk = '$id'";
    $query = $conn->query($sql);
    
    if ($query) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Gagal memperbarui produk: ' . $conn->error]);
    }
} 

if($action == 'like'){
    if (!isset($_SESSION['id_user'])) {
        echo json_encode(['status' => 'error', 'message' => 'User belum login']);
        exit;
    }
    
    $idUser = $_SESSION['id_user'];
    $idProduk = $_POST['idProduk'];

    $query = "SELECT * FROM likes WHERE id_user = ? AND id_produk = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param('ii', $idUser, $idProduk);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $query = "DELETE FROM likes WHERE id_user = ? AND id_produk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $idUser, $idProduk);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'liked' => false]);
        exit;
    } else {
        $query = "INSERT INTO likes (id_user, id_produk) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $idUser, $idProduk);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'liked' => true]);
        exit;
    }
    
}

?>