<?php
 


 
include '../config/database.php';

if (isset($_GET['id'])) {
    $restaurant_id = $_GET['id'];
    $_SESSION['restaurant_id'] = $restaurant_id; // Oturuma restoran ID'sini ekleyin

 
    $stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ?");
    $stmt->execute([$restaurant_id]);
    $restaurant = $stmt->fetch();

    if ($restaurant) {
 
        $stmt = $pdo->prepare("SELECT * FROM foods WHERE restaurant_id = ?");
        $stmt->execute([$restaurant_id]);
        $yemekler = $stmt->fetchAll();
    } else {
        $error_message = "Restoran bulunamadı.";
    }
} else {
    $error_message = "Restoran ID belirtilmedi.";
}


?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemekler - <?= htmlspecialchars($restaurant['name'] ?? 'Restoran') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .restaurant-header {
            margin-bottom: 20px;
            text-align: center;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                Sepetinizde <?= htmlspecialchars($cart_count) ?> ürün var. Toplam: <?= number_format($cart_total, 2) ?> TL
                <a href="/views/cart.php" class="btn btn-primary btn-sm float-end">Sepeti Görüntüle</a>
            </div>
        </div>
    </div>
<div class="container mt-4">
    <?php if (isset($restaurant)): ?>
        <div class="restaurant-header">
            <h2>Restoran: <?= htmlspecialchars($restaurant['name']) ?></h2>
        </div>

        <div class="row">
            <?php foreach ($yemekler as $yemek): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($yemek['name']) ?></h5>
                            <p class="card-text">Fiyat: <?= htmlspecialchars($yemek['price']) ?> TL</p>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($yemek['name']) ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars($yemek['price']) ?>">
                                <button type="submit" class="btn btn-success">Sepete Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>
</div>

<script>
function addToCart(yemekAd, fiyat) {
    Swal.fire(
        'Eklendi!',
        yemekAd + ' sepete eklendi.',
        'success'
    );
}
</script>

</body>
</html>
