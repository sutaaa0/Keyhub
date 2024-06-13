<?php 
    // session_start();

    require_once '../middleware.php';

    $conn = require_once __DIR__ ."/../config/database.php";
    require_once __DIR__ ."/../models/Petugas.php";

    $petugas = new Petugas($conn);

    if(isset($_GET["id"])) {
        $id = $_GET["id"];
        if($petugas->delete($id)) {
            header("Location: daftar_petugas.php");
            exit;
        } else {
            echo "Gagal menghapus petugas.";
        }
    } else {
        echo "ID petugas tidak ditemukan.";
    }
?>
