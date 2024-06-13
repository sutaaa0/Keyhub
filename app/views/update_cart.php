<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Cart.php";

$id_barang = $_POST['id'];
$action = $_POST['action'];

$cart = new Cart($conn);

if ($action === 'increase') {
    $cart->updateQuantity($id_barang, 1);  // Increment quantity by 1
} elseif ($action === 'decrease') {
    $cart->updateQuantity($id_barang, -1); // Decrement quantity by 1
}
?>
