<?php
session_start();
$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Barang.php";
require_once __DIR__ . "/../models/Cart.php";
require_once __DIR__ . "/../models/Transaksi.php";

if (!isset($_COOKIE['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_COOKIE['username'];
$user_parts = explode(' ', $username);
$first_name = $user_parts[0]; 

$barang = new Barang($conn);
$Transaksi = new Transaksi($conn);
$cart = new Cart($conn, $_COOKIE['user_id']);

$searchTerm = isset($_GET["search"]) ? $_GET["search"] : "";
$sortOrder = isset($_GET["sort"]) ? $_GET["sort"] : "";
$category = isset($_GET["category"]) ? $_GET["category"] : "";

if ($searchTerm) {
    $result = $barang->search($searchTerm); } elseif ($sortOrder) { $result = $barang->sortByPrice($sortOrder); } elseif ($category) { $result = $barang->filterByCategory($category); } else { $result = $barang->getAll(); 
}

if(isset($_POST['submit_to_cart'])) {
    $cart->addToCart($_POST);
  }   
  
  $total_product_in_cart = $cart->getTotalItemsInCart();
  $total_transaksi = $Transaksi->getTotalTransactionCount($_COOKIE['user_id']);
  

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../styles/global.css" />
    <style>
        @font-face {
        font-family: "Geist-Regular";
        src: url('../fonts/Geist-Regular.ttf'); 
        }
        body {
            overflow-x: hidden;
            font-family: "Geist-Regular", sans-serif; 
        }
    </style>
  </head>
  <body>
    <div class="flex flex-col h-auto">
      <div class="navbar bg-base-100">
        <div class="flex-1">
          <a class="btn btn-ghost text-xl">KeyHub</a>
        </div>
        <div class="flex-none">
          <a href="view_cart.php" class="dropdown dropdown-end">
            <div role="button" class="btn btn-ghost btn-circle">
              <div class="indicator">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
                <span class="badge badge-sm indicator-item"><?php echo $total_product_in_cart ? $total_product_in_cart : 0 ?></span>
              </div>
            </div>
            </a>
            <a  href="user_history.php" role="button" class="btn btn-ghost btn-circle">
              <div class="indicator">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-dollar-sign"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                <span class="badge badge-sm indicator-item"><?php echo $total_transaksi ? $total_transaksi : 0 ?></span>
              </div>
            </a>
          </div>
          <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 h-10 flex justify-center items-center rounded-full">
              <p class="text-lg"><?php echo strtoupper(substr($first_name, 0,1)); ?></p>
              </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
              <li><p><?php echo $_COOKIE['username']?></p></li>
              <?php if(isset($_COOKIE["role"])) : ?>
                <li><a href="index.php">Dashboard</a></li>
                <?php else:?>
                  <p></p>
              <?php endif; ?>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="flex flex-col justify-center items-center p-5">
        <div class="flex justify-between gap-x-3 w-full mb-5">
          <div class="flex gap-x-3">
            <form action="" method="get" class="flex items-center gap-x-3">
              <label class="input input-bordered flex items-center gap-2">
                <input type="text" class="grow" placeholder="Cari keyboard" name="search" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                  <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                </svg>
              </label>
              <button class="btn" type="submit">Cari..</button>
            </form>
          </div>
          <div class="flex gap-x-3">
            <div class="dropdown dropdown-hover">
              <div tabindex="0" role="button" class="btn m-1">Urut berdasarkan</div>
              <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="store.php?sort=ASC">Harga: Rendah hingga Tinggi</a></li>
                <li><a href="store.php?sort=DESC">Harga: Tinggi ke Rendah</a></li>
              </ul>
            </div>
            <div class="dropdown dropdown-hover">
              <div tabindex="0" role="button" class="btn m-1">Kategori</div>
              <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="store.php?category=Mechanical">Mechanical</a></li>
                <li><a href="store.php?category=Gaming">Gaming</a></li>
                <li><a href="store.php?category=Standar">Standar</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-4 gap-5 mt-3">
        <?php if (empty($result)): ?>
          <p class="text-2xl text-center col-span-4">Barang tidak ditemukan</p>
        <?php else: ?>
          <?php foreach ($result as $barang) : ?>
            <div class="flex flex-col justify-between items-center w-[350px] h-[350px]">
              <a href="detail_produk.php?id=<?php echo $barang['id_barang']; ?>" class="flex flex-1 justify-center items-center border border-slate-300 rounded-2xl w-full bg-gray-100 relative">
              <div class="absolute top-2 right-2 flex justify-center items-center rounded-2xl border border-slate-300 bg-white py-1 px-3">
                <p class="text-sm text-black font-semibold"><?php echo $barang['kategori_barang'] ?></p>
                </svg>
              </div>
                <img class="max-w-[350px] max-h-[200px] contain" src="../uploads/<?php echo $barang['gambar_barang'] ?>" alt="" />
                </a>
              <div class="w-full flex gap-y-3 flex-col justify-center items-start rounded-2xl mt-4">
                <div class="flex justify-between items-center w-full">
                <h1 class="text-sm font-bold text-black"><?php echo $barang['nama_barang'] ?></h1>
                <p class="text-sm font-bold text-black">
                  Rp
                  <?php echo number_format($barang['harga'], 0, ',', '.'); ?>
                </p>
                </div>
                <div class="flex justify-between gap-x-2 items-center w-full">
                    <form action="" method="post" class="flex flex-1 justify-center items-center py-2 px-3 rounded-3xl bg-black text-white rounded-md text-sm font-bold hover:bg-white hover:text-black focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-black transition duration-300 ease-in">
                        <input class="hidden" type="text" name="id" value="<?php echo $barang['id_barang']; ?>" hidden">
                        <input class="hidden" type="text" name="quantity" value="1" hidden">
                        <button name="submit_to_cart" value="submit">Masukkan keranjang</button>
                    </form>
                    <a href="checkout.php?buy_now=true&id_barang=<?php echo $barang['id_barang']; ?>" class="flex flex-1 justify-center items-center border border-black py-2 px-3 bg-white text-black rounded-md text-sm font-bold hover:bg-black hover:text-white focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-black transition duration-300 ease-in">Beli sekarang</a>
                </div>
              </div>
            </div>
          <?php endforeach?>
          <?php endif ?>
        </div>
      </div>
    </div>
  </body>
</html>

