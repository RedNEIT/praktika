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

// SQL-запрос для получения данных о сотруднике, включая зарплату
$sql = "SELECT id, name, surname, patronymic, rank, job_title, photo, salary FROM employee WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Нет данных о сотруднике.";
    exit;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['name']) . ' ' . htmlspecialchars($row['surname']); ?> - Отдел кадров</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="MVD_logo_color.png" type="image/png">
    <style>
        body {
            background: url('MVD_logo_color.png') no-repeat; background-position: center center; background-size: 1000px;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .content {
            width: 100%;
            max-width: 800px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            border: 2px solid #d2ab67;
        }

        img {
            width: 200px;
            height: auto;
            border-radius: 15px;
            margin: 20px 0;
            border: 2px solid #d2ab67;
        }

        .button {
            background-color: #d2ab67;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 15px;
            margin-top: 20px;
            margin-right: 10px;
        }

        .button-change {
            background-color: rgba(190, 190, 190, 0.9);
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 15px;
            margin-top: 50px;
            margin-right: 10px; /* Для отступа между кнопками */
        }

        .button-del {
            background-color: rgba(191, 0, 0, 0.9);
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            border-radius: 15px;
            margin-top: 20px;
            margin-right: 10px; /* Для отступа между кнопками */  
        }

        .button:hover  {
            opacity: 0.5;
        }

        .button-change:hover {
            opacity: 0.5;
        }

        .button-del:hover {
            opacity: 0.5;
        }

        .employee {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            border-radius: 15px;
            overflow: hidden;
            border: 2px solid #d2ab67;
        }

        .employee th,
        .employee td {
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #d2ab67;
            color: #222;
            background-color: #ffcf67;
        }

        .employee th {
            background-color: #d2ab67;
            color: white;
        }

        .employee tr:hover { 
            background-color: #444; 
        }

        .employee tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>
<body>

<div class="content">
    <h1><?php echo htmlspecialchars($row['name']) . ' ' . htmlspecialchars($row['surname']); ?></h1>
    <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
    <table class="employee">
        <thead>
            <tr>
                <th>Характеристика</th>
                <th>Значение</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Звание</td>
                <td><?php echo htmlspecialchars($row['rank']); ?></td>
            </tr>
            <tr>
                <td>Должность</td>
                <td><?php echo htmlspecialchars($row['job_title']); ?></td>
            </tr>
            <tr>
                <td>Зарплата</td>
                <td><?php echo htmlspecialchars($row['salary']); ?> руб.</td>
            </tr>
        </tbody>
    </table>

    <button class="button-change" onclick="window.location.href='change-employee.php?id=<?php echo $row['id']; ?>'">Изменить данные</button>
    <button class="button-del" onclick="confirmDeletion(<?php echo $row['id']; ?>)">Удалить</button>
    
    <button class="button" onclick="window.history.back();">Назад</button>
</div>

<script>
    function confirmDeletion(id) {
        if (confirm("Вы уверены, что хотите удалить сотрудника?")) {
            window.location.href = 'delete_employee.php?id=' + id; // Перенаправление на скрипт удаления
        }
    }
</script>

</body>
</html>
