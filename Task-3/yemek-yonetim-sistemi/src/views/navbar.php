<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="s.jpg" alt="Logo" class="logo" style="border-radius: 0.5rem; margin-right: 0.5rem; width: 50px; height: auto;">
            Yavuz Sepeti
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/views/home.php">Anasayfa</a>
                </li>
                <?php if (isset($_SESSION['username'])): ?>
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
                <li class="nav-item">
                    <a class="nav-link" href="/views/cart.php">Sepet</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="/public/css/styles.css">
