<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Спасибо!</title>
    <style>.feedback-item { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }</style>
</head>
<body>
    <h1>Спасибо за ваше сообщение!</h1>
    
    <div class="feedback-history">
        <h2>История сообщений:</h2>
        <?php
        try {
            // Подключение 
            $conn = new mysqli('localhost', 'root', '', 'feedback_db');
            
            if ($conn->connect_error) {
                throw new Exception("Ошибка подключения: " . $conn->connect_error);
            }
            
            $result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="feedback-item">';
                    echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
                    echo '<p><strong>Дата:</strong> ' . $row['created_at'] . '</p>';
                    echo '<p><strong>Сообщение:</strong><br>' . nl2br(htmlspecialchars($row['message'])) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>История сообщений пуста</p>';
            }
            
            $conn->close();
            
        } catch (Exception $e) {
            echo '<p>Ошибка при загрузке истории: ' . $e->getMessage() . '</p>';
        }
        ?>
    </div>
</body>
</html>