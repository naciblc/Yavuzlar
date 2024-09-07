<?php

try {
    $db = new PDO('sqlite:functions/data.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $db->query("SELECT id, question, options, correct_answer FROM Questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <h1>Quiz</h1>
    <form action="submit_quiz.php" method="post">
        <?php foreach ($questions as $index => $question): ?>
            <div>
                <h3><?php echo ($index + 1) . ". " . $question['question']; ?></h3>
                <?php
                $options = json_decode($question['options'], true);
                foreach ($options as $i => $option): ?>
                    <div>
                        <input type="radio" name="question_<?php echo $question['id']; ?>" value="<?php echo $i; ?>">
                        <label><?php echo $option; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit">Quiz'i Bitir</button>
        
    </form>
    <button onclick="anasayfa()">Ana Sayfa</button>
    </div>
    <script src="script.js"></script>
</body>
</html>
