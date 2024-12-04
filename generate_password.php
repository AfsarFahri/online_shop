<?php
// Password yang ingin di-hash
$password = "admin";

// Hash password menggunakan algoritma bcrypt
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Tampilkan hash password
echo "Password hashed: " . $hashed_password;
?>
