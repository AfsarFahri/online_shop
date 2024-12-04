<?php
$message = ""; // Variabel untuk pesan konfirmasi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "online_shop");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Ambil data file gambar
    $image_file = $_FILES['image_file']['name'];
    $image_tmp = $_FILES['image_file']['tmp_name'];
    $image_path = "uploads/" . $image_file;

    // Pastikan gambar berhasil diupload
    if (move_uploaded_file($image_tmp, $image_path)) {
        // Query untuk memasukkan data produk baru
        $sql = "INSERT INTO products (name, description, price, image_file) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $name, $description, $price, $image_file);

        if ($stmt->execute()) {
            $message = "Produk berhasil ditambahkan! <a href='products_admin.php'>Kembali ke produk</a>";
        } else {
            $message = "Gagal menambahkan produk: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "Sorry, your file was not uploaded.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale A Product</title>
    <style>
        /* Style sesuai dengan desain Anda */
        * {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-container input, 
        .form-container textarea, 
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-container button {
            background: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container button:hover {
            background: #0056b3;
        }

        .confirmation-message {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }

        .confirmation-message a {
            color: #007BFF;
            text-decoration: none;
        }

        .confirmation-message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sale A Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Product Description" rows="4" required></textarea>
            <input type="number" name="price" placeholder="Product Price" required>
            <input type="file" name="image_file" required> <!-- Form untuk mengupload gambar -->
            <button type="submit">Sell Product</button>
        </form>
        <?php if (!empty($message)) { ?>
            <div class="confirmation-message"><?php echo $message; ?></div>
        <?php } ?>
    </div>
</body>
</html>
            