<?php

class Cart {

    private $conn;
    private $id_pembeli;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->id_pembeli = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;
    }

    private function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
    }

    private function validateId($id) {
        return filter_var($id, FILTER_VALIDATE_INT);
    }

    public function addToCart($data) {
        $quantity = $this->sanitize($data["quantity"]);
        $id = $this->sanitize($data["id"]);
        $stmt = $this->conn->prepare("SELECT * FROM barang WHERE id_barang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $stmt = $this->conn->prepare("SELECT * FROM cart WHERE id_barang = ? AND id_pembeli = ?");
            $stmt->bind_param("ii", $id, $this->id_pembeli);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $stmt = $this->conn->prepare("UPDATE cart SET jumlah = jumlah + 1 WHERE id_barang = ? AND id_pembeli = ?");
                $stmt->bind_param("ii", $id, $this->id_pembeli);
            } else {
                $stmt = $this->conn->prepare("INSERT INTO cart (id_barang, id_pembeli, jumlah) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $id, $this->id_pembeli, $quantity);
            }
            $stmt->execute();
            $stmt->close();
            return ["success" => "Item added to cart"];
        } else {
            return ["error" => "Item not found"];
        }
    }

    public function viewCart() {
        $stmt = $this->conn->prepare("SELECT c.id_barang, c.jumlah, b.nama_barang, b.kategori_barang, b.harga, b.gambar_barang 
                                      FROM cart c 
                                      JOIN barang b ON c.id_barang = b.id_barang 
                                      WHERE c.id_pembeli = ?");
        $stmt->bind_param("i", $this->id_pembeli);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $cartItems;
    }

    public function getTotalPriceAndItems() {
        $stmt = $this->conn->prepare("SELECT SUM(b.harga * c.jumlah) AS total_price, SUM(c.jumlah) AS total_items 
                                      FROM cart c 
                                      JOIN barang b ON c.id_barang = b.id_barang 
                                      WHERE c.id_pembeli = ?");
        $stmt->bind_param("i", $this->id_pembeli);
        $stmt->execute();
        $result = $stmt->get_result();
        $totals = $result->fetch_assoc();
        $stmt->close();
        return $totals;
    }

    public function removeFromCart($data) {
        $id = $this->sanitize($data["id"]);
        if (!$this->validateId($id)) {
            return ["error" => "Invalid item ID"];
        }

        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id_barang = ? AND id_pembeli = ?");
        $stmt->bind_param("ii", $id, $this->id_pembeli);
        $stmt->execute();
        $stmt->close();

        return ["success" => "Item removed from cart"];
    }

    public function updateQuantity($id_barang, $amount) {
        $stmt = $this->conn->prepare("SELECT jumlah FROM cart WHERE id_barang = ? AND id_pembeli = ?");
        $stmt->bind_param("ii", $id_barang, $this->id_pembeli);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result) {
            $new_quantity = $result['jumlah'] + $amount;
            if ($new_quantity > 0) {
                $stmt = $this->conn->prepare("UPDATE cart SET jumlah = ? WHERE id_barang = ? AND id_pembeli = ?");
                $stmt->bind_param("iii", $new_quantity, $id_barang, $this->id_pembeli);
            } else {
                $stmt = $this->conn->prepare("DELETE FROM cart WHERE id_barang = ? AND id_pembeli = ?");
                $stmt->bind_param("ii", $id_barang, $this->id_pembeli);
            }
            $stmt->execute();
        }
    }

    public function getTotalItemsInCart() {
        $stmt = $this->conn->prepare("SELECT SUM(jumlah) AS total_items FROM cart WHERE id_pembeli = ?");
        $stmt->bind_param("i", $this->id_pembeli);
        $stmt->execute();
        $result = $stmt->get_result();
        $totalItems = $result->fetch_assoc();
        $stmt->close();
        return $totalItems['total_items'];
    }

    public function getProductById($id_barang) {
        $stmt = $this->conn->prepare("SELECT id_barang, nama_barang, harga, stock FROM Barang WHERE id_barang = ?");
        $stmt->bind_param("i", $id_barang);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    public function clearCart($id_pembeli) {
        $stmt = $this->conn->prepare("DELETE FROM Cart WHERE id_pembeli = ?");
        $stmt->bind_param("i", $id_pembeli);
        $stmt->execute();
        $stmt->close();
    }
    
    
    

}
?>
