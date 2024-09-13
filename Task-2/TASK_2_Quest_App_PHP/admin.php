<?php
require 'functions/db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update'])) {
    $question = $_POST['question'];
    $options = json_encode($_POST['options']);
    $correctAnswer = $_POST['correct_answer'];

    
    $stmt = $pdo->prepare("INSERT INTO Questions (question, options, correct_answer) VALUES (:question, :options, :correct_answer)");
    $stmt->execute([
        ':question' => $question,
        ':options' => $options,
        ':correct_answer' => $correctAnswer
    ]);

    echo '<script>alert("Soru Eklendi");
    window.location.href = "admin.php";
    </script>';
}


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $questionId = (int)$_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM Questions WHERE id = :id");
    $stmt->execute([':id' => $questionId]);

    echo '<script>alert("Soru Silindi");
    window.location.href = "admin.php";
    </script>';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $options = json_encode($_POST['options']);
    $correctAnswer = $_POST['correct_answer'];

    $stmt = $pdo->prepare("UPDATE Questions SET question = :question, options = :options, correct_answer = :correct_answer WHERE id = :id");
    $stmt->execute([
        ':id' => $id,
        ':question' => $question,
        ':options' => $options,
        ':correct_answer' => $correctAnswer
    ]);

    echo '<script>alert("Soru Güncellendi");
    window.location.href = "admin.php";
    </script>';
}


$stmt = $pdo->query("SELECT * FROM Questions");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<link rel="stylesheet" href="styles.css">';
echo '<div class="container">';


if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['update'])) {
    echo '<h1>Soru Ekle</h1>';
    echo '<form method="POST" action="admin.php">';
    echo 'Soru: <input type="text" name="question" required><br>';
    echo 'Şık 1: <input type="text" name="options[]" required><br>';
    echo 'Şık 2: <input type="text" name="options[]" required><br>';
    echo 'Şık 3: <input type="text" name="options[]" required><br>';
    echo 'Şık 4: <input type="text" name="options[]" required><br>';
    echo 'Doğru Cevap (0-3): <input type="number" name="correct_answer" min="0" max="3" required><br>';
    echo '<button type="submit" value="Ekle">Soru Ekle</button>';
    echo '</form>';
    echo "<button onclick=window.location.href='index.php';>Ana Sayfa</button>";
    echo "<button onclick=window.location.href='uye.php';>Üye Ekle</button>";
}


echo '<h1>Soruları Listele</h1>';
echo '<table border="1">';
echo '<tr><th>ID</th><th>Soru</th><th>Şıklar</th><th>Doğru Cevap</th><th>İşlemler</th></tr>';

foreach ($questions as $question) {
    $options = json_decode($question['options'], true);
    $optionsList = implode(', ', $options);

    echo '<tr>';
    echo '<td>' . htmlspecialchars($question['id']) . '</td>';
    echo '<td>' . htmlspecialchars($question['question']) . '</td>';
    echo '<td>' . htmlspecialchars($optionsList) . '</td>';
    echo '<td>' . htmlspecialchars($question['correct_answer']) . '</td>';
    echo '<td>
            <a href="admin.php?action=edit&id=' . htmlspecialchars($question['id']) . '">Güncelle</a> |
            <a href="admin.php?action=delete&id=' . htmlspecialchars($question['id']) . '" onclick="return confirm(\'Bu soruyu silmek istediğinizden emin misiniz?\');">Sil</a>
          </td>';
    echo '</tr>';
}
echo '</table>';


if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM Questions WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($question) {
        $options = json_decode($question['options'], true);
        echo '<h1>Soru Güncelle</h1>';
        echo '<form method="POST" action="admin.php">';
        echo '<input type="hidden" name="id" value="' . htmlspecialchars($question['id']) . '">';
        echo 'Soru: <input type="text" name="question" value="' . htmlspecialchars($question['question']) . '" required><br>';
        echo 'Şık 1: <input type="text" name="options[]" value="' . htmlspecialchars($options[0]) . '" required><br>';
        echo 'Şık 2: <input type="text" name="options[]" value="' . htmlspecialchars($options[1]) . '" required><br>';
        echo 'Şık 3: <input type="text" name="options[]" value="' . htmlspecialchars($options[2]) . '" required><br>';
        echo 'Şık 4: <input type="text" name="options[]" value="' . htmlspecialchars($options[3]) . '" required><br>';
        echo 'Doğru Cevap (0-3): <input type="number" name="correct_answer" min="0" max="3" value="' . htmlspecialchars($question['correct_answer']) . '" required><br>';
        echo '<button type="submit" name="update">Güncelle</button>';
        echo '<button onclick="window.location.href=\'admin.php\'">Geri Dön</button>';
        echo '</form>';
    } else {
        echo 'Soru bulunamadı.';
    }
}

echo '</div>';
?>
