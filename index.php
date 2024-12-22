<?php
$error_message = ''; // Инициализируем переменную для ошибки
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Подключение к БД
    $servername = "localhost"; 
    $username = "admin"; 
    $password = "admin"; 
    $dbname = "is_police"; 

    // Создание подключения
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка подключения
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Получение значений из формы
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Запрос к базе данных с использованием хеширования паролей
   // Запрос к базе данных для извлечения роли
    $stmt = $conn->prepare("SELECT role FROM user WHERE login=? AND password=?");
    $stmt->bind_param("ss", $login, $password); // Передаем и логин, и пароль
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($role);
        $stmt->fetch();

        // Перенаправление в зависимости от роли
        switch ($role) {
            case 'admin':
                header("Location: /admin.php");
                exit();
            case 'user':
                header("Location: /user.php");
                exit();
            default:
                $error_message = "<p>Ошибка роли</p>";
        }
    } else {
        $error_message = "<p>Неверные логин или пароль</p>";
    }


    // Закрытие соединения
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="MVD_logo_color.png" type="image/png">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Roboto", sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('MVD_logo_color.png') no-repeat;
            background-size: 1000px;
            background-position: center;
        }

        .wrapper {
            width: 420px;
            background: rgba(0, 0, 0, 0.5); /* Затемнённый фон */
            border: 2px solid #d2ab67; /* Цвет обводки */
            backdrop-filter: blur(10px);
            color: white;
            border-radius: 20px;
            align-items: center;
            padding: 30px 30px 40px 40px;
        }


        .wrapper .logo_GTU {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .wrapper h1 {
            font-size: 40px;
            text-align: center;
        }

        .input-box {
            width: 100%;
            height: 50px;
            margin: 30px 0;
            position: relative;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border: 2px solid #d2ab67; /* Цвет обводки */
            border-radius: 15px;
            font-size: 16px;
            color: white;
            padding: 20px 45px 20px 20px;
        }

        .input-box input::placeholder {
            color: white;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .toggle-password {
            position: absolute;
            right: 60px; /* Позиция кнопки для отображения в правой части поля */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .wrapper .btn {
            width: 100%;
            height: 45px;
            background: rgba(0, 128, 0);
            border: none;
            outline: none;
            border-radius: 15px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 5px;
        }

        .wrapper .close-btn {
            width: 100%;
            height: 45px;
            background: rgba(191, 0, 0, 0.5);
            border: none;
            outline: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-top: 15px;
        }

        .remember {
            font-size: 14.5px;
            text-align: center;
            margin-top: 20px;
        }

        .remember a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .remember a:hover {
            text-decoration: underline;
        }

        .design-by {
            font-size: 14.5px;
            font-weight: 600;
            text-align: center;
            margin-top: 10px;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            background-color: black;
            padding: 10px;
            border-radius: 15px;
            border: 1px solid red;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border-radius: 15px;
            align-items: center;
            padding: 30px 30px 40px 40px;
            margin: auto;
            width: 40%;
            text-align: center;
        }

        .modal-content button {
            width: 45%;
            height: 45px;
            border: none;
            outline: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            color: white;
        }

        .modal-content .confirm {
            background: rgba(0, 128, 0);
            margin-right: 5%;
        }

        .modal-content .cancel {
            background: rgba(191, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="wrapper"> 
        <div class="logo_GTU">
            <a href="https://belovokyzgty.ru/">
                <img src="MVD_logo.png" alt="Логотип" width="100">
            </a>
        </div>
        
        <form action="" method="POST"> 
            <h1>Авторизация</h1>
            <div class="input-box">
                <input type="text" name="login" placeholder="логин" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="пароль" required>
                <i class='bx bx-show toggle-password' id="togglePassword"></i> <!-- Иконка для показа/скрытия пароля -->
            </div>
            
            <!-- Здесь выводим сообщение об ошибке, если оно есть -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="remember">
            </div>
            <button type="submit" class="btn">Вход</button>
            <button type="button" class="close-btn" onclick="openModal()">Закрыть</button>
            
            <div class="design-by">
                <p>Designed by Kvasov in Belovo</p>
            </div>
        </form>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <p>Вы действительно хотите закрыть окно?</p>
            <button class="confirm" onclick="closePage()">Да</button>
            <button class="cancel" onclick="closeModal()">Нет</button>
        </div>
    </div>

    <script>
        function closePage() {
            window.close();
        }

        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        // Функция для показа и скрытия пароля
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('bx-show');
            this.classList.toggle('bx-hide');
        });
    </script>
</body>
</html>
