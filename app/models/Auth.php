<?php

class Auth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        // Mulai sesi pada konstruktor
    }

    public function displayError($message) {
        echo ' <div class="absolute top-[7%] right-[7%] border border-gray-300 p-3 w-[550px] h-[70px] rounded-2xl flex items-center z-10">
        <div class="flex flex-col flex-1 text-black">
            <h1 class="font-bold">Error</h1>
            <p>'.$message.'!</p>
        </div>
    </div>';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $stmt = $this->conn->prepare("SELECT id_pembeli, nama_pembeli, password FROM pembeli WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            $id = null;
            $name = null;
            $hashed_password = null;
            
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();
    
            if ($stmt->num_rows > 0 && $hashed_password !== null && password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $name;

                setcookie('user_id', $id, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
                setcookie('username', $name, time() + (86400 * 30), "/");
                
                header("Location: store.php");
                exit();
            } else {
                $stmt = $this->conn->prepare("SELECT id_petugas, nama_petugas, password, role FROM petugas WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
                
                $id = null;
                $name = null;
                $hashed_password = null;
                $role = null;
                
                $stmt->bind_result($id, $name, $hashed_password, $role);
                $stmt->fetch();
    
                if ($stmt->num_rows > 0 && $hashed_password !== null && password_verify($password, $hashed_password)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $name;
                    $_SESSION['role'] = $role;

                    setcookie('user_id', $id, time() + (86400 * 30), "/"); // Cookie berlaku selama 30 hari
                    setcookie('username', $name, time() + (86400 * 30), "/");
                    setcookie('role', $role, time() + (86400 * 30), "/");

                    if ($role == 'petugas' ) {
                        header("Location: index.php");
                        exit();
                    }
                }
            }
        }
    }
    
    public function register($username, $email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM pembeli WHERE nama_pembeli = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $this->displayError("Username atau email sudah ada");

        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("INSERT INTO pembeli (nama_pembeli, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();
    
            return true;
        }
    }
}
