<?php
// login.php

session_start();
include 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // 5. Fitur Backend: Hashing password admin
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: admin/dashboard.php');
            exit();
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Xiaomi Expert System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 6. Desain: Tema warna khas Xiaomi */
        :root {
            --color-primary: #FF6700; /* Oranye Khas Xiaomi */
            --color-secondary: #f4f4f4; /* Abu-abu muda/putih */
            --color-text: #333;
        }
        .bg-xiaomi { background-color: var(--color-primary); }
        .text-xiaomi { color: var(--color-primary); }
        .border-xiaomi { border-color: var(--color-primary); }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-6">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-3xl font-bold text-center mb-6 text-xiaomi">Admin Login</h2>
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= $error ?></span>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                    <input type="text" id="username" name="username" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-xiaomi">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                    <input type="password" id="password" name="password" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline focus:border-xiaomi">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-xiaomi hover:opacity-90 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-300">
                        Masuk
                    </button>
                </div>
            </form>
            <p class="text-center text-gray-500 text-xs mt-4">
                &copy; <?= date('Y') ?> Xiaomi Expert System
            </p>
        </div>
    </div>
</body>
</html>