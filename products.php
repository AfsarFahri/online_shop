<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    
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
        }

        h1 {
            font-size: 36px;
            color: #333;
            margin: 20px 0 10px;
            text-align: center;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-grow: 1;
            width: 100%;
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

        .product-card p {
            color: #555;
            font-size: 14px;
            margin: 10px 0;
        }

        .product-card span {
            font-weight: bold;
            color: #007BFF;
            font-size: 16px;
            margin: 10px 0;
        }

        .button-group {
            display: flex; /* Menyusun tombol dalam satu baris */
            justify-content: center;
            gap: 10px; /* Memberikan jarak antar tombol */
            margin-top: 15px; /* Menambahkan jarak atas */
        }

        .product-card button {
            background: #007BFF; /* Warna biru */
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .product-card button:hover {
            background: #0056b3; /* Biru lebih gelap saat hover */
        }

        .footer {
            margin-top: auto;
            padding: 20px;
            width: 100%;
            text-align: center;
        }

        .logout-button {
            background-color: #FF4D4D; /* Tombol logout berwarna merah */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .logout-button:hover {
            background-color: #cc0000; /* Warna lebih gelap saat hover */
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Our Products</h1>
        <div class="products-container">
            <?php
            // Koneksi ke database
            $conn = new mysqli("localhost", "root", "", "online_shop");

            // Periksa koneksi
            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            // Query untuk mendapatkan semua produk
            $sql = "SELECT id, name, description, price, image_file FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    // Menampilkan gambar dari folder "uploads"
                    if (!empty($row['image_file']) && file_exists("uploads/" . $row['image_file'])) {
                        echo '<img src="uploads/' . $row['image_file'] . '" alt="Product Image">';
                    } else {
                        echo '<img src="default.png" alt="Default Image">'; // Gunakan gambar default jika file tidak ditemukan
                    }
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '<span>Rp ' . number_format($row['price'], 2, ',', '.') . '</span>';
                    echo '<div class="button-group">';
                    echo '<form action="buy.php" method="get">';
                    echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                    echo '<button type="submit">Buy Product</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Tidak ada produk yang tersedia.</p>';
            }

            $conn->close();
            ?>
        </div>
    </div>
    <div class="footer">
        <button class="logout-button" onclick="location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
