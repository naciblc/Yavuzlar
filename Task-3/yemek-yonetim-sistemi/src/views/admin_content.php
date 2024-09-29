<?php
 
 

 
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Yönetici';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet"> <!-- Kendi stil dosyanızı ekleyin -->
</head>
<body>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Hoş Geldiniz, <?php echo $username; ?>!</h1>
                <div class="list-group">
                    <a href="/views/manage_users.php" class="list-group-item list-group-item-action list-group-item-primary">Kullanıcıları Yönet</a>
                    <a href="/views/manage_restaurants.php" class="list-group-item list-group-item-action list-group-item-secondary">Restoranları Yönet</a>
                    <a href="/views/manage_foods.php" class="list-group-item list-group-item-action list-group-item-success">Yemekleri Yönet</a>
                    <a href="/views/manage_comments.php" class="list-group-item list-group-item-action list-group-item-info">Yorumları Yönet</a>
                    <a href="/views/admin_add_discount.php" class="list-group-item list-group-item-action list-group-item-warning">İndirim Kodu Ekle</a>
                    <a href="/views/admin_view_discounts.php" class="list-group-item list-group-item-action list-group-item-light">İndirim Kodlarını Görüntüle</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
