<?php
session_start();

 
if (isset($_SESSION['cart'])) {
    $index = (int)$_POST['index'];
    $note = htmlspecialchars($_POST['note']);

 
    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['note'] = $note;
    }
}

 
header("Location: /views/cart.php");
exit();
