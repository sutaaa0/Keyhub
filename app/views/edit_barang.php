<?php 

require_once "../middleware.php";


$conn = require_once __DIR__ ."/../config/database.php";
require_once __DIR__ ."/../models/Barang.php";

$barang = new Barang($conn);

$id = $_GET["id"];
$data = $barang->getProduct($id);

if(isset($_POST["submit"])) {
    $nama_barang = $_POST["nama_barang"];
    $harga = $_POST["harga"];
    $deskripsi_barang = $_POST["deskripsi_barang"];
    $kategori_barang = $_POST["kategori_barang"];
    $stock = $_POST["stock"];

    if($nama_barang && $harga && $deskripsi_barang && $kategori_barang && $stock) {
        if($barang->update($_POST, $_FILES, $id)) {
            header("Location: index.php");
            exit;
        } else {
            echo "Gagal memperbarui barang.";
        }
    } else {
        echo "Semua field harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Barang</title>
    <!-- talwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end -->
    <!-- Daisy UI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end -->
</head>
<body>
    <div class="flex flex-col justify-center items-center h-screen w-full relative">
        <div class="absolute top-20 left-20">
            <a href="daftar_barang.php">
                <button class="btn flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo-dot"><circle cx="12" cy="17" r="1"/><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                    Back
                </button>
            </a>
        </div>
        <div class="flex flex-col justify-center items-center h-screen w-full">
            <h1 class="font-bold text-2xl">Ubah Barang</h1>
            <form action="" method="post" enctype="multipart/form-data" class="flex flex-col justify-center items-center gap-y-4 my-8 w-[400px]">
                <input value="<?= $data["id_barang"] ?>" name="id" type="hidden" class="grow" placeholder="id" required />
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" /></svg>
                    <input name="nama_barang" type="text" class="grow" placeholder="Nama Barang" required value="<?= $data["nama_barang"] ?>" />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="harga" type="number" step="0.01" class="grow" placeholder="Harga" required value="<?= $data["harga"] ?>"/>
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <textarea name="deskripsi_barang" class="grow" placeholder="Deskripsi Barang" required><?= $data["deskripsi_barang"] ?></textarea>
                </label>
                <select name="kategori_barang" class="select select-bordered w-full max-w-80">
                    <option disabled selected>Kategori Barang</option>
                    <option value="Mechanical" <?php if($data["kategori_barang"] == "Mechanical" ) echo "selected" ?>>Mechanical</option>
                    <option value="Gaming" <?php if($data["kategori_barang"] == "Gaming") echo "selected" ?>>Gaming</option>
                    <option value="Standar" <?php if($data["kategori_barang"] == "Standar") echo "selected" ?>>Standar</option>
                </select>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="gambar_barang" type="file" class="grow" placeholder="Gambar Barang" />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="stock" type="number" class="grow" placeholder="Stock" required value="<?= $data["stock"] ?>"/>
                </label>
                <button class="btn btn-neutral w-80" type="submit" name="submit">
                    Update
                </button>
            </form>
        </div>
    </div>
</body>
</html>
