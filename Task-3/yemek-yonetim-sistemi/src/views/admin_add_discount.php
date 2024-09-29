<?php
session_start();
include '../config/database.php';

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = htmlspecialchars($_POST['code']);
    $discount_percentage = (float)$_POST['discount_percentage'];
    $restaurant_id = (int)$_POST['restaurant_id']; // Restoran ID'si

    $stmt = $pdo->prepare("INSERT INTO discount_codes (code, discount_percentage, restaurant_id) VALUES (?, ?, ?)");
    $stmt->execute([$code, $discount_percentage, $restaurant_id]);

    echo "<div class='alert alert-success'>İndirim kodu başarıyla eklendi.</div>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İndirim Kodu Ekle - Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="s.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top"> Admin Paneli
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="/controllers/logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>İndirim Kodu Ekle</h2>
        <form method="POST" action="admin_add_discount.php">
            <div class="mb-3">
                <label for="code" class="form-label">İndirim Kodu</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
                <label for="discount_percentage" class="form-label">İndirim Yüzdesi (%)</label>
                <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required>
            </div>
            <div class="mb-3">
                <label for="restaurant_id" class="form-label">Restoran</label>
                <select class="form-select" id="restaurant_id" name="restaurant_id" required>
                    <?php
 
                    $stmt = $pdo->query("SELECT id, name FROM restaurants");
                    while ($row = $stmt->fetch()) {
                        echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ekle</button>
            <a href="admin.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
