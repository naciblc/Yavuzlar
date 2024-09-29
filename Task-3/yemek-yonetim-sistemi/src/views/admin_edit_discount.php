<?php
session_start();
include '../config/database.php';

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $code = htmlspecialchars($_POST['code']);
    $discount_percentage = (float)$_POST['discount_percentage'];
    $restaurant_id = (int)$_POST['restaurant_id'];

    $stmt = $pdo->prepare("UPDATE discount_codes SET code = ?, discount_percentage = ?, restaurant_id = ? WHERE id = ?");
    $stmt->execute([$code, $discount_percentage, $restaurant_id, $id]);

 
    header("Location: admin_view_discounts.php?success=1");
    exit();
}

 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM discount_codes WHERE id = ?");
$stmt->execute([$id]);
$discount_code = $stmt->fetch(PDO::FETCH_ASSOC);

 
if (!$discount_code) {
    echo "<div class='alert alert-danger'>İndirim kodu bulunamadı.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İndirim Kodu Güncelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>İndirim Kodu Güncelle</h2>
        <form method="POST" action="admin_edit_discount.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($discount_code['id']) ?>">
            <div class="mb-3">
                <label for="code" class="form-label">İndirim Kodu</label>
                <input type="text" class="form-control" id="code" name="code" value="<?= htmlspecialchars($discount_code['code']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="discount_percentage" class="form-label">İndirim Yüzdesi (%)</label>
                <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="<?= htmlspecialchars($discount_code['discount_percentage']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="restaurant_id" class="form-label">Restoran</label>
                <select class="form-select" id="restaurant_id" name="restaurant_id" required>
                    <?php
 
                    $stmt = $pdo->query("SELECT id, name FROM restaurants");
                    while ($row = $stmt->fetch()) {
                        $selected = ($row['id'] == $discount_code['restaurant_id']) ? 'selected' : '';
                        echo "<option value=\"{$row['id']}\" $selected>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="admin.php" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
