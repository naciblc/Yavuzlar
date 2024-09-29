<?php
 
session_start();

 
include '../config/database.php';

 
if (!isset($_SESSION['username'])) {
    header("Location: /views/login.php");
    exit();
}

 
if (!isset($_GET['id'])) {
    header("Location: /views/manage_restaurants.php");
    exit();
}

$restaurant_id = $_GET['id'];

 
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->execute([$restaurant_id]);
$restaurant = $stmt->fetch();

if (!$restaurant) {
    header("Location: /views/manage_restaurants.php");
    exit();
}

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];

 
    $stmt = $pdo->prepare("UPDATE restaurants SET name = ?, address = ? WHERE id = ?");
    $stmt->execute([$name, $address, $restaurant_id]);

 
    header("Location: /views/manage_restaurants.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoranı Güncelle</title>
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
        <h1>Restoranı Güncelle</h1>

        <form action="edit_restaurant.php?id=<?php echo $restaurant_id; ?>" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Restoran Adı</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($restaurant['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adres</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($restaurant['address']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success mt-3">
                Restoran başarıyla güncellendi!
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
