<?php
require_once '../middleware.php';

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Barang.php";

$barang = new Barang($conn);

if(isset($_POST["submit"])) {
    if ($barang->create($_POST, $_FILES)) {
        header("Location: index.php"); 
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Barang</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Daisy UI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="flex flex-col justify-center items-center h-screen w-full relative">
        <div class="absolute top-20 left-20">
            <a href="daftar_barang.php">
                <button class="btn flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo-dot">
                        <circle cx="12" cy="17" r="1"/>
                        <path d="M3 7v6h6"/>
                        <path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/>
                    </svg>
                    Back
                </button>
            </a>
        </div>
        <div class="flex flex-col justify-center items-center h-screen w-full">
            <h1 class="font-bold text-2xl">Form Tambah Barang</h1>
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col justify-center items-center gap-y-4 my-8 w-[400px]">
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" /></svg>
                    <input name="nama_barang" type="text" class="grow" placeholder="Nama Barang" required />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="harga" type="number" step="0.01" class="grow" placeholder="Harga" required />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="deskripsi_barang" class="grow" placeholder="Deskripsi Barang" required></input>
                </label>
                <select name="kategori_barang" class="select select-bordered w-full max-w-80">
                    <option disabled selected>Kategori Keyboard</option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Gaming">Gaming</option>
                    <option value="Standar">Standar</option>
                </select>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="gambar_barang" type="file" class="grow" placeholder="Gambar Barang" required />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="stock" type="number" class="grow" placeholder="Stock" required />
                </label>
                <button class="btn btn-neutral w-80" type="submit" name="submit">
                    Submit
                </button>
            </form>
        </div>
    </div>
</body>
</html>
