<?php
$servername = "localhost"; 
$username = "admin"; 
$password = "admin"; 
$dbname = "is_police";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $column = $_POST['column'];
    $new_value = $_POST['new_value'];

    // Проверка на допустимые значения столбца
    $allowed_columns = ['name', 'surname', 'patronymic', 'rank', 'job_title', 'salary', 'photo'];

    if (in_array($column, $allowed_columns)) {
        $sql = "UPDATE employee SET $column = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_value, $id);
        
        if ($stmt->execute()) {
            $success_message = "Запись успешно обновлена.";
        } else {
            $error_message = "Ошибка при обновлении записи: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        $error_message = "Недопустимое имя столбца.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="MVD_logo_color.png" type="image/png">
    <title>Изменение данных сотрудника</title>
    <style>
        body {
            background-color: #222;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        header {
            margin-bottom: 20px;
        }

        h1 {
            color: #ffcf67;
        }

        form {
            background-color: #333;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="number"],
        input[type="text"],
        select {
            width: calc(100% - 20px);
            padding: 10px;
            border-radius: 15px;
            border: 1px solid #d2ab67;
            background-color: #444;
            color: white;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #ffcf67;
            color: #222;
            padding: 10px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color:rgb(210, 171, 103);
        }

        .back-button {
            background-color: rgba(191, 0, 0, 0.9);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            opacity: 0.8;
        }

        .message {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Изменение данных сотрудника</h1>
    </header>

    <form method="post">
        <label for="id">ID сотрудника:</label>
        <input type="number" id="id" name="id" required>

        <label for="column">Выберите столбец:</label>
        <select id="column" name="column" required>
            <option value="name">Имя</option>
            <option value="surname">Фамилия</option>
            <option value="patronymic">Отчество</option>
            <option value="rank">Звание</option>
            <option value="job_title">Должность</option>
            <option value="salary">Зарплата</option>
            <option value="photo">Фото (URL)</option>
        </select>

        <label for="new_value">Новое значение:</label>
        <input type="text" id="new_value" name="new_value" required>

        <input type="submit" value="Изменить данные">
        <button class="back-button" type="button" onclick="window.history.back();">Назад</button>
    </form>

    <?php if (isset($success_message)): ?>
        <div class="message" style="color: #ffcf67;">
            <?php echo $success_message; ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="message" style="color: red;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
</body>
</html>
