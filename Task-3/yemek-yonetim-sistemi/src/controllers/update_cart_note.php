<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'], $_POST['note'])) {
    $index = (int)$_POST['index'];
    $note = htmlspecialchars($_POST['note']);

 
    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['not'] = $note;
    }
}

 
header('Location: /views/cart.php');
exit();
?>
