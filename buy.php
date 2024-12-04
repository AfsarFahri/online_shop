<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        img {
        max-width: 100%;
        max-height: 300px; /* Atur tinggi maksimal */
        width: auto;
        height: auto;
        display: block; /* Hindari teks yang berdekatan */
        margin: 0 auto; /* Pusatkan gambar */
    }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .back-button img {
            width: 30px;
            height: 30px;
        }

        .back-button img:hover {
            opacity: 0.8;
        }

        .product-detail {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .product-detail h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .product-detail img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .product-detail h2 {
            font-size: 20px;
            color: #555;
            margin: 10px 0;
        }

        .product-detail p {
            color: #777;
            font-size: 14px;
            line-height: 1.5;
            margin: 10px 0;
        }

        .product-detail strong {
            display: block;
            font-size: 18px;
            color: #007BFF;
            margin-top: 15px;
        }

        .buy-button {
            display: inline-block;
            margin-top: 20px;
            background: #007BFF; /* Biru */
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px;
            border: none;
            font-size: 14px;
            cursor: pointer;
        }

        .buy-button:hover {
            background: #0056b3; /* Biru lebih gelap saat hover */
        }
    </style>
    <script>
        function purchaseProduct() {
            alert("Produk berhasil dibeli!");
        }
    </script>
</head>
<body>
    <!-- Tombol kembali -->
    <button class="back-button" onclick="location.href='products.php'">
        <img src="https://cdn-icons-png.flaticon.com/512/93/93634.png" alt="Kembali">
    </button>

    <?php
    if (!isset($_GET['product_id'])) {
        echo "<div class='product-detail'><h1>Produk tidak ditemukan.</h1></div>";
        exit;
    }

    $product_id = $_GET['product_id'];

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "online_shop");

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Query untuk mendapatkan data produk berdasarkan ID
    $sql = "SELECT name, description, price, image_file FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $description, $price, $image_file);
        $stmt->fetch();

        echo '<div class="product-detail">';
        echo '<h1>Detail Produk</h1>';
        echo '<img src="uploads/' . $image_file . '" alt="' . $name . '">';
        echo '<h2>' . $name . '</h2>';
        echo '<p>' . $description . '</p>';
        echo '<strong>Harga: Rp ' . number_format($price, 2, ',', '.') . '</strong>';
        echo '<button class="buy-button" onclick="purchaseProduct()">Beli Produk</button>';
        echo '</div>';
    } else {
        echo "<div class='product-detail'><h1>Produk tidak ditemukan.</h1></div>";
    }

    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
