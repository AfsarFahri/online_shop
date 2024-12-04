<?php
session_start();

// Periksa apakah pengguna telah login dan memiliki peran admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Arahkan ke halaman login jika bukan admin
    exit;
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "online_shop");

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani penghapusan produk (jika ada permintaan)
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    header("Location: products_admin.php"); // Muat ulang halaman setelah penghapusan
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow-x: none;
        }

        h1 {
            font-size: 36px;
            color: #333;
            margin: 20px 0;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
        }

        .product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-card img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .product-card span {
            font-weight: bold;
            color: #007BFF;
            font-size: 16px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        button {
            background: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .footer {
            margin-top: auto;
            padding: 20px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <div class="products-container">
        <?php
        // Query untuk mendapatkan semua produk
        $sql = "SELECT id, name, description, price, image_file FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                // Menampilkan gambar produk
                if (!empty($row['image_file']) && file_exists("uploads/" . $row['image_file'])) {
                    echo '<img src="uploads/' . $row['image_file'] . '" alt="Product Image">';
                } else {
                    echo '<img src="default.png" alt="Default Image">'; // Gambar default
                }
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<span>Rp ' . number_format($row['price'], 2, ',', '.') . '</span>';
                echo '<div class="button-group">';
                echo '<a href="edit.php?id=' . $row['id'] . '"><button>Edit</button></a>';
                echo '<a href="products_admin.php?delete=' . $row['id'] . '"><button style="background-color: #FF4D4D;">Delete</button></a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>Tidak ada produk yang tersedia.</p>';
        }
        ?>
    </div>
    <div class="footer">
        <button onclick="location.href='sale.php'">Add Product</button>
        <button style="background-color: #FF4D4D;" onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>