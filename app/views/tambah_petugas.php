<?php
require_once '../middleware.php';
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Petugas.php";

$petugas = new Petugas($conn);

if (isset($_POST["submit"])) {
        if ($petugas->create($_POST)) {
            header("Location: tambah_petugas.php"); 
            exit;
        }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Petugas</title>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo-dot">
                        <circle cx="12" cy="17" r="1"/>
                        <path d="M3 7v6h6"/>
                        <path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/>
                    </svg>
                    Back
                </button>
            </a>
        </div>
        <div class="flex flex-col justify-center items-center h-screen w-full">
            <h1 class="font-bold text-2xl">Form Add Petugas</h1>
            <form action="" method="post" class="flex flex-col justify-center items-center gap-y-4 my-8 w-[400px]">
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" /></svg>
                    <input name="nama_petugas" type="text" class="grow" placeholder="Nama Petugas" required />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="email" type="email" class="grow" placeholder="Email" required />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input name="password" type="password" class="grow" placeholder="Password" required />
                </label>
                <label class="input input-bordered flex items-center gap-2 w-80">
                    <input type="" placeholder="You can't touch this" class="grow" name="role" value="petugas" readonly/>
                </label>
                <button class="btn btn-neutral w-80" type="submit" name="submit">
                    Submit
                </button>
            </form>
        </div>
    </div>
</body>
</html>
