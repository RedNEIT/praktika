<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отдел кадров</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="MVD_logo_color.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap');
        
        body {
            background: url('MVD_logo_color.png') no-repeat; background-position: center center; background-size: 1000px;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #222;
            padding: 10px 20px;
        }

        header h1 {
            color: #ffcf67; margin: 0;
        }

        .button {
            background-color: rgba(200, 0, 0, 0.9);
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 15px;
        }

        .button:hover {
            opacity: 0.5;
        }

        .content {
            margin: 20px;
            text-align: center;
            flex-grow: 1;
        }

        .tile {
            display: inline-block;
            width: 200px;
            height: 210px;
            margin: 10px;
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #d2ab67;
            padding: 5px;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            color: #ffcf67;
        }

        .tile img {
            width: 100%;
            height: auto;
            max-height: 140px;
            object-fit: contain;
            border-radius: 15px;
        }

        .tile h3 {
            margin: 5px 0 0;
        }

        .footer {
            background-color: #222;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            position: relative;
            bottom: 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Отдел кадров</h1>
    <div>
        <button class="button" onclick="closePage()">Закрыть</button>
        <button class="button login" onclick="window.location.href='index.php'">Выйти</button>
    </div>
</header>

<div class="content">
    <?php
    $servername = "localhost"; 
    $username = "admin"; 
    $password = "admin"; 
    $dbname = "is_police"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $sql = "SELECT id, name, surname, patronymic, rank, job_title, photo FROM employee";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="tile" onclick="openDetails(' . $row["id"] . ')">';
            if ($row["photo"]) {
                echo '<img src="' . htmlspecialchars($row["photo"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
            }
            echo '<h3>' . htmlspecialchars($row["name"]) . ' ' . htmlspecialchars($row["surname"]) . '</h3>';
            echo '<p>' . htmlspecialchars($row["job_title"]) . '</p>';
            echo '</div>';
        }
    } else {
        echo "Нет доступных сотрудников.";
    }

    $conn->close();
    ?>
</div>

<script>
    function openDetails(id) {
        window.location.href = 'employee_details.php?id=' + id;
    }

    function closePage() {
        if (confirm("Вы уверены, что хотите закрыть это окно?")) {
            window.close();
        }
    }
</script>

</body>
</html>
