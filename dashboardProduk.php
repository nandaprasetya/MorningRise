<?php
include 'koneksi.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Produk</title>
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
    <div id="KategoriModal" class="fixed w-full inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="w-9/12 bg-white p-6 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Create Kategori Produk</h2>
            <form action="prosesProduk.php" id="produkKategoriForm" method="POST" enctype="multipart/form-data">
                <input type="text" name="action" value="createProdukKategori" hidden>
                <div class="space-y-4">
                    <div>
                        <label for="produkSelect" class="block text-sm font-medium mb-2">Nama Produk</label>
                        <select name="id_produk" class="w-full h-fit px-2 py-2" id="produkSelect">
                            <option value=""></option>
                        </select>
                    </div>
                    <div>
                        <label for="kategoriSelect" class="block text-sm font-medium mb-2">Nama Kategori</label>
                        <select name="id_kategori" class="w-full h-fit px-2 py-2" id="kategoriSelect">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="mt-4 flex">
                        <button type="submit" name="submit" class="w-1/2 mr-3 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Insert Kategori</button>
                        <button type="button" id="closeModalKategori" class="w-1/2 bg-red-600 text-white px-4 py-2 rounded ml-2">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div id="updateModal" class="fixed w-full inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden z-50">
    <div class="w-9/12 bg-white p-6 rounded-lg">
        <h2 class="text-lg font-semibold mb-4">Update Produk</h2>
        <form id="updateForm" action="prosesProduk.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="action" value="update" hidden>
            <input type="hidden" id="update_id" name="id">
            <div class="space-y-4">
                <div>
                    <label for="update_nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" id="update_nama" name="nama" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="update_desk" class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
                    <textarea id="update_desk" name="desk" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="w-full flex justify-between">
                    <div class="w-1/2 mr-10">
                        <label for="update_harga" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                        <input type="number" id="update_harga" name="harga" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-1/2">
                        <label for="update_stok" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                        <input type="number" id="update_stok" name="stok" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label for="update_gambar" class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                    <input type="file" id="update_gambar" name="gambar" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="w-full flex justify-between">
                    <div class="w-1/4 mr-4">
                        <label for="update_kalori" class="block text-sm font-medium text-gray-700">Kalori</label>
                        <input type="number" id="update_kalori" name="kalori" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-1/4 mr-4">
                        <label for="update_karbo" class="block text-sm font-medium text-gray-700">Karbohidrat</label>
                        <input type="number" id="update_karbo" name="karbo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-1/4 mr-4">
                        <label for="update_protein" class="block text-sm font-medium text-gray-700">Protein</label>
                        <input type="number" id="update_protein" name="protein" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-1/4 mr-4">
                        <label for="update_lemak" class="block text-sm font-medium text-gray-700">Lemak</label>
                        <input type="number" id="update_lemak" name="lemak" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="mt-4 flex">
                    <button type="submit" name="submit" class="w-1/2 mr-3 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update Produk</button>
                    <button type="button" id="closeModalUpdate" class="w-1/2 bg-red-600 text-white px-4 py-2 rounded ml-2">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <div id="createModal" class="fixed w-full inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="w-9/12 bg-white p-6 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Create Produk</h2>
            <form action="prosesProduk.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="action" value="create" hidden>
                <div class="space-y-4">
                    <div>
                        <label for="create_nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input type="text" id="create_nama" name="nama" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="create_desk" class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
                        <textarea id="create_desk" name="desk" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div class="w-full flex justify-between">
                        <div class="w-1/2 mr-10">
                            <label for="create_harga" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                            <input type="number" id="create_harga" name="harga" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-1/2">
                            <label for="create_stok" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                            <input type="number" id="create_stok" name="stok" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label for="create_gambar" class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                        <input type="file" id="create_gambar" name="gambar" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-full flex justify-between">
                        <div class="w-1/4 mr-4">
                            <label for="create_kalori" class="block text-sm font-medium text-gray-700">Kalori</label>
                            <input type="number" id="create_kalori" name="kalori" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-1/4 mr-4">
                            <label for="create_karbo" class="block text-sm font-medium text-gray-700">Karbohidrat</label>
                            <input type="number" id="create_karbo" name="karbo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-1/4 mr-4">
                            <label for="create_protein" class="block text-sm font-medium text-gray-700">Protein</label>
                            <input type="number" id="create_protein" name="protein" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="w-1/4 mr-4">
                            <label for="create_lemak" class="block text-sm font-medium text-gray-700">Lemak</label>
                            <input type="number" id="create_lemak" name="lemak" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4 flex">
                        <button type="submit" name="submit" class="w-1/2 mr-3 py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Insert Produk</button>
                        <button type="button" id="closeModalCreate" class="w-1/2 bg-red-600 text-white px-4 py-2 rounded ml-2">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="outer-table">
        <div class="inner-table flex flex-col">
            <div class="flex w-fit">
                <button class="createProduk w-fit my-4 mr-3 bg-blue-500 text-white px-4 py-2 rounded">Tambah Produk</button>
                <button class="createKategoriProduk w-fit my-4 bg-blue-500 text-white px-4 py-2 rounded">Tambah Kategori Produk</button>
            </div>
            <table id="dataTable" class="bg-white border border-gray-200 rounded-md shadow-md w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Kalori</th>
                        <th>Karbohidrat</th>
                        <th>Protein</th>
                        <th>Lemak</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                    
                </thead>
                <tbody id="isi-table" class="divide-y divide-gray-200">
                    <?php include 'TampilProduk.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="plugins/jquery.js"></script>
    <script src="plugins/dataTables.min.js"></script>
    <script src="plugins/dataTables.tailwindcss.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
            "responsive": true,
            
            });
        });
        $("#closeModalUpdate").click(function(){
            $("#updateModal").addClass("hidden");
        });
        
        $("#closeModalCreate").click(function(){
            $("#createModal").addClass("hidden");
        });
        
        $("#closeModalKategori").click(function(){
            $("#KategoriModal").addClass("hidden");
        });

        $('#isi-table').on('click', '.openModalUpdate', function () {
            var id = $(this).data('id');
            $('#update_id').val(id);
            $('#updateModal').removeClass('hidden');
            
            $.ajax({
                url: 'getProduk.php?action=getUpdateProduk',
                method: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (!data.error) {
                        $('#update_id').val(data.id_produk);
                        $('#update_nama').val(data.nama);
                        $('#update_harga').val(data.harga);
                        $('#update_desk').val(data.desk);
                        $('#update_stok').val(data.stok);
                        $('#update_kalori').val(data.kalori);
                        $('#update_karbo').val(data.karbo);
                        $('#update_protein').val(data.protein);
                        $('#update_lemak').val(data.lemak);
                    } else {
                        alert('Data tidak ditemukan: ' + data.error);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Gagal mengambil data.');
                }
            });
        });

        $(".createProduk").click(function(){
            $('#createModal').removeClass('hidden');
        });
        
        $(".createKategoriProduk").click(function(){
            $('#KategoriModal').removeClass('hidden');
            $.ajax({
                url: 'getProduk.php?action=getProdukKategori',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const produkSelect = $('#produkSelect');
                        const kategoriSelect = $('#kategoriSelect');

                        produkSelect.empty();
                        kategoriSelect.empty();

                        response.produk.forEach(item => {
                            produkSelect.append(`<option value="${item.id_produk}">${item.nama_produk}</option>`);
                        });

                        response.kategori.forEach(item => {
                            kategoriSelect.append(`<option value="${item.id_kategori}">${item.nama_kategori}</option>`);
                        });
                    } else {
                        alert('Gagal memuat data produk dan kategori.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                }
            });
        });

        $('#produkKategoriForm').on('submit', function (e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            $.ajax({
                url: 'prosesProduk.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $("#KategoriModal").addClass("hidden");
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error: ' + (response.error || 'Terjadi kesalahan.'));
                    console.log('Terjadi kesalahan saat menyimpan data.');
                }
            });
        });

        function getAllData() {
            $.ajax({
                url: 'getProduk.php?action=allProduk',
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
                                    <td>${item.nama}</td>
                                    <td>${item.harga}</td>
                                    <td>${item.desk}</td>
                                    <td>${item.stok}</td>
                                    <td>${item.kalori}</td>
                                    <td>${item.karbo}</td>
                                    <td>${item.protein}</td>
                                    <td>${item.lemak}</td>
                                    <td><img src="media/uploads/${item.gambar}" alt="Produk" width="50"></td>
                                    <td class='min-w-fit flex flex-wrap'>
                                        <button data-id=${item.id} id='openModalUpdate' class='openModalUpdate bg-blue-500 text-white px-2 py-2 my-1 rounded'><img src='media/edit.png' class='w-4' alt=''></button>
                                        <a href='deleteProduk.php?id=${item.id}' class='bg-red-500 text-white px-2 py-2 my-1 rounded'><img src='media/delete.png' class='w-4' alt=''></a>
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


        $('#updateForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: 'prosesProduk.php',
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
                    alert('Terjadi kesalahan saat memperbarui produk.');
                }
            });
        });

    </script>
</body>
</html>