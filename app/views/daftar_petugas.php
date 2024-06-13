<?php 
    // session_start();

    require_once '../middleware.php';
    

    $conn = require_once __DIR__ . "/../config/database.php";
    require_once __DIR__ . "/../models/Petugas.php";
    
    $petugas = new Petugas($conn);
    
    $searchTerm = isset($_GET["search"]) ? $_GET["search"] : "";
    
    if($searchTerm) {
        $result = $petugas->search($searchTerm);
    } else {
        $result = $petugas->getAll();
    }
?>


<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- talwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end -->
    <!-- Daisy UI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- end -->
    <!-- global css -->
    <link rel="stylesheet" href="../styles/global.css">
    <!-- end -->
    <style>
        @font-face {
        font-family: "Geist-Regular";
        src: url('../fonts/Geist-Regular.ttf');
        }
        body {
            font-family: "Geist-Regular", sans-serif; 
        }
    </style>
</head>
<body>
    <div class="flex items-center justify-center w-full h-screen relative">
    <div class="absolute top-20 left-20">
            <a href="index.php">
                <button class="btn flex gap-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo-dot"><circle cx="12" cy="17" r="1"/><path d="M3 7v6h6"/><path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13"/></svg>
                    <p>
                        back
                    </p>
                </button>
            </a>
        </div>
        <div class="flex flex-col">
            <div class="py-4">
                <h1 class="font-bold text-4xl">Daftar Petugas</h1>
            </div>
            <div class="flex space-x-3 mb-3">
                <form action="" method="get" class="flex flex-1 gap-x-3">
                <label class="input input-bordered flex items-center gap-2">
                    <input type="text" class="grow" placeholder="Search" name="search" />
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                </label>
                <button class="btn" type="submit">Cari..</button>
                </form>
                <a href="tambah_petugas.php"><button class="btn">Tambah Petugas</button></a>
            </div>
            <div class="table-container">
                <table class="table">
                    <!-- head -->
                    <thead>
                    <tr class="sticky top-0 bg-white">
                        <th class="px-4"></th>
                        <th class="text-base">Nama Petugas</th>
                        <th class="text-base">Email</th>
                        <th class="text-base">Role</th>
                        <th class="text-base">Alat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($result)) : ?>
                        <tr>
                            <td colspan="5" class="text-center">No data</td>
                        </tr>
                    <?php endif ?>
                    <?php $counter = 1 ?>
                    <?php foreach ($result as $data) : ?>
                        <tr class="h-16">
                            <th><?= $counter ?></th>
                            <td><?= $data["nama_petugas"] ?></td>
                            <td><?= $data["email"] ?></td>
                            <td><?= $data["role"] ?></td>
                            <td>
                                <div class="flex gap-x-3">
                                <a href="edit_petugas.php?id=<?= $data["id_petugas"] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-line"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/><path d="m15 5 3 3"/></svg>
                                </a>
                                <button onclick="return confirm('Are you sure?')">
                                    <a href="hapus_petugas.php?id=<?= $data["id_petugas"] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>   
                                    </a>
                                </button>
                                </div>
                            </td>
                        </tr>
                        <?php $counter++ ?>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
