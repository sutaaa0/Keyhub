<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Transaksi.php";

$transaction = new Transaksi($conn);
$transaction_manager = new TransactionManager($conn);

if (isset($_GET['id'])) {
    $id_pembeli = $_GET['id'];
} else {
    $id_pembeli = $_COOKIE['user_id'];
}

$transactions = $transaction->getTransactions($id_pembeli);
$total_revenue = $transaction_manager->getTotalRevenueForBuyer($id_pembeli);
$total_transactions = $transaction->getTotalTransactionCount($id_pembeli);
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
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
    <div class="flex flex-col justify-center items-center w-full p-12 relative">
        <div class=" flex flex-col gap-y-3 absolute top-5 left-12 ">
            <a href="store.php" class="mb-4 w-24 h-10 ">
                <button class="w-24 h-10 bg-black text-white rounded text-sm font-bold hover:bg-white hover:text-black hover:border hover:border-black transition duration-300 ease-in outline-none focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-2 focus:ring-offset-black focus:ring-opacity-50">
                    Back
                </button>
            </a>
        </div>
        <div class="w-full p-1 flex  justify-center items-center relative">
            <div class="absolute top-6 left-0">
                <p class="">Total Transaksi: <?php echo $total_transactions; ?></p>
                <p class="">Rp <?php echo number_format($total_revenue,0,'.','.'); ?></p>
            </div>
            <h1 class="text-6xl font-bold mb-4">Histori Transaksi</h1>
        </div>
        <?php if (!empty($transactions)): ?>
        <?php foreach ($transactions as $transaction_user): ?>
            <div class="mb-4 p-4 border border-gray-300 rounded-2xl mt-12 w-full p-12">
                <p>Transaction ID: <?php echo $transaction_user['id_transaksi']; ?></p>
                <p>Date: <?php echo $transaction_user['waktu_transaksi']; ?></p>
                <p>Total Price: Rp <?php echo number_format($transaction_user['total_harga'],0,'.','.'); ?></p>
                <h2 class="text-xl font-bold mb-4">Barang yang Dibeli</h2>
                <?php $purchasedItems = $transaction->getPurchasedItems($transaction_user['id_transaksi']); ?>
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
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no purchase history.</p>
    <?php endif; ?>
    </div>
</body>
</html>
