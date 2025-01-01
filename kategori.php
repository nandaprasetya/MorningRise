<?php
include 'koneksi.php';
session_start();
$sql = "SELECT * FROM kategori";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kategori</title>
    <link rel="stylesheet" href="plugins/dataTables.css">
    <link rel="stylesheet" href="plugins/tailwind.css">
    <script src="plugins/tailwindcss.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        td {
            white-space: normal; 
            word-wrap: break-word; 
            overflow-wrap: break-word;
        }  

        .outer-table{
            width: 100%;
            display: flex;
            justify-content: center;
            padding-top: 100px;
        }
        
        .inner-table{
            width: 90%;
            display: flex;
            justify-content: center;
        }

        .dt-paging nav{
            position: relative;
            z-index: 1;
        }
    </style>
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
    <div id="createModal" class="fixed w-full inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="w-9/12 bg-white p-6 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Create Kategori</h2>
            <form action="prosesKategori.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="action" value="create" hidden>
                <div class="space-y-4">
                    <div>
                        <label for="create_nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" id="create_nama" name="nama_kategori" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mt-4 flex">
                        <button type="submit" name="submit" class="w-1/2 mr-3 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Insert Kategori</button>
                        <button type="button" id="closeModalCreate" class="w-1/2 bg-red-600 text-white px-4 py-2 rounded ml-2">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="updateModal" class="fixed w-full inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="w-9/12 bg-white p-6 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Update Kategori</h2>
            <form id="updateForm" action="prosesKategori.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="action" value="update" hidden>
                <input type="hidden" id="update_id" name="id">
                <div class="space-y-4">
                    <div>
                        <label for="update_nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" id="update_nama" name="nama" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="mt-4 flex">
                        <button type="submit" name="submit" class="w-1/2 mr-3 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update Produk</button>
                        <button type="button" id="closeModalUpdate" class="w-1/2 bg-red-600 text-white px-4 py-2 rounded ml-2">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="outer-table">
        <div class="inner-table flex flex-col">
            <button class="createKategori w-fit my-4 bg-blue-500 text-white px-4 py-2 rounded">Tambah Kategori</button>
            <table id="dataTable" class="bg-white border border-gray-200 rounded-md shadow-md w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th>No</th>
                        <th>Id Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="isi-table" class="divide-y divide-gray-200">
                    <?php 
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['id_kategori']}</td>
                                    <td>{$row['nama_kategori']}</td>
                                    <td class='min-w-fit flex flex-wrap'>
                                        <button data-id={$row['id_kategori']} id='openModalUpdate' class='openModalUpdate bg-blue-500 text-white px-2 py-2 my-1 mx-2 rounded'><img src='media/edit.png' class='w-4' alt=''></button>
                                        <a href='deleteKategori.php?id_kategori={$row['id_kategori']}' class='bg-red-500 text-white px-2 py-2 my-1 rounded' ><img src='media/delete.png' class='w-4' alt=''></a>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="plugins/jquery.js"></script>
    <script src="plugins/dataTables.min.js"></script>
    <script src="plugins/dataTables.tailwindcss.js"></script>
    <script>
        $('#dataTable').DataTable({
            "responsive": true,    
        });

        $("#closeModalCreate").click(function(){
            $("#createModal").addClass("hidden");
        });

        $("#closeModalUpdate").click(function(){
            $("#updateModal").addClass("hidden");
        });

        $(".createKategori").click(function(){
            $('#createModal').removeClass('hidden');
        });

        $('#isi-table').on('click', '.openModalUpdate', function () {
            var id = $(this).data('id');
            console.log(id);
            $('#update_id').val(id);
            $('#updateModal').removeClass('hidden');
            
            $.ajax({
                url: 'getProduk.php?action=getUpdateKategori',
                method: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (!data.error) {
                        $('#update_id').val(data.id_kategori);
                        $('#update_nama').val(data.nama_kategori);
                    } else {
                        alert('Data tidak ditemukan: ' + data.error);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Gagal mengambil data.');
                }
            });
        });

        $('#updateForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: 'prosesKategori.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    $('#isi-table').empty();
                    getAllData();
                    $("#updateModal").addClass("hidden");
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui Kategori.');
                }
            });
        });

        function getAllData() {
            $.ajax({
                url: 'getProduk.php?action=allKategori',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        console.log(response.data);
                        $('#isi-table').empty();
                        response.data.forEach(item => {
                            $('#isi-table').append(`
                                <tr>
                                    <td>${item.no}</td>
                                    <td>${item.id}</td>
                                    <td>${item.nama}</td>
                                    <td class='min-w-fit flex flex-wrap'>
                                        <button data-id=${item.id} id='openModalUpdate' class='openModalUpdate bg-blue-500 text-white px-2 py-2 my-1 rounded'><img src='media/edit.png' class='w-4' alt=''></button>
                                        <a href='deleteKategori.php?id=${item.id}' class='bg-red-500 text-white px-2 py-2 my-1 rounded'><img src='media/delete.png' class='w-4' alt=''></a>
                                    </td>
                                </tr>
                            `);
                            $('.openModalUpdate').prop('disabled', false);
                            $('#updateForm')[0].reset();
                        });
                    } else {
                        alert('Gagal memuat data: ' + response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        }
    </script>
</body>
</html>