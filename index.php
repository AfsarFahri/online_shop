<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElectShop</title>
    <style>
        /* Gaya Umum */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; /* Warna latar belakang yang ringan */
            color: #343a40; /* Warna teks gelap */
            text-align: center;
        }

        .container {
            max-width: 90%; /* Batasi lebar kontainer agar pas di layar kecil */
            padding: 20px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            word-wrap: break-word; /* Menghindari teks panjang meluber */
        }

        .button-container {
            margin-top: 20px;
        }

        .button {
            display: inline-block; /* Supaya padding berlaku dengan benar */
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        footer {
            margin-top: 50px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Media Queries untuk Responsivitas */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem; /* Kurangi ukuran font pada layar kecil */
            }

            .button {
                font-size: 0.9rem; /* Kurangi ukuran tombol */
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5rem; /* Kurangi ukuran font lebih jauh */
            }

            .button {
                font-size: 0.8rem;
                padding: 6px 12px;
            }

            footer {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome To ElectShop</h1>
        <div class="button-container">
            <a href="register.php" class="button">Register</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 ElectShop. Afsar Fakhri</p>
    </footer>
</body>
</html>
