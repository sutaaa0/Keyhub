<?php
// Pastikan ada koneksi ke database di sini
$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/PembelianBarang.php";

// Instansiasi objek model pembelian barang
$pembelianBarangModel = new PembelianBarang($conn);

// Panggil metode beliBarang() dari model pembelian barang
// Panggil metode beliBarang() dari model pembelian barang
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $id_barang = $_POST['id_barang'];
    $nama_pembeli = $_POST['nama_pembeli'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];

    // Lakukan pembelian barang
    $pembelianBerhasil = $pembelianBarangModel->beliBarang($id_barang, $nama_pembeli, $alamat, $nomor_telepon, $email);

    // Periksa apakah pembelian berhasil atau tidak
    if ($pembelianBerhasil) {
        // Redirect ke halaman sukses jika pembelian berhasil
        header("Location: pembelian_sukses.php");
        exit;
    } else {
        // Redirect ke halaman gagal jika pembelian gagal
        header("Location: pembelian_gagal.php");
        exit;
    }
}

$id_barang = isset($_GET['id']) ? $_GET['id'] : null;

var_dump($id_barang);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembelian</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Form Pembelian</h2>
        <form action="" method="post">
        <input type="hidden" name="id_barang" value="<?php echo $id_barang; ?>">

            <div class="mb-3">
                <label for="nama_pembeli" class="form-label">Nama Pembeli</label>
                <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" required></textarea>
            </div>
            <div class="mb-3">
                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <button type="submit" class="btn btn-primary">Beli</button>
            <a href="store.php" class="btn btn-secondary">Kembali ke Store</a>
        </form>
    </div>
</body>
</html>
