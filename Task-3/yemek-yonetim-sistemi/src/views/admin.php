<?php
session_start();
include '../config/database.php'; 

 
$stmt = $pdo->query("SELECT id, name, address, rating FROM restaurants");
$restoranlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

 
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
$cart_total = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'fiyat')) : 0;

 
$content = 'admin_content.php';
include 'layout.php';
?>
