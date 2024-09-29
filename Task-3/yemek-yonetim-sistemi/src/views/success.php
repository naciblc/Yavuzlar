<?php

$redirect_url = 'restaurant_panel.php'; 
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Başarılı</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta http-equiv="refresh" content="3;url=<?= htmlspecialchars($redirect_url); ?>">
</head>
<body>
    <div class="container mt-5 text-center">
        <h2>İşlem Başarılı!</h2>
        <p>İşleminiz başarıyla gerçekleştirildi. 3 saniye içinde yönlendirileceksiniz.</p>
        <p>Eğer yönlendirilmezseniz, <a href="<?= htmlspecialchars($redirect_url); ?>">buraya tıklayın</a>.</p>
    </div>
</body>
</html>
