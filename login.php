<?php
session_start();
$conn = new mysqli("localhost", "root", "", "online_shop");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password yang dimasukkan dengan password terenkripsi di database
        if (password_verify($password, $user['password'])) {
            // Set session untuk user yang login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Role untuk mengarahkan ke halaman yang sesuai

            // Redirect ke halaman berdasarkan role
            if ($user['role'] === 'admin') {
                header("Location: products_admin.php"); // Halaman admin
            } else {
                header("Location: products.php"); // Halaman user biasa
            }
            exit();
        } else {
            $error_message = "Login gagal. Periksa username atau password.";
        }
    } else {
        $error_message = "Login gagal. Periksa username atau password.";
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
            box-sizing: border-box;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 0.9em;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 0.9em;
        }

        .signup-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Media Queries untuk Responsivitas */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            form {
                padding: 15px;
            }

            input[type="text"],
            input[type="password"],
            button {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            form {
                padding: 10px;
            }

            input[type="text"],
            input[type="password"],
            button {
                font-size: 0.8rem;
                padding: 8px; /* Perkecil padding */
            }
        }
    </style>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit" name="login">Login</button>

        <!-- Tempat untuk menampilkan pesan kesalahan -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Tautan untuk sign-up -->
        <div class="signup-link">
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>
    </form>
</body>
</html>
