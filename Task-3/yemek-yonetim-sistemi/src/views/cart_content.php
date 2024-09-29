<?php

include '../config/database.php';

$total = 0;
$indirimli_toplam = 0;
$error_message = '';

 
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promosyon_kodu'])) {
    $promosyon_kodu = htmlspecialchars($_POST['promosyon_kodu']);
    if (isset($_SESSION['restaurant_id'])) {
        $restaurant_id = (int)$_SESSION['restaurant_id'];

        $stmt = $pdo->prepare("SELECT discount_percentage FROM discount_codes WHERE code = ? AND restaurant_id = ?");
        $stmt->execute([$promosyon_kodu, $restaurant_id]);
        $discount = $stmt->fetch();

        if ($discount) {
            $discount_percentage = $discount['discount_percentage'];
            $_SESSION['discount_percentage'] = $discount_percentage;
        } else {
            $_SESSION['discount_percentage'] = 0;
            $error_message = "Geçersiz promosyon kodu.";
        }
    } else {
        $error_message = "Restoran ID'si bulunamadı.";
    }
}

 
$total = array_sum(array_column($cart, 'fiyat'));
$indirimli_toplam = $total;

if (isset($_SESSION['discount_percentage']) && $_SESSION['discount_percentage'] > 0) {
    $discount_percentage = $_SESSION['discount_percentage'];
    $indirimli_toplam = $total * (1 - $discount_percentage / 100);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetiniz</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        padding-top: 0; /* Üst boşluğu sıfırladık */
        margin: 0; /* Tarayıcı varsayılan marginlerini sıfırladık */
    }
    .cart-item {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }
    .cart-total, .indirimli-toplam {
        font-weight: bold;
        font-size: 1.5rem;
    }
    .promo-code-form {
        margin-top: 20px;
    }
    .error-message {
        color: #dc3545;
    }
    .total-card {
        border: 2px solid #007bff;
        border-radius: 10px;
        padding: 20px;
        background-color: #f0f8ff;
    }
</style>

</head>
<body>
<div class="container">
    <h2>Sepetiniz</h2>

    <?php if (count($cart) > 0): ?>
        <?php foreach ($cart as $index => $yemek): ?>
            <div class="cart-item d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($yemek['ad']) ?> - <?= htmlspecialchars($yemek['fiyat']) ?> TL</span>
                <form method="POST" action="/controllers/remove_from_cart.php" style="display: inline;">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                </form>
                <form method="POST" action="/controllers/update_cart.php" style="display: inline;">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <input type="text" name="note" placeholder="Not ekleyin..." class="form-control" style="width: 200px; display: inline-block;">
                    <button type="submit" class="btn btn-info btn-sm">Güncelle</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="total-card mt-4">
            <h3 class="cart-total">Toplam: <?= $total ?> TL</h3>
            <?php if (isset($indirimli_toplam) && $indirimli_toplam < $total): ?>
                <h3 class="indirimli-toplam">İndirimli Toplam: <?= number_format($indirimli_toplam, 2) ?> TL</h3>
            <?php endif; ?>
        </div>

        <div class="promo-code-form">
            <form method="POST" action="/views/cart.php">
                <div class="form-group">
                    <label for="promosyon_kodu">Promosyon Kodu</label>
                    <input type="text" class="form-control" id="promosyon_kodu" name="promosyon_kodu">
                </div>
                <button type="submit" class="btn btn-primary">Uygula</button>
            </form>

            <?php if ($error_message): ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
        </div>

        <form method="POST" action="/controllers/place_order.php" class="mt-3">
            <input type="hidden" name="total" value="<?= $indirimli_toplam ?>">
            <button type="submit" class="btn btn-success">Sipariş Ver</button>
        </form>
                
    <?php else: ?>
        <p>Sepetiniz boş.</p>
    <?php endif; ?>

    <!-- Sipariş sayfasına dönme butonu -->
    <a href="home.php" class="btn btn-secondary mt-3">Restoran Sayfasına Dön</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
