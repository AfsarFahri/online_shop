<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "online_shop");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Jika ada file gambar yang di-upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $image_file = $_FILES['image_file']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image_file);

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            // Gambar berhasil diupload
        } else {
            echo "<p>Gagal meng-upload gambar.</p>";
            exit;
        }
    } else {
        // Ambil gambar lama jika tidak ada upload
        $sql = "SELECT image_file FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($image_file);
        $stmt->fetch();
        $stmt->close();
    }

    // Query update produk
    $sql = "UPDATE products SET name = ?, description = ?, price = ?, image_file = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $name, $description, $price, $image_file, $id);

    if ($stmt->execute()) {
        echo "<p>Produk berhasil diperbarui! <a href='products_admin.php'>Kembali ke produk</a></p>";
    } else {
        echo "<p>Gagal memperbarui produk: " . $conn->error . "</p>";
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
    <title>Edit Product</title>
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
            box-sizing: border-box;
        }

        h2 {
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        div {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
            box-sizing: border-box;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        textarea,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        textarea {
            resize: none;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background: #007BFF;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box;
        }

        button:hover {
            background: #0056b3;
        }

        .delete-button {
            background: #FF4D4D;
        }

        .delete-button:hover {
            background: #cc0000;
        }

        p {
            font-size: 16px;
            color: #555;
            text-align: center;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
    div {
        max-width: 70%; /* Lebar form lebih kecil */
        padding: 15px;
        margin: 0 auto; /* Pusatkan form */
    }

    h2 {
        font-size: 18px; /* Sedikit lebih kecil untuk menyesuaikan dengan form */
    }

    button {
        font-size: 12px; /* Perkecil ukuran tombol */
        padding: 6px; /* Kurangi padding tombol */
    }

    input[type="text"],
    textarea,
    input[type="number"] {
        font-size: 11px; /* Perkecil teks pada input */
        padding: 6px; /* Kurangi padding input */
    }

    label {
        font-size: 11px; /* Perkecil label */
    }
}



        @media (max-width: 480px) {
    div {
        max-width: 100%;
        padding: 10px;
        margin: 0 15px; /* Tambahkan margin di kiri dan kanan */
        box-sizing: border-box; /* Pastikan padding dan margin tidak memengaruhi lebar total */
    }

    h2 {
        font-size: 18px;
    }

    button {
        font-size: 14px;
        padding: 8px;
    }

    input[type="text"],
    textarea,
    input[type="number"] {
        font-size: 12px;
        padding: 8px;
    }

    label {
        font-size: 12px;
    }
}

    </style>
</head>
<body>
<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit;
}

$id = $_GET['id'];

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "online_shop");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan data produk berdasarkan ID
$sql = "SELECT name, description, price, image_file FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($name, $description, $price, $image_file);
    $stmt->fetch();
?>
    <div>
        <h2>Edit Product</h2>
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="old_image" value="<?php echo $image_file; ?>">

            <label>Product Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label>Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea>

            <label>Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>

            <label>Image (leave empty to keep current image):</label>
            <input type="file" name="image_file">

            <button type="submit">Update Product</button>
        </form>
        <form action="delete.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="delete-button">Delete Product</button>
        </form>
    </div>
<?php
} else {
    echo "<p>Produk dengan ID tersebut tidak ditemukan.</p>";
}

$stmt->close();
$conn->close();
?>
</body>
</html>
