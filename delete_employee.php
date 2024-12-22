<?php
$servername = "localhost"; 
$username = "admin"; 
$password = "admin"; 
$dbname = "is_police";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение ID сотрудника из URL
$id = intval($_GET['id']);

// SQL-запрос на удаление записи
$sql = "DELETE FROM employee WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Сотрудник удален успешно.";
} else {
    echo "Ошибка удаления сотрудника или сотрудник не найден.";
}

$stmt->close();
$conn->close();

// Перенаправление обратно на страницу со списком сотрудников или на главную
header("Location: user.php");
exit();
?>
