<?php
if (!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit;
}

$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Barang.php";
require_once __DIR__ . "/../models/Cart.php";

$barang = new Barang($conn);
$cart = new Cart($conn, $_COOKIE['user_id']);

if(isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product = $barang->getProduct($product_id);

    if (!$product) {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid Product ID";
    exit;
}

if(isset($_POST['submit_to_cart'])) {
    $cart->addToCart($_POST);
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['nama_barang']; ?> - Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/global.css">
</head>
<body>
    <div class="w-full flex h-screen p-12 gap-x-12 relative">
    <div class=" flex flex-col gap-y-3 absolute top-5 left-12 ">
            <a href="store.php" class="mb-4 w-24 h-10 ">
                <button class="w-24 h-10 bg-black text-white rounded text-sm font-bold hover:bg-white hover:text-black hover:border hover:border-black transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-black focus:ring-opacity-50">
                    Back
                </button>
            </a>
        </div>
        <div class="flex flex-1 justify-center items-start">
            <img class="object-contain w-full h-[776px]" src="../uploads/<?php echo $product['gambar_barang']; ?>" alt="<?php echo $product['nama_barang']; ?>">
        </div>
        <div class="flex flex-1 flex-col justify-start items-start gap-y-5">
        <div class="border border-gray-300 rounded-2xl p-2 px-3">
            <h1 class="text-xl"><?php echo $product['kategori_barang']; ?></h1>
        </div>
            <h2 class="block mt-1 text-4xl leading-tight font-semibold text-gray-900"><?php echo $product['nama_barang']; ?></h2>
            <div class="flex flex-col mt-2 text-gray-600 text-base">
                <h3>Description :</h3>
                <p><?php echo $product['deskripsi_barang']; ?></p>
            </div>
                <div class="mt-4 flex gap-x-5 items-center">
                    <div>
                        <span class="text-gray-900 font-bold">Harga:</span>
                        <span class="text-gray-700 ml-2">Rp. <?php echo number_format($product['harga'], 0, ',', '.'); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-900 font-bold">Tersisa:</span>
                        <span class="text-gray-700 ml-2"><?php echo $product['stock']; ?> buah</span>
                    </div>
                </div>
                <div>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $product['id_barang']; ?>">
                    <div class="flex items-center">
                        <input type="number" id="value" class="text-center border-2 border-gray-400 p-2 rounded-md" name="quantity" value="1" readonly>
                        <p class="bg-orange-500 cursor-pointer hover:bg-orange-700 text-black font-bold py-2 px-4 rounded" id="increase-btn">+</p>
                        <p class="bg-orange-500 cursor-pointer hover:bg-orange-700 text-black font-bold py-2 px-4 rounded" id="decrease-btn">-</p>
                        <div class="flex justify-center items-center py-2 px-3 rounded-2xl bg-black text-white rounded text-sm font-bold hover:bg-white hover:text-black hover:border hover:border-black transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-black focus:ring-opacity-50">
                            <button type="submit" name="submit_to_cart" class="">Add to cart</button>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-center items-center px-2 py-3 border border-black rounded-3xl bg-white text-black rounded text-sm font-bold hover:bg-black hover:text-white hover:border hover:border-white transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-white focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-white focus:ring-opacity-50">
                        <a href="store.php" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 9.293a1 1 0 0 1 0-1.414L8.586 2.5a1 1 0 0 1 1.414 1.414L6.414 9H17a1 1 0 1 1 0 2H6.414l3.586 3.086a1 1 0 1 1-1.414 1.414L3.707 10.707a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali ke Store
                        </a>
                    </div>
                </form>
        </div>
        </div>
    </div>
    <script>
        const increaseBtn = document.getElementById('increase-btn');
const decreaseBtn = document.getElementById('decrease-btn');
const valueInput = document.getElementById('value');

let currentValue = parseInt(valueInput.value);

increaseBtn.addEventListener('click', () => {
  currentValue += 1;
  valueInput.value = currentValue.toString();
});

decreaseBtn.addEventListener('click', () => {
  if (currentValue > 1) {
    currentValue -= 1;
    valueInput.value = currentValue.toString();
  }
});
    </script>
</body>
</html>
a