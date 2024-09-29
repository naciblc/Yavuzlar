<?php
session_start();
include '../config/database.php';

 
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

 
if (!$user_id) {
    header("Location: /views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['cart']) && isset($_SESSION['restaurant_id']) && isset($_POST['total'])) {
        $cart = $_SESSION['cart'];
        $restaurant_id = (int)$_SESSION['restaurant_id'];
        $total = (float)$_POST['total'];
        $status = 'Onaylandı'; 

 
        $notes = [];
        foreach ($cart as $item) {
            if (isset($item['note'])) {
                $notes[] = $item['note'];
            }
        }
        $notes_string = implode(", ", $notes);
        try {
 
            $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Bilinmiyor';
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, restaurant_id, total, status, note, username, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, $restaurant_id, $total, $status, $notes_string, $username]);
            $order_id = $pdo->lastInsertId();

 
            foreach ($cart as $item) {
                $food_name = isset($item['ad']) ? $item['ad'] : 'Bilinmiyor';
                $price = isset($item['fiyat']) ? $item['fiyat'] : 0.00;
                $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                $food_id = isset($item['food_id']) ? $item['food_id'] : null;

                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_id, food_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$order_id, $food_id, $food_name, $quantity, $price]);
            }

 
            unset($_SESSION['cart']);
            unset($_SESSION['discount_percentage']);

 
            header('Location: /views/orders.php');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "Sipariş işlemi sırasında bir hata oluştu.";
        }
    } else {
        echo "Sepetiniz boş veya gerekli bilgiler eksik.";
    }
}
?>
