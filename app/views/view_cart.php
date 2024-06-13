<?php

if (!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Cart.php";

$id_pembeli = $_COOKIE['user_id'];

$cart = new Cart($conn, $id_pembeli);
$cartItems = $cart->viewCart();
$total = $cart->getTotalPriceAndItems();

if(isset($_POST["remove_from_cart"])) {
    $cart->removeFromCart($_POST);
    header("Location: {$_SERVER['PHP_SELF']}");
exit;

}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../styles/global.css" />
    <script>
        function updateQuantity(itemId, action) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send('id=' + itemId + '&action=' + action);
        }
    </script>
    <style>
        @font-face {
    font-family: "Geist-Black";
    src: url('../fonts/Geist-Regular.ttf'); 
}
body {
    font-family: "Geist-Black", sans-serif;
}
    </style>
</head>
<body>
    <div class="w-full h-screen flex flex-col p-12">
        <div class="w-full flex flex-col gap-y-3">
            <a href="store.php" class="mb-4 w-24 h-10 ">
                <button class="w-24 h-10 bg-black text-white rounded text-sm font-bold hover:bg-white hover:text-black hover:border hover:border-black transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-black focus:ring-opacity-50">
                    Back
                </button>
            </a>
            <h1 class="text-2xl font-bold mb-4">My Cart</h1>
        </div>
        <div class="w-full gap-x-5 flex ">
            <div class="flex flex-col flex-1 gap-y-5">
            <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="w-full h-auto flex justify-between items-center rounded-xl px-4 border border-gray-300 py-2 relative">
                            <div class="absolute top-3 right-3">
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $item['id_barang']; ?>">
                                    <button type="submit" name="remove_from_cart">
                                        <svg svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                            <div class="flex gap-x-3">
                                <div>
                                    <img src="../uploads/<?php echo $item['gambar_barang']; ?>" alt="foto_product" class="w-20 h-20 object-cover rounded ">
                                </div>
                                <div>
                                <div class="badge badge-lg"><?php echo $item['kategori_barang']; ?></div>
                                    <h1 class="font-bold text-base"><?php echo $item['nama_barang']; ?></h1>
                                </div>
                            </div>
                            <div class="flex flex-col gap-y-2">
                                <p>Quantity: <?php echo $item['jumlah']; ?></p>
                                <p>Price: <?php echo $item['harga']; ?></p>
                                <div class="flex justify-center items-center gap-x-5 bg-gray-100 border border-gray-200 rounded-xl">
                                    <button onclick="updateQuantity(<?php echo $item['id_barang']; ?>, 'decrease')" class="font-bold">-</button>
                                        <?php echo $item['jumlah']; ?>
                                    <button onclick="updateQuantity(<?php echo $item['id_barang']; ?>, 'increase')" class="font-bold">+</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
            </div>
            <div class="w-[250px]">
                <div class="flex flex-col border border-gray-300 py-2 px-4 rounded-xl">
                    <h1 class="font-bold text-lg w-full flex flex-start ">Summary</h1>
                    <div class="mb-4 text-sm flex flex-col justify-center items-start">
                        <p>Total Items: <?php echo $total['total_items']; ?></p>
                        <p>Total Price: <?php echo $total['total_price']; ?></p>
                    </div>
                    <div class="bg-black text-white rounded text-sm font-bold hover:bg-white hover:text-black hover:border hover:border-black transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-black focus:ring-opacity-50 w-full bg-black flex justify-center items-center px-4 py-2 rounded-xl">
                        <a href="checkout.php" class="">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
