<?php
require 'functions/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
} else {
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];

        $stmt = $pdo->prepare("SELECT * FROM Questions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($question) {
            $options = json_decode($question['options'], true);
            echo '<link rel="stylesheet" href="styles.css">';
            echo '<div class="container">';
            echo '<h1>Soru Güncelle</h1>';
            echo '<form method="POST" action="update.php">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($question['id']) . '">';
            echo 'Soru: <input type="text" name="question" value="' . htmlspecialchars($question['question']) . '" required><br>';
            echo 'Şık 1: <input type="text" name="options[]" value="' . htmlspecialchars($options[0]) . '" required><br>';
            echo 'Şık 2: <input type="text" name="options[]" value="' . htmlspecialchars($options[1]) . '" required><br>';
            echo 'Şık 3: <input type="text" name="options[]" value="' . htmlspecialchars($options[2]) . '" required><br>';
            echo 'Şık 4: <input type="text" name="options[]" value="' . htmlspecialchars($options[3]) . '" required><br>';
            echo 'Doğru Cevap (0-3): <input type="number" name="correct_answer" min="0" max="3" value="' . htmlspecialchars($question['correct_answer']) . '" required><br>';
            echo '<button type="submit">Güncelle</button>';
            echo '<button onclick="window.location.href=\'admin.php\'">Geri Dön</button>';
            echo '</form>';
            echo '</div>';
        } else {
            echo 'Soru bulunamadı.';
        }
    } else {
        echo 'Soru ID belirtilmedi.';
    }
}
?>
