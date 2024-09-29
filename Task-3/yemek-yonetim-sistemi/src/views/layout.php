<?php 
include '../config/database.php'; 
 
$is_logged_in = isset($_SESSION['username']);
$is_firma = isset($_SESSION['role']) && $_SESSION['role'] === 'firma'; 
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; 
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yavuz Sepeti</title>
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="/views/home.php">
                <img src="s.jpg" alt="Logo" class="logo" style="border-radius: 0.5rem; margin-right: 0.5rem;">
                Yavuz Sepeti
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <?php if ($is_logged_in && !$is_firma): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/home.php">Anasayfa</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($is_logged_in): ?>
                        <?php if ($is_admin): ?>
                           
                            <li class="nav-item">
                                <a class="nav-link" href="/views/admin.php">Admin Paneli</a>
                            </li>
                        <?php endif; ?>

                        <?php if ($is_firma): ?>
                           
                            <li class="nav-item">
                                <a class="nav-link" href="/views/restaurant_panel.php">Restoran Siparişlerim</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/views/add_food.php">Yemek Ekle</a> 
                            </li>
                        <?php else: ?>
                           
                            <li class="nav-item">
                                <a class="nav-link" href="/views/orders.php">Siparişlerim</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/views/profile.php">Profilim</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/controllers/logout.php">Çıkış Yap (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                        </li>
                    <?php else: ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="/views/login.php">Giriş Yap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/views/register.php">Kayıt Ol</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

  
    <div class="container mt-4">
        <?php 
 
        if (isset($content) && !empty($content)) {
            include $content; 
        } else {
            echo "<p>İçerik yüklenemedi.</p>";
        }
        ?>
    </div>

  
    <footer class="bg-light py-3 mt-4">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Yavuz Sepeti. Tüm hakları saklıdır.</p>
        </div>
    </footer>
    
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
