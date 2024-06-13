<?php

class Petugas {
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
        $query = "SELECT * FROM petugas";
        return $this->conn->query($query);
    }

    public function create($data) {
            $nama_petugas = $this->sanitize($data['nama_petugas']);
            $email = $this->sanitize($data['email']);
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            $role = $this->sanitize($data['role']);

            $stmt = $this->conn->prepare("SELECT * FROM petugas WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->fetch()) {
                $stmt->close();
                $this->displayError("Email sudah terdaftar");
                return false;
            }

            $stmt = $this->conn->prepare("INSERT INTO petugas (nama_petugas, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama_petugas, $email, $password, $role);
            $stmt->execute();

            return true;
    }

    public function getPetugas($id) {
        $id = $this->sanitize($id);
        $stmt = $this->conn->prepare("SELECT * FROM petugas WHERE id_petugas = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($data, $id) {
            $nama_petugas = $this->sanitize($data["nama_petugas"]);
            $email = $this->sanitize($data["email"]);
            $role = $this->sanitize($data["role"]);
            $id = $this->sanitize($id);
        
            $stmt = $this->conn->prepare("UPDATE petugas SET nama_petugas = ?, email = ?, role = ? WHERE id_petugas = ?");
            $stmt->bind_param("sssi", $nama_petugas, $email, $role, $id);
            $stmt->execute();
        
            return true;
    }

    public function delete($id) {
            $id = $this->sanitize($id);
            $stmt = $this->conn->prepare("DELETE FROM petugas WHERE id_petugas = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return true;
    }

    public function search($searchTerm) {
        $searchTerm = "%" . $searchTerm . "%";
        $query = "SELECT * FROM petugas WHERE nama_petugas LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $petugas = [];
        while ($row = $result->fetch_assoc()) {
            $petugas[] = $row;
        }
        $stmt->close();
        return $petugas;
    }
}
?>
