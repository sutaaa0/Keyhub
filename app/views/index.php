<?php

require_once '../middleware.php';
require_once __DIR__ . "/../models/Barang.php";
require_once __DIR__ . "/../models/Petugas.php";
require_once __DIR__ . "/../models/Pembeli.php";
$conn = require_once __DIR__ . "/../config/database.php";

$username = $_COOKIE['username'];

$user_parts = explode(' ', $username);
$first_name = $user_parts[0]; 

$barang = new Barang($conn);
    
$searchBarang = isset($_GET["search_barang"]) ? $_GET["search_barang"] : "";
    
  if($searchBarang) {
    $result_barang = $barang->search($searchBarang);
  } else {
    $result_barang = $barang->getAll();
  }

    
    $petugas = new Petugas($conn);
    
    $searchPetugas = isset($_GET["search_petugas"]) ? $_GET["search_petugas"] : "";
    
    if($searchPetugas) {
        $result_petugas = $petugas->search($searchPetugas);
    } else {
        $result_petugas = $petugas->getAll();
    }


$pembeli = new Pembeli($conn);
$pembeli = new Pembeli($conn);
$totalPembeli = $pembeli->countAll();
$result_pembeli = $pembeli->getAll();

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- talwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end -->
    <!-- Daisy UI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end -->
    <!-- global css -->
    <link rel="stylesheet" href="../styles//global.css">
    <!-- end -->
    <style>
        @font-face {
        font-family: "Geist-Regular";
        src: url('../fonts/Geist-Regular.ttf'); /* Ubah path_ke_font dengan path ke direktori font */
        }
        body {
            font-family: "Geist-Regular", sans-serif; /* Gunakan font */
        }
    </style>
</head>
<body>
  <div class="flex flex-col justify-center items-center max-h-screen p-5 w-full">
    <div class="flex h-full flex-col justify-center items-center w-full">
      <div class="flex justify-beetween items-center w-full py-3">
        <h1 class="font-bold text-4xl">Dashboard</h1>
        <div class="navbar bg-base-100">
      <div class="flex-1">
        <a class="btn btn-ghost text-xl"></a>
      </div>
      <div class="flex-none">
        <div class="dropdown dropdown-end">
          <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full flex justify-center items-center">
              <p class="text-lg"><?php echo strtoupper(substr($first_name, 0, 1)); ?></p>
            </div>
          </div>
          <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
            <li><p><?php echo $username; ?></p></li> 
            <li><a href="store.php">Toko</a></li> 
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
      </div>
      <div class="flex flex-1 justify-center gap-5 items-center w-full h-[800px]">
        <div class="w-full flex-1 flex h-[600px] rounded-3xl border border-slate-300 shadow-lg p-3 relative">
        <div class="flex w-full h-full">
        <div class="flex flex-col">
            <div class="py-4">
                <h1 class="font-bold text-xl">Daftar Barang</h1>
            </div>
            <div class="flex space-x-3 mb-3">
                <form action="" method="get" class="flex flex-1 gap-x-3">
                <label class="input input-bordered flex items-center gap-2  flex flex-1">
                    <input type="text" class="grow" placeholder="Search" name="search_barang" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                </label>
                <div>
                  <button class="btn" type="submit">Cari..</button>
                </div>
                </form>
            </div>
            <div class="table-container">
                <table class="table">
                    <!-- head -->
                    <theadd>
                    <tr class="sticky top-0 bg-white">
                        <th class="px-2"></th>
                        <th class="text-sm">Nama barang</th>
                        <th class="text-sm">Harga</th>
                        <th class="text-sm">Deskripsi</th>
                        <th class="text-sm">Kategori</th>
                        <th class="text-sm">Foto</th>
                        <th class="text-sm">Stok</th>
                    </tr>
                    </theadd>
                    <tbody>
                    <?php if ( empty($result_barang) ) : ?>
                        <tr>
                            <td colspan="5" class="text-center">No data</td>
                        </tr>
                    <?php endif ?>
                    <?php $counter_barang = 1 ?>
                    <?php foreach ( $result_barang as $data ) : ?>
                        <tr class="h-12">
                            <th><?= $counter_barang ?></th>
                            <td><?= strlen($data["nama_barang"]) > 16 ? substr($data["nama_barang"], 0, 16) . '...' : $data["nama_barang"] ?></td>
                            <td><?= $data["harga"] ?></td>
                            <td><?= strlen($data["deskripsi_barang"]) > 10 ? substr($data["deskripsi_barang"], 0, 10) . "..." : $data["deskripsi_barang"] ?></td>
                            <td><?= $data["kategori_barang"] ?></td>
                            <td><?= strlen($data["gambar_barang"]) > 10 ? substr($data["gambar_barang"], 0, 10) . '...' : $data["gambar_barang"] ?></td>
                            <td><?= $data["stock"] ?></td>
                        </tr>
                        <?php $counter_barang++?>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
        <a href="index.php"></a>
      </div>
      <div class="absolute bottom-5 right-5">
          <a href="daftar_barang.php" class="text-sm text-slate-500 hover:text-slate-700">Read more...</a>
      </div>
        </div>
        <div class="w-full gap-5 flex-1 flex-col flex justify-center items-center h-[600px]">
          <div class="flex flex-col flex-1 justify-center items-center w-full h-[50%] rounded-3xl border border-slate-300 shadow-lg p-1 relative">
          <div class="p-2 w-full flex justify-between items-center">
                <h1 class="font-bold text-xl">Daftar Petugas</h1>
                <a href="daftar_petugas.php" class="text-sm text-slate-500 hover:text-slates-700">Read more...</a>
            </div>
          <div class="flex flex-col p-1 w-full h-[80%]">
            <div class="flex space-x-3 mb-3">
                <form action="" method="get" class="flex flex-1 gap-x-3">
                <label class="input input-bordered flex items-center gap-2 flex flex-1">
                    <input type="text" class="grow" placeholder="Search" name="search_petugas" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                </label>
                <div>
                  <button class="btn" type="submit">Cari..</button>
                </div>
                </form>
            </div>
            <div class="table-container">
                <table class="table">
                    <!-- head -->
                    <thead>
                    <tr class="sticky top-0 bg-white">
                        <th class="px-4"></th>
                        <th class="text-base">Nama Petugas</th>
                        <th class="text-base">Email</th>
                        <th class="text-base">Role</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($result_petugas)) : ?>
                        <tr>
                            <td colspan="5" class="text-center">No data</td>
                        </tr>
                    <?php endif ?>
                    <?php $counter_petugas = 1 ?>
                    <?php foreach ($result_petugas as $data) : ?>
                        <tr class="h-16">
                            <th><?= $counter_petugas ?></th>
                            <td><?= $data["nama_petugas"] ?></td>
                            <td><?= $data["email"] ?></td>
                            <td><?= $data["role"] ?></td>
                        </tr>
                        <?php $counter_petugas++ ?>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
          </div>
          </div>
          <div class="flex flex-col flex-1  w-full h-[50%] rounded-3xl border border-slate-300 shadow-lg relative px-6 py-3">
            <div class="py-1 w-full flex justify-between items-center py-3">
              <div class="flex flex-col py-2">
                <h1 class="text-Regular font-bold">Daftar Pembeli</h1>
                <p class="text-slate-500">kamu memiliki <?php echo $totalPembeli; ?> pembeli</p>
              </div>
              <div>
                <a href="daftar_pembeli.php">Reed more...</a>
              </div>
            </div>
            <div class="w-full flex-1 flex flex-col gap-y-5 table-container">
              <?php foreach ($result_pembeli as $data) : ?>
              <div class="w-full h-auto flex gap-x-2">
                <div>
                <div class="w-10 h-10 flex justify-center items-center rounded-full">
                  <p class="text-lg"><?php echo strtoupper(substr($data["nama_pembeli"], 0,1)); ?></p>
                </div>
                </div>
                <div class="w-full flex justify-between items-center">
                  <div>
                    <h1 class="text-slate-900"><?php echo $data["nama_pembeli"]; ?></h1>
                    <p class="text-slate-500"><?php echo $data["email"]; ?></p>
                  </div>
                  <div>
                    <p>Rp <?php echo number_format($data['total_pembelian'], 0, ',', '.'); ?></p>
                  </div>
                </div>
              </div>
              <?php endforeach ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
