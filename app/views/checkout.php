<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Cart.php";
require_once __DIR__ . "/../models/Checkout.php";

$id_pembeli = $_COOKIE['user_id'];
$cart = new Cart($conn);

$cartItems = [];
$total = ['total_price' => 0, 'total_items' => 0];

if (isset($_GET['buy_now']) && isset($_GET['id_barang'])) {
    $id_barang = intval($_GET['id_barang']);
    $item = $cart->getProductById($id_barang);
    if ($item) {
        $item['jumlah'] = 1;
        $cartItems[] = $item;
        $total = ['total_price' => $item['harga'], 'total_items' => 1];
    }
} else {
    $cartItems = $cart->viewCart();
    $total = $cart->getTotalPriceAndItems();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($cartItems)) {
        $nama_pembeli = htmlspecialchars($_POST['nama_pembeli'], ENT_QUOTES, "UTF-8");
        $alamat_pengiriman = htmlspecialchars($_POST['alamat_pengiriman'], ENT_QUOTES, "UTF-8");
        $nomor_telepon = htmlspecialchars($_POST['nomor_telepon'], ENT_QUOTES, "UTF-8");
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, "UTF-8");

        $checkout = new Checkout($conn, $cartItems, $total);

        try {
            $id_transaksi = $checkout->processCheckout($id_pembeli, $nama_pembeli, $alamat_pengiriman, $nomor_telepon, $email);

            if (!isset($_GET['buy_now'])) {
                $cart->clearCart($id_pembeli);
            }

            header("Location: confirmation.php?id_transaksi=$id_transaksi");
            exit;
        } catch (Exception $e) {
            echo "Checkout failed: " . $e->getMessage();
        }
    } else {
        echo "Your cart is empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @font-face {
        font-family: "Geist-Regular";
        src: url('../fonts/Geist-Regular.ttf');
        }
        body {
            font-family: "Geist-Regular", sans-serif;
        }
    </style>
</head>
<body>
    <div class="flex flex-col items-center justify-center h-screen w-full gap-y-5">
        <div>
            <h1 class="text-6xl font-bold mb-4">Checkout</h1>
        </div>
        <div class="flex w-full p-12 gap-x-5">
            <?php if (!empty($cartItems)): ?>
                <div class="mb-4 flex flex-col ">
                    <p>Total Items: <?php echo $total['total_items']; ?></p>
                    <p>Total Price: Rp <?php echo number_format($total['total_price'],0,'.','.'); ?></p>
                </div>
                <form class="flex flex-col gap-y-2 flex-1 w-full" action="checkout.php<?php echo isset($_GET['buy_now']) ? '?buy_now=true&id_barang=' . intval($_GET['id_barang']) : ''; ?>" method="POST">
                    <div class="mb-4">
                    <input type="text" id="nama_pembeli" name="nama_pembeli" class="bg-white h-16 border-b-2 font-bold focus:outline-none border-black px-4 py-2 w-full" placeholder="Nama Pembeli" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" id="alamat_pengiriman" name="alamat_pengiriman" class="bg-white h-16 border-b-2 font-bold placeholder-normal focus:outline-none border-black px-4 py-2 w-full" placeholder="Alamat Pengiriman" required>
                    </div>
                    <div class="mb-4">
                        <input type="text" id="nomor_telepon" name="nomor_telepon" class="bg-white h-16 border-b-2 font-bold placeholder-normal focus:outline-none border-black px-4 py-2 w-full" placeholder="Nomor Telepon" required>
                    </div>
                    <div class="mb-4">
                        <input type="email" id="email" name="email" class="bg-white h-16 border-b-2 font-bold placeholder-normal focus:outline-none border-black px-4 py-2 w-full" placeholder="Email" required>
                    </div>
                    <button type="submit" class="py-2 px-4 bg-black text-white rounded-md text-sm font-bold hover:bg-white hover:text-black focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-black transition duration-300 ease-in">Confirm Checkout</button>

                </form>
            <?php else: ?>
                <p class="text-6xl font-bold mb-4 w-full text-center">Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
