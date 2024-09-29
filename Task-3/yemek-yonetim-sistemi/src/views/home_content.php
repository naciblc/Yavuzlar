<div class="container mt-3">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                Sepetinizde <?= htmlspecialchars($cart_count) ?> ürün var. Toplam: <?= number_format($cart_total, 2) ?> TL
                <a href="/views/cart.php" class="btn btn-primary btn-sm float-end">Sepeti Görüntüle</a>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-12">
            <h5>Aktif Kuponlar:</h5>
            <?php
 
            $stmt = $pdo->query("SELECT dc.*, r.name AS restaurant_name FROM discount_codes dc JOIN restaurants r ON dc.restaurant_id = r.id");
            $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($discounts) > 0): ?>
                <div class="alert alert-warning animate__animated animate__flash" role="alert">
                    <strong>Kuponlarınız:</strong>
                    <ul class="list-group">
                        <?php foreach ($discounts as $discount): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($discount['code']) ?></strong> - <?= htmlspecialchars($discount['discount_percentage']) ?>% indirim
                                <br>
                                Restoran: <?= htmlspecialchars($discount['restaurant_name']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
                <p>Henüz aktif kupon bulunmamaktadır.</p>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="row">
        <?php foreach ($restoranlar as $restoran): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($restoran['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($restoran['address']) ?></p>
                        <p class="card-text">Puan: <?= htmlspecialchars($restoran['rating']) ?></p>
                        <a href="/views/restaurant.php?id=<?= $restoran['id'] ?>" class="btn btn-primary">Yemekleri Gör</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
