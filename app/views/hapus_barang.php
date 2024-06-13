<?php 

require_once '../middleware.php';


$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Barang.php";

$barang = new Barang($conn);

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    if ($barang->delete($id)) {
        header("Location: daftar_barang.php");
        exit;
    } else {
        echo "Gagal menghapus barang.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
