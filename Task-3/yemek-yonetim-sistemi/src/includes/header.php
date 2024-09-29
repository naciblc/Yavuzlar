<?php

$total_items = 0;

 
if (isset($_SESSION['cart'])) {
    $total_items = count($_SESSION['cart']);
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/index.php">Yavuz Sepeti</a>
    <div class="ml-auto">
        <a href="/views/cart.php" class="btn btn-primary">
            Sepet <span class="badge bg-light text-dark"><?= $total_items ?></span>
        </a>
    </div>
</nav>
