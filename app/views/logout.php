<?php
session_start();
session_unset();
session_destroy();

// Hapus cookie dengan mengatur waktu kedaluwarsa ke masa lalu
setcookie('user_id', '', time() - 3600, "/");
setcookie('username', '', time() - 3600, "/");
setcookie('role', '', time() - 3600, "/");

header("Location: login.php");
exit;
?>
