<?php
 
session_start();

 
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
    header("Location: /views/login.php");
    exit();
}

 
$stmt = $pdo->query("SELECT * FROM restaurants");
$restaurants = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $restaurant_id = $_POST['restaurant_id'];

 
    $stmt = $pdo->prepare("INSERT INTO foods (name, price, restaurant_id) VALUES (?, ?, ?)");
    $stmt->execute([$name, $price, $restaurant_id]);

 
    header("Location: /views/manage_foods.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Yemek Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
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
        <h1>Yeni Yemek Ekle</h1>

        <form action="add_food_admin.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Yemek Adı</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Fiyat</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="restaurant_id" class="form-label">Restoran</label>
                <select class="form-select" id="restaurant_id" name="restaurant_id" required>
                    <option value="">Bir restoran seçin</option>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <option value="<?php echo $restaurant['id']; ?>"><?php echo htmlspecialchars($restaurant['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Yemek Ekle</button>
        </form>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success mt-3">
                Yemek başarıyla eklendi!
            </div>
        <?php endif; ?>

        <!-- Geri Dön Butonu -->
        <a href="/views/manage_foods.php" class="btn btn-secondary mt-3">Geri Dön</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
