<?php
// Variabel untuk menyimpan pesan kesalahan
$error_message = "";

// Fungsi untuk menangani registrasi pengguna
function register($username, $email, $password, &$error_message, $role = 'user') {
    $conn = new mysqli("localhost", "root", "", "online_shop");

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Periksa apakah email sudah terdaftar
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error_message = "Email sudah digunakan! Gunakan email lain.";
        $stmt_check->close();
        $conn->close();
        return; // Hentikan proses registrasi
    }

    $stmt_check->close();

    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Simpan data ke tabel users
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        header("Location: login.php"); // Alihkan ke layar login
        exit();
    } else {
        $error_message = "Terjadi kesalahan saat mendaftarkan pengguna. Coba lagi.";
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi konfirmasi password
    if ($password !== $confirm_password) {
        $error_message = "Password dan Confirm Password tidak cocok!";
    } else {
        register($username, $email, $password, $error_message);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        input[type="email"],
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

        .redirect-login {
            margin-top: 15px;
            font-size: 0.9em;
        }

        .redirect-login a {
            color: #007BFF;
            text-decoration: none;
        }

        .redirect-login a:hover {
            text-decoration: underline;
        }

        /* Media Queries untuk Responsivitas */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            form {
                width: 100%;
                max-width: 100%; /* Form memenuhi layar pada perangkat kecil */
                padding: 15px;
            }

            input[type="text"],
            input[type="email"],
            input[type="password"],
            button {
                font-size: 0.9rem; /* Ukuran font lebih kecil */
            }
        }

        @media (max-width: 480px) {
            form {
                padding: 10px;
            }

            input[type="text"],
            input[type="email"],
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
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    
    <button type="submit" name="register">Register</button>

    <!-- Pesan kesalahan akan muncul di sini -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Tautan ke halaman login -->
    <div class="redirect-login">
        Have an account? <a href="login.php">Log in</a>
    </div>
</form>
</body>
</html>
