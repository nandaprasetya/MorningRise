<?php
include 'koneksi.php';

$sql = "SELECT * FROM produk";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama']}</td>
                <td>{$row['harga']}</td>
                <td class='break-words'>{$row['desk']}</td>
                <td>{$row['stok']}</td>
                <td>{$row['kalori']}</td>
                <td>{$row['karbo']}</td>
                <td>{$row['protein']}</td>
                <td>{$row['lemak']}</td>
                <td><img src='media/uploads/{$row['gambar']}' alt='Gambar Produk' width='50'></td>
                <td class='min-w-fit flex flex-wrap'>
                    <button data-id={$row['id_produk']} id='openModalUpdate' class='openModalUpdate bg-blue-500 text-white px-2 py-2 my-1 rounded'><img src='media/edit.png' class='w-4' alt=''></button>
                    <a href='deleteProduk.php?id={$row['id_produk']}' class='bg-red-500 text-white px-2 py-2 my-1 rounded'><img src='media/delete.png' class='w-4' alt=''></a>
                </td>
              </tr>";
        $no++;
    }
} else {
    echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
}
?>