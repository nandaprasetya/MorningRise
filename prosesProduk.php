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

if($action == 'addCart'){
    $idUser = $_SESSION['id_user'];
    $idProduk = $_POST['idProduk'];
    $jumlahBrg = $_POST['jmlBarang'];

    $sql = "SELECT * FROM cart WHERE id_user = ? AND id_produk = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idUser, $idProduk);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentJumlahBarang = $row['jumlah_barang'];
        $updateJmlBarang = $jumlahBrg + $currentJumlahBarang;
        $sql = "UPDATE cart SET jumlah_barang = ? WHERE id_user = ? AND id_produk = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $updateJmlBarang , $idUser, $idProduk);
    } else {
        $sql = "INSERT INTO cart (id_user, id_produk, jumlah_barang) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $idUser, $idProduk, $jumlahBrg);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan produk ke keranjang.']);
    }
    exit;
}

if($action == 'updateJumlah'){
    $idCart = $_POST['idCart'];
    $jumlahBaru = $_POST['jumlahBaru'];

    $sql = "UPDATE cart SET jumlah_barang = ? WHERE id_cart = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $jumlahBaru, $idCart);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Quantity updated successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update quantity']);
        exit;
    }
}

if ($action === 'deleteItem') {
    $idCart = $_POST['idCart'];

    $sql = "DELETE FROM cart WHERE id_cart = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCart);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete item']);
        exit;
    }
}


if($action == 'updateSummary'){
    $idUser = $_SESSION['id_user'];

    $sqlCart = "SELECT c.jumlah_barang, p.harga, p.nama 
                FROM cart c 
                JOIN produk p ON c.id_produk = p.id_produk 
                WHERE c.id_user = ?";
    $stmtCart = $conn->prepare($sqlCart);
    $stmtCart->bind_param("i", $idUser);
    $stmtCart->execute();
    $resultCart = $stmtCart->get_result();

    $subtotal = 0;
    $cartItems = [];
    while ($row = $resultCart->fetch_assoc()) {
        $subtotal += $row['jumlah_barang'] * $row['harga'];
        $cartItems[] = [
            'nama' => $row['nama'],
            'jumlah_barang' => $row['jumlah_barang']
        ];
    }

    $diskonKode = $_POST['diskonKode'];
    $diskonAmount = 0;

    if (!empty($diskonKode)) {
        $sqldiskon = "SELECT jumlah_diskon FROM diskon WHERE kode_diskon = ? AND isAktif = 1";
        $stmtdiskon = $conn->prepare($sqldiskon);
        $stmtdiskon->bind_param("s", $diskonKode);
        $stmtdiskon->execute();
        $resultdiskon = $stmtdiskon->get_result();

        if ($resultdiskon->num_rows > 0) {
            $diskonData = $resultdiskon->fetch_assoc();
            $diskonAmount = $diskonData['jumlah_diskon'];
        }
    }

    $grandTotal = $subtotal - $diskonAmount;

    echo json_encode([
        'subtotal' => $subtotal,
        'diskon' => $diskonAmount,
        'grandTotal' => $grandTotal,
        'cartItems' => $cartItems
    ]);
}
?>