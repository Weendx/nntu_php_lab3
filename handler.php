<?php

function dbConnect(): PDO {
    try {
        // подключаемся к серверу
        $conn = new PDO("mysql:host=localhost;port=3306;dbname=php_Martianov", "root", "");
        // установка режима вывода ошибок
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "DB Connection failed: " . $e->getMessage();
        exit();
    }
}

function fetchUser(PDO $conn, string $username): bool { 
    $stmt = $conn->prepare("SELECT * FROM lab3_users WHERE login = ?");
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result === false) {
        $_SESSION["error"] = sprintf("Аккаунт &lt;%s&gt; не найден.", $username);
        return false;
    }
    // 3600*24*7 = 7 days
    setcookie("uLogin", $result["login"], time() + 3600*24*7, "/");
    setcookie("uColor", $result["color"], time() + 3600*24*7, "/");
    return true;
}

function handleForm(PDO $conn) {
    if (isset($_POST["action"])) {
        switch ($_POST["action"]) { 
            case "register":
                if ( !(isset($_POST["login"]) && isset($_POST["color"])) || (
                            empty($_POST["login"]) || empty($_POST["color"]) ) ) {
                    $_SESSION["error"] = "Все поля должны быть заполнены!";
                    return;
                }
                $checkStmt = $conn->prepare("SELECT COUNT(*) FROM lab3_users WHERE login = ?");
                $checkStmt->bindParam(1, $_POST["login"], PDO::PARAM_STR);
                $checkStmt->execute();
                if ($checkStmt->fetch()[0] != 0) {
                    $_SESSION["error"] = sprintf("Аккаунт &lt;%s&gt; уже существует!", $_POST['login']);
                    return;
                }

                $stmt = $conn->prepare("INSERT IGNORE INTO lab3_users VALUES(NULL, :login, :color)");
                $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);
                $stmt->bindParam(":color", $_POST["color"], PDO::PARAM_STR);
                // exit(var_dump($stmt));
                if ($stmt->execute()) {
                    $_SESSION["success"] = "Регистрация прошла успешно, осталось войти в аккаунт!";
                } else {
                    $_SESSION["error"] = "Что-то пошло не так";
                }
                break;
            case "login":
                if ( !isset($_POST["login"]) || empty($_POST["login"]) ) {
                    $_SESSION["error"] = "Введите логин!";
                    return;
                }
                fetchUser($conn, $_POST["login"]);
                break;
            case "logout":
                session_destroy();
                setcookie("uLogin", null, 0, "/");
                setcookie("uColor", null, 0, "/");
                break;
            case "changeSettings":
                if ( !isset($_POST["color"]) || empty($_POST["color"]) ) {
                    $_SESSION["error"] = "Выберите цвет!";
                    return;
                }
                if ( !isset($_COOKIE["uLogin"]) ) {
                    $_SESSION["error"] = "Доступ не разрешен";
                    return;
                } 
                if ( $_POST["color"] == $_COOKIE["uColor"] ) {
                    return;
                }
                $stmt = $conn->prepare("UPDATE `lab3_users` SET `color` = :color WHERE `login` = :login");
                $stmt->bindParam(":color", $_POST["color"], PDO::PARAM_STR);
                $stmt->bindParam(":login", $_COOKIE["uLogin"], PDO::PARAM_STR);
                $stmt->execute();
                
                if ( $stmt->rowCount() > 0) {
                    $_SESSION["success"] = "Настройки сохранены, обновите страницу";
                } else {
                    $_SESSION["error"] = "Что-то пошло не так";
                }
                break;
        }
    }
}

function onLoad() {
    $conn = dbConnect();
    if ($_COOKIE["uLogin"]) {
        fetchUser($conn, $_COOKIE["uLogin"]);
    }
    if (isset($_POST['action'])) {
        handleForm($conn);
        header("Location: index.php");
    }
}

onLoad();

?>