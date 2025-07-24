<?php
// Включим подробные ошибки для отладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    // Получение данных
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

    try {
        // Подключение к БД 
        $conn = new mysqli('localhost', 'root', '', 'feedback_db');
        
        // Проверка подключения
        if ($conn->connect_error) {
            throw new Exception("Ошибка подключения: " . $conn->connect_error);
        }
        
        // Проверка существования таблицы
        $tableCheck = $conn->query("SHOW TABLES LIKE 'feedback'");
        if ($tableCheck->num_rows === 0) {
            // Создаем таблицу
            $createTable = "CREATE TABLE feedback (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                message TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if (!$conn->query($createTable)) {
                throw new Exception("Ошибка создания таблицы: " . $conn->error);
            }
        }
        
        // Подготавливаем запрос
        $sql = "INSERT INTO feedback (email, message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        
        
        if ($stmt === false) {
            throw new Exception("Ошибка подготовки запроса: " . $conn->error);
        }
        
        // Привязываем параметры
        $bindResult = $stmt->bind_param("ss", $email, $message);
        if ($bindResult === false) {
            throw new Exception("Ошибка привязки параметров: " . $stmt->error);
        }
        
        // Выполняем запрос
        if (!$stmt->execute()) {
            throw new Exception("Ошибка выполнения: " . $stmt->error);
        }
        
        // Закрываем соединения
        $stmt->close();
        $conn->close();
        
        // Перенаправление
        header('Location: thank_you.php');
        exit;
        
    } catch (Exception $e) {
        // Детальный вывод ошибки
        die("Произошла ошибка: " . $e->getMessage() . 
            "<br>Проверьте:<br>
            1. Существует ли база данных 'feedback_db'<br>
            2. Существует ли таблица 'feedback'<br>
            3. Правильность SQL-запроса");
    }
}
?>