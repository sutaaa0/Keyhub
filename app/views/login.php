<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

$conn = require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Auth.php";


$auth = new Auth($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth->login();
}
?>


<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../styles/global.css">
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

<div class="flex justify-between items-center h-screen w-full relative">
        <div class="flex flex-1">
            <img src="../assets/auth.jpg" alt="logo" class="object-cover w-full h-[776px]">
        </div>
        <div class="flex justify-center items-center flex-1">
            <div class="absolute top-10 right-10">
                <a href="register.php">
                    <button class="btn btn-ghost">Register</button>
                </a>
            </div>
            <div>
                <form action="" method="POST" class="flex flex-col justify-center gap-y-5 items-center">
                    <div class="flex flex-col gap-y-2 justify-center items-center">
                        <h1 class="font-bold text-3xl">Login</h1>
                        <p class="text-base text-slate-500 font-normal">Enter your details below to login</p>
                    </div>
                    <div class="flex flex-col justify-center items-center gap-y-2">
                        <label class="input input-bordered flex items-center gap-2 h-[35px] w-[350px]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
                            </svg>
                            <input type="email" class="grow" placeholder="Email" name="email" required/>
                        </label>
                        <label class="input input-bordered flex items-center gap-2 h-[35px] w-[350px]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                                <path fill-rule="evenodd" d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z" clip-rule="evenodd" />
                            </svg>
                            <input type="password" class="grow" placeholder="Password" name="password" required/>
                        </label>
                        <div>
                            <button class="h-[35px] w-[350px] py-2 px-3 rounded-3xl bg-black text-white rounded-md text-sm font-bold hover:bg-white hover:text-black focus:outline-none focus:ring focus:ring-offset-2 focus:ring-black focus:ring-opacity-50 focus:ring-offset-black transition duration-300 ease-in" type="submit" name="submit">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


