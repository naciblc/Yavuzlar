<?php
 
session_start();

 
include '../config/database.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = htmlspecialchars($_POST['status']);

 
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);

 
    header('Location: /views/orders.php');
    exit;
} else {
    echo "Gerekli bilgiler eksik.";
}
?>
