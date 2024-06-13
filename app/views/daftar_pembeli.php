<?php 
require_once '../middleware.php'; 
$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Pembeli.php";
require_once __DIR__ . "/../models/Transaksi.php";

$pembeli = new Pembeli($conn);
$transaksi = new TransactionManager($conn);
$total_revenue = $transaksi->getTotalRevenue();
$searchTerm = isset($_GET["search"]) ? $_GET["search"] : "";

if($searchTerm) {
    $result = $pembeli->search($searchTerm);
} else {
    $result = $pembeli->getAll();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="flex items-center justify-center w-full h-screen relative">
        <div class="absolute top-20 left-20">
            <a href="index.php">
                <button class="btn flex gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo-dot"><circle cx="12" cy="17" r="1"/><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                    <p>back</p>
                </button>
            </a>
        </div>
        <div class="flex flex-col">
            <div class="py-4">
                <h1 class="font-bold text-4xl">Daftar Pembeli</h1>
            </div>
            <div class="flex space-x-3 mb-3">
                <form action="" method="get" class="flex flex-1 gap-x-3">
                    <label class="input input-bordered flex items-center gap-2">
                        <input type="text" class="grow" placeholder="Search" name="search" />
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                    </label>
                    <button class="btn" type="submit">Cari..</button>
                </form>
                <div>
                    <p>Total revenue : Rp <?= number_format($total_revenue, 0, ',', '.') ?></p>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="sticky top-0 bg-whiteda">
                            <th class="px-4"></th>
                            <th class="text-base">Nama Pembeli</th>
                            <th class="text-base">Email</th>
                            <th class="text-base">Total Pembelian</th>
                            <th class="text-base">Alat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($result)) : ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        <?php endif ?>
                        <?php $counter = 1 ?>
                        <?php foreach ($result as $data) : ?>
                            <tr class="h-16">
                                <th><?= $counter ?></th>
                                <td><?= $data["nama_pembeli"] ?></td>
                                <td><?= $data["email"] ?></td>
                                <td>Rp <?= number_format($data["total_pembelian"],0,',','.') ?></td>
                                <td>
                                    <div class="flex gap-x-3 py-2 px-3 rounded-3xl bg-black text-white rounded-md text-sm font-bold hover:bg-white hover:text-black focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-black transition duration-300 ease-in">
                                        <a href="user_history.php?id=<?= $data["id_pembeli"] ?>">
                                            <button>Lihat pembelian</button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $counter++ ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
