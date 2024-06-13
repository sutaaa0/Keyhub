<?php
require_once __DIR__ . "/../config/database.php";

class Transaksi {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getTransactions($id_pembeli) {
        $query = "SELECT * FROM Transaksi WHERE id_pembeli = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pembeli);
        $stmt->execute();
        $transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $transactions;
    }

    public function getPurchasedItems($id_transaksi) {
        $query = "SELECT dt.*, b.nama_barang, b.harga, b.gambar_barang 
                  FROM DetailTransaksi dt 
                  JOIN Barang b ON dt.id_barang = b.id_barang 
                  WHERE dt.id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();
        $purchasedItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $purchasedItems;
    }

    public function getTotalTransactionCount($id_pembeli) {
        $query = "SELECT COUNT(*) AS total_transaksi 
                  FROM Transaksi 
                  WHERE id_pembeli = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pembeli);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_transaksi = $result->fetch_assoc()['total_transaksi'];
        $stmt->close();
    
        return $total_transaksi;
    }
    
    

    
}

class TransactionManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPurchasedItems($transaction_id) {
        $query = "SELECT dt.*, b.nama_barang, b.harga, b.gambar_barang 
                  FROM DetailTransaksi dt 
                  JOIN Barang b ON dt.id_barang = b.id_barang 
                  WHERE dt.id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $purchasedItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $purchasedItems;
    }

    public function getTotalRevenue() {
        $query = "SELECT SUM(dt.jumlah * b.harga) AS total_pendapatan
                  FROM DetailTransaksi dt
                  JOIN Barang b ON dt.id_barang = b.id_barang";
        $result = $this->conn->query($query);
        $total_pendapatan = $result->fetch_assoc()['total_pendapatan'];
        return $total_pendapatan;
    }

    public function getTotalRevenueForBuyer($id_pembeli) {
        $query = "SELECT SUM(dt.jumlah * b.harga) AS total_pendapatan
                  FROM DetailTransaksi dt
                  JOIN Barang b ON dt.id_barang = b.id_barang
                  JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi
                  WHERE t.id_pembeli = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pembeli);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_pendapatan = $result->fetch_assoc()['total_pendapatan'];
        $stmt->close();
        return $total_pendapatan;
    }
    
    
}
class ConfirmationTransaction {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function transactionDetails ($id_transaksi) {
    $query = "SELECT * FROM Transaksi WHERE id_transaksi = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id_transaksi);
    $stmt->execute();
    $transaction = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $transaction;
    }
    
    public function purchasedItems ($id_transaksi) {
        $query = "SELECT dt.*, b.nama_barang, b.gambar_barang 
                FROM DetailTransaksi dt 
                JOIN Barang b ON dt.id_barang = b.id_barang 
                WHERE dt.id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();
        $purchasedItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $purchasedItems;
    }
}

?>