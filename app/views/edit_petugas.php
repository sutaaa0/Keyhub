<?php
require_once '../middleware.php';

$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Petugas.php";

$petugas = new Petugas($conn);

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $data = $petugas->getPetugas($id);
    
    if (!$data) {
        echo "Data petugas tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak diberikan.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($petugas->update($_POST, $id)) {
        header("Location: daftar_petugas.php");
        exit;
    } else {
        $petugas->displayError("Gagal memperbarui petugas.");
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Petugas</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Daisy UI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="flex flex-col justify-center items-center h-screen w-full relative">
        <div class="absolute top-20 left-20">
            <a href="daftar_petugas.php">
                <button class="btn flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo-dot"><circle cx="12" cy="17" r="1"/><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                    Back
                </button>
            </a>
        </div>
        <div class="flex flex-col justify-center items-center h-screen w-full">
            <h1 class="font-bold text-2xl">Ubah Petugas</h1>
            <form action="" method="post" class="flex flex-col justify-center items-center gap-y-4 my-8 w-[400px]">
                <input value="<?= htmlspecialchars($data["id_petugas"]) ?>" name="id" type="hidden" class="grow" required />
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="nama_petugas" type="text" class="grow" placeholder="Nama Petugas" required value="<?= htmlspecialchars($data["nama_petugas"]) ?>" />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="email" type="email" class="grow" placeholder="Email" required value="<?= htmlspecialchars($data["email"]) ?>" />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="password" type="password" class="grow" placeholder="Password (Optional)" />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80" disabled >
                    <input name="role" type="text" class="grow" required value="<?= htmlspecialchars($data["role"]) ?>" />
                </label>
                <button class="btn btn-neutral w-80" type="submit" name="submit">
                    Update
                </button>
            </form>
        </div>
    </div>
</body>
</html>
