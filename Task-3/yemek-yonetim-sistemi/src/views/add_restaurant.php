<?php


include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];

 
    $stmt = $pdo->prepare("INSERT INTO restaurants (name, description, location) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $location]);

 
    header("Location: /views/manage_restaurants.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Ekle</title>
    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Yeni Restoran Ekle</h1>

        <form action="add_restaurant.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Restoran Adı</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Konum</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <button type="submit" class="btn btn-primary">Restoran Ekle</button>
        </form>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success mt-3">
                Restoran başarıyla eklendi!
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
