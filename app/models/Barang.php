
<?php

class Barang {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
    }

    public function displayError($message) {
        echo ' <div class="absolute top-[4%] right-[32%] border border-gray-300 p-3 w-[550px] h-[70px] rounded-2xl flex items-center z-10">
        <div class="flex flex-col flex-1 text-black">
            <h1 class="font-bold">Error</h1>
            <p>'.$message.'!</p>
        </div>
    </div>';
    }

    public function getAll() {
        $query = "SELECT * FROM Barang";
        return $this->conn->query($query);
    }

    public function getProduct($id) {
        $id_barang = $this->sanitize($id);
        $stmt = $this->conn->prepare("SELECT * FROM Barang WHERE id_barang = ?");
        $stmt->bind_param("i", $id_barang);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function create($data, $file) {
        $nama_barang = $this->sanitize($data["nama_barang"]);
        $harga = $this->sanitize($data["harga"]);
        $deskripsi_barang = $this->sanitize($data["deskripsi_barang"]);
        $kategori_barang = $this->sanitize($data["kategori_barang"]);
        $stock = $this->sanitize($data["stock"]);
        
        $gambar_barang = $file["gambar_barang"]["name"];
        $gambar_barang_tmp = $file["gambar_barang"]["tmp_name"];
        $upload_dir = __DIR__ . "/../uploads/";
        $upload_file = $upload_dir . basename($gambar_barang);

        $stmt = $this->conn->prepare("SELECT * FROM Barang WHERE nama_barang = ?");
        $stmt->bind_param("s", $nama_barang);
        $stmt->execute();
        if ($stmt->fetch()) {
            $stmt->close();
            $this->displayError("Barang sudah ada");
            return false;
        }
        $stmt->close();

        // Pindahkan file ke direktori tujuan
        if (move_uploaded_file($gambar_barang_tmp, $upload_file)) {
            $stmt = $this->conn->prepare("INSERT INTO Barang (nama_barang, harga, deskripsi_barang, kategori_barang, gambar_barang, stock) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsssi", $nama_barang, $harga, $deskripsi_barang, $kategori_barang, $gambar_barang, $stock);
            $stmt->execute();

            if ($stmt->errno) {
                $this->displayError("Gagal menambahkan barang");
                return false;
            }
            $stmt->close();
            return true;
        } else {
            $this->displayError("Gagal mengunggah file");
            return false;
        }
    }

    public function update($data, $file, $id) {
        $nama_barang = $this->sanitize($data["nama_barang"]);
        $harga = $this->sanitize($data["harga"]);
        $deskripsi_barang = $this->sanitize($data["deskripsi_barang"]);
        $kategori_barang = $this->sanitize($data["kategori_barang"]);
        $stock = $this->sanitize($data["stock"]);
        $id = $this->sanitize($id);

        $gambar_barang = $file["gambar_barang"]["name"];
        $gambar_barang_tmp = $file["gambar_barang"]["tmp_name"];
        $upload_dir = __DIR__ . "/../uploads/";
        $upload_file = $upload_dir . basename($gambar_barang);

        if ($gambar_barang && move_uploaded_file($gambar_barang_tmp, $upload_file)) {
            $stmt = $this->conn->prepare("UPDATE Barang SET nama_barang = ?, harga = ?, deskripsi_barang = ?, kategori_barang = ?, gambar_barang = ?, stock = ? WHERE id_barang = ?");
            $stmt->bind_param("sdsssii", $nama_barang, $harga, $deskripsi_barang, $kategori_barang, $gambar_barang, $stock, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE Barang SET nama_barang = ?, harga = ?, deskripsi_barang = ?, kategori_barang = ?, stock = ? WHERE id_barang = ?");
            $stmt->bind_param("sdssii", $nama_barang, $harga, $deskripsi_barang, $kategori_barang, $stock, $id);
        }

        $stmt->execute();

        if ($stmt->errno) {
            $this->displayError("Gagal memperbarui barang");
            return false;
        }

        $stmt->close();
        return true;
    }

    public function delete($id) {
        $id = $this->sanitize($id);
        $stmt = $this->conn->prepare("DELETE FROM Barang WHERE id_barang = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->errno) {
            $this->displayError("Gagal menghapus barang");
            return false;
        }

        $stmt->close();
        return true;
    }

    public function search($searchTerm) {
        $searchTerm = "%" . $searchTerm . "%";
        $query = "SELECT * FROM Barang WHERE nama_barang LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $barang = [];
        while ($row = $result->fetch_assoc()) {
            $barang[] = $row;
        }
        $stmt->close();
        return $barang;
    }

    public function sortByPrice($order = 'ASC') {
        $order = strtoupper($order);
        $query = "SELECT * FROM Barang ORDER BY harga $order";
        $result = $this->conn->query($query);
        $barang = [];
        while ($row = $result->fetch_assoc()) {
            $barang[] = $row;
        }
        return $barang;
    }

    public function filterByCategory($category) {
        $category = $this->sanitize($category);
        $query = "SELECT * FROM Barang WHERE kategori_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $barang = [];
        while ($row = $result->fetch_assoc()) {
            $barang[] = $row;
        }
        $stmt->close();
        return $barang;
    }
}
?>
