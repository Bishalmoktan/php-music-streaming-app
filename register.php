<?php
require_once 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Registration successful! Please login.";
        header("location: login.php");
    } else {
        $error = "Username already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Music Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
 <style>
        body {
            background: #0F172A;
        }
        </style>
<body class="text-white">
    <div class="min-h-screen flex items-center justify-center">
        <div class="border border-gray-700  p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl mb-6 text-center">Register</h2>
            <?php if (isset($error)) { ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-100 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-100 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Register
                </button>
            </form>
            <p class="mt-4 text-center">
                Already have an account? <a href="login.php" class="text-blue-500">Login here</a>
            </p>
        </div>
    </div>
</body>
</html>