<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $yemek_ad = $_POST['name'];
    $fiyat = $_POST['price'];

 
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

 
    $_SESSION['cart'][] = [
        'ad' => $yemek_ad,
        'fiyat' => $fiyat,
    ];

 
    header("Location: /views/cart.php");
    exit();
}
?>
