<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Transaksi.php";

$id_transaksi = $_GET['id_transaksi'];

$transaction = new ConfirmationTransaction($conn);

$transaction_details = $transaction->transactionDetails($id_transaksi);
$purchasedItems = $transaction->purchasedItems($id_transaksi);

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
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
    <div class="flex flex-col justify-center items-center w-full p-12 relative">
        <div class=" flex flex-col gap-y-3 absolute top-5 left-12 ">
            <a href="store.php" class="mb-4 w-24 h-10 ">
                <button class="w-24 h-10 bg-black text-white rounded text-sm font-bold hover:bg-white hover:text-black hover:border hover:border-black transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-black focus:ring-opacity-50">
                    Back
                </button>
            </a>
        </div>
        <div class="w-full p-1 flex  justify-center items-center relative">
            <h1 class="text-6xl font-bold mb-4">Transaksi Succes</h1>
        </div>
            <div class="mb-4 p-4 border border-gray-300 rounded-2xl mt-12 w-full p-12">
                <p>Transaction ID: <?php echo $transaction_details['id_transaksi']; ?></p>
                <p>Date: <?php echo $transaction_details['waktu_transaksi']; ?></p>
                <p>Total Price: Rp <?php echo number_format($transaction_details['total_harga'],0,'.','.'); ?></p>
                <h2 class="text-xl font-bold mb-4 mt-4">Barang yang Dibeli</h2>
                <?php if (!empty($purchasedItems)): ?>
                    <table class="table">
                        <thead>
                        <tr class="text-base">
                            <th>Nama barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($purchasedItems as $item):?>
                            <tr>
                                <th><?php echo $item['nama_barang']; ?></th>
                                <td><?php echo $item['jumlah']; ?></td>
                                <td>Rp <?php echo number_format($item['harga'],0,'.','.'); ?></td>
                                <td><img src="../uploads/<?php echo $item['gambar_barang']; ?>" alt="<?php echo $item['nama_barang']; ?>" width="50"></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No items found for this transaction.</p>
                <?php endif; ?>
            </div>
    </div>
</body>
</html>
