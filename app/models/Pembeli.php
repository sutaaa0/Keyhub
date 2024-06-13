<?php

class Pembeli {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $query = "SELECT p.*, SUM(t.total_harga) AS total_pembelian
                  FROM Pembeli p
                  LEFT JOIN Transaksi t ON p.id_pembeli = t.id_pembeli
                  GROUP BY p.id_pembeli";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function search($searchTerm) {
        $searchTerm = "%{$searchTerm}%";
        $query = "SELECT p.*, SUM(t.total_harga) AS total_pembelian
                  FROM Pembeli p
                  LEFT JOIN Transaksi t ON p.id_pembeli = t.id_pembeli
                  WHERE p.nama_pembeli LIKE ?
                  GROUP BY p.id_pembeli";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPurchaseHistory($id_pembeli) {
        $query = "SELECT * FROM Transaksi WHERE id_pembeli = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_pembeli);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAll() {
        $query = "SELECT COUNT(DISTINCT id_pembeli) AS total_pembeli FROM Pembeli";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total_pembeli'];
    }

}

?>
