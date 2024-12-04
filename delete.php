<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "online_shop");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil ID produk dari form
    $product_id = $_POST['product_id'];

    // Query untuk mendapatkan nama file gambar dari database
    $sql = "SELECT image_file FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($image_file);
    $stmt->fetch();
    $stmt->close();

    // Hapus file gambar dari server
    $image_path = "uploads/" . $image_file;
    if (file_exists($image_path)) {
        unlink($image_path); // Hapus file gambar
    }

    // Query untuk menghapus produk dari database
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<p>Produk berhasil dihapus! <a href='products.php'>Kembali ke daftar produk</a></p>";
    } else {
        echo "<p>Gagal menghapus produk: " . $conn->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            font-size: 28px;
            color: #333;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="hidden"] {
            display: none;
        }

        button {
            background: #FF4D4D;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 10px;
            border-radius: 4px;
        }

        button:hover {
            background: #cc0000;
        }

        p {
            font-size: 16px;
            color: #555;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

</html>
