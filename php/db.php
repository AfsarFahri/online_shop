<?php
require_once 'config.php';

function getProducts() {
    global $conn;
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

function addProduct($name, $price, $description) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $description); 
    $stmt->execute();
    $stmt->close();
}

function updateProduct($id, $name, $price, $description) {
    global $conn;
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sd", $name, $price, $description, $id);    
    $stmt->execute();
    $stmt->close();
}

function deleteProduct($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>