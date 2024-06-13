<?php
class Checkout {
    private $conn;
    private $cartItems;
    private $total;

    public function __construct($conn, $cartItems, $total) {
        $this->conn = $conn;
        $this->cartItems = $cartItems;
        $this->total = $total;
    }

    public function processCheckout($id_pembeli, $nama_pembeli, $alamat_pengiriman, $nomor_telepon, $email) {
        $this->conn->begin_transaction();
        try {
            foreach ($this->cartItems as $item) {
                $query = "SELECT stock FROM Barang WHERE id_barang = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("i", $item['id_barang']);
                $stmt->execute();
                $result = $stmt->get_result();
                $barang = $result->fetch_assoc();
                $stmt->close();

                if ($barang['stock'] < $item['jumlah']) {
                    throw new Exception("Stok barang tidak mencukupi: " . $item['nama_barang']);
                }
            }

            $query = "INSERT INTO Transaksi (id_pembeli, waktu_transaksi, total_harga, nama_pembeli, alamat_pengiriman, nomor_telepon_pembeli, email_pembeli) VALUES (?, NOW(), ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("idssss", $id_pembeli, $this->total['total_price'], $nama_pembeli, $alamat_pengiriman, $nomor_telepon, $email);
            $stmt->execute();
            $id_transaksi = $stmt->insert_id;

            $query = "INSERT INTO DetailTransaksi (id_transaksi, id_barang, jumlah, harga) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            foreach ($this->cartItems as $item) {
                $stmt->bind_param("iiid", $id_transaksi, $item['id_barang'], $item['jumlah'], $item['harga']);
                $stmt->execute();

                $query = "UPDATE Barang SET stock = stock - ? WHERE id_barang = ?";
                $update_stmt = $this->conn->prepare($query);
                $update_stmt->bind_param("ii", $item['jumlah'], $item['id_barang']);
                $update_stmt->execute();
                $update_stmt->close();
            }

            $this->conn->commit();
            return $id_transaksi;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
}
?>
