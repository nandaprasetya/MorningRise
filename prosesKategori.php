<?php
    include 'koneksi.php';

    $action = $_POST['action'];

    if($action == 'create'){
        $nama = $_POST['nama_kategori'];
        $sql = "INSERT INTO kategori 
        (nama_kategori)
        VALUES ('$nama')";
        if ($conn->query($sql) === TRUE) {
            header("Location: kategori.php");
            exit();
        }
    }

    if($action == 'update'){
        $id = $_POST['id'];
        $nama = $_POST['nama'];

        $sql = "UPDATE kategori SET nama_kategori = '$nama' WHERE id_kategori = '$id'";
        $query = $conn->query($sql);

        if ($query) {
            echo json_encode(['success' => true, 'message' => 'Kategori berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Gagal memperbarui kategori.']);
        }
    }
?>