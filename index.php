<?php
    require "vendor/autoload.php";
    session_start();

    $colorNames = [
        "red" => "Красный",
        "green" => "Зеленый",
        "blue" => "Голубой",
        "white" => "Белый"
    ];
    $colorSchemes = [
        "red" => "background: #e69090; --super: #a63c23",
        "green" => "background: #7dd06c; --super: #109362",
        "blue" => "background: #4fb4b9; --super: #0b36b9",
        "white" => "background: #f7f7f7; --super: #bf8413"
    ];
    require "handler.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body style="<?= $colorSchemes[$_COOKIE["uColor"]] ?>">
    <div class="wrapper">
        <div class="error block" style="<?= $_SESSION["error"] ? '' : 'display: none' ?>">
            <p>Ошибка: <?= $_SESSION["error"] ?></p>
        </div>
        <div class="success block" style="<?= $_SESSION["success"] ? '' : 'display: none' ?>">
            <p><?= $_SESSION["success"] ?></p>
        </div>
        <div class="info block" style="<?= $_COOKIE["uLogin"] ? '' : 'display: none' ?>">
            <p>Осуществлен вход в систему:</p>
            <p>> Логин: <span class="super"><?= $_COOKIE["uLogin"] ?></span></p>
            <p>> Предпочтительный цвет: <span class="super"><?= $colorNames[$_COOKIE["uColor"]] ?></span></p>
            <form action="" method="post">
                <input type="hidden" name="action" value="logout">
                <input type="submit" value="Выйти">
            </form>
        </div>
        <!-- register -->
        <div class="block" style="<?= $_COOKIE["uLogin"] ? 'display: none' : '' ?>">
            <h3>Регистрация</h3>
            <form action="" method="post">
                <label>Ваш логин:
                    <input type="text" name="login" id="" placeholder="Введите логин">
                </label>
                <br>
                <label>Выберите предпочтительный фоновый цвет для страницы:</label>
                <br>
                
                <input type="radio" name="color" value="red" id="reg-c-red">
                <label for="reg-c-red">Красный</label><br>
                <input type="radio" name="color" value="green" id="reg-c-green">
                <label for="reg-c-green">Зеленый</label><br>
                <input type="radio" name="color" value="blue" id="reg-c-blue">
                <label for="reg-c-blue">Голубой</label><br>
                <input type="radio" name="color" value="white" id="reg-c-white">
                <label for="reg-c-white">Белый</label><br>
                
                <input type="hidden" name="action" value="register">
                <input type="submit" value="Зарегистрироваться">
            </form>
        </div>
        
        <!-- login -->
        <div class="block" style="<?= $_COOKIE["uLogin"] ? 'display: none' : '' ?>">
            <h3>Авторизация</h3>
            <form action="" method="post">
                <label>Ваш логин:
                    <input type="text" name="login" id="" placeholder="Введите логин">
                </label>
                <br>
                <input type="hidden" name="action" value="login">
                <input type="submit" value="Войти в учетную запись">
            </form>
        </div>
       
        <!-- settings -->
        <div class="block" style="<?= $_COOKIE["uLogin"] ? '' : 'display: none' ?>">
            <h3>Изменение настроек</h3>
            <form action="" method="post">
                <label>Выберите предпочтительный фоновый цвет для страницы:</label>
                <br>
                    
                <input type="radio" name="color" value="red" id="chng-c-red">
                <label for="chng-c-red">Красный</label><br>
                <input type="radio" name="color" value="green" id="chng-c-green">
                <label for="chng-c-green">Зеленый</label><br>
                <input type="radio" name="color" value="blue" id="chng-c-blue">
                <label for="chng-c-blue">Голубой</label><br>
                <input type="radio" name="color" value="white" id="chng-c-white">
                <label for="chng-c-white">Белый</label><br>

                <input type="hidden" name="action" value="changeSettings">
                <input type="submit" value="Изменить выбор">
            </form>
        </div>
    </div>
</body>
</html>

<?php
if (!isset($_POST['action'])) {
    $_SESSION["error"] = "";
    $_SESSION["success"] = "";
}
?>