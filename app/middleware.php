<?php
session_start();

// Fungsi untuk memeriksa apakah pengguna telah login dengan memeriksa session atau cookie
function isUserLoggedIn() {
    return isset($_SESSION['user_id']) || (isset($_COOKIE['user_id']) && isset($_COOKIE['username']));
}

// Fungsi untuk memeriksa apakah pengguna memiliki peran sebagai petugas dengan memeriksa session atau cookie
function isUserPetugas() {
    return (isset($_SESSION['role']) && $_SESSION['role'] == 'petugas') ||
           (isset($_COOKIE['role']) && $_COOKIE['role'] == 'petugas');
}

// Middleware untuk memeriksa apakah pengguna memiliki akses yang diperlukan
function checkAccess() {
    if (!isUserLoggedIn()) {
        // Jika pengguna belum login, arahkan ke halaman login
        header("Location: login.php");
        exit;
    } elseif (!isUserPetugas()) {
        header("Location: store.php");
    } else {
        // Jika pengguna login menggunakan cookie, atur session
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
            $_SESSION['user_id'] = $_COOKIE['user_id'];
            $_SESSION['username'] = $_COOKIE['username'];
            if (isset($_COOKIE['role'])) {
                $_SESSION['role'] = $_COOKIE['role'];
            }
        }
    }
}

// Gunakan middleware di halaman yang memerlukan akses petugas
checkAccess();
?>
