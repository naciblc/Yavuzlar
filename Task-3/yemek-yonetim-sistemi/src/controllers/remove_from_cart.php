<?php
session_start();
include '../config/database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    $index = $_POST['index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); 
    }
    
    header('Location: /views/cart.php'); 
    exit;
}
?>

