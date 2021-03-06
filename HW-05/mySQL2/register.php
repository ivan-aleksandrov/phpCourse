<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Регистрация';
include './includes/header.php';
if (isset($_SESSION['isLogged']) == true) {
    header('Location: index.php');
    exit;
} else {
    require './includes/connection.php';
    if (!$connection) {
        echo 'no database';
        exit;
    }
    mysqli_set_charset($connection, 'utf8');
    ?>
    Минимален брой символи за потребителско име: 5 символа. Минимален брой символи за парола: 5 символа.
    <form method="POST">
        <div>Потребителско име:<input type="text" name="username" maxlength="50"/></div>
        <div>Паролa: <input type="password" name="password" maxlength="50"/></div>
        <div>Повтори парола: <input type="password" name="confirmPassword" maxlenght="50"/></div>
        <div><input type="submit" value="Регистрирай се!"/></div>
    </form>
    <?php
    if (!empty($_POST)) {
        $username = trim($_POST['username']);
        $username = htmlspecialchars($username);
        $username = mysqli_real_escape_string($connection, $username);
        $password = trim($_POST['password']);
        $password = htmlspecialchars($password);
        $password = mysqli_real_escape_string($connection, $password);
        $passwordConfirm = trim($_POST['confirmPassword']);
        $passwordConfirm = htmlspecialchars($passwordConfirm);
        $passwordConfirm = mysqli_real_escape_string($connection, $passwordConfirm);

        if (!$username || !$password || !$passwordConfirm) {
            echo 'Не може да има празни полета!';
            exit;
        } else {
            if ($password != $passwordConfirm) {
                echo 'Грешно потвърждение на парола!';
            } else {
                if (strlen($username) < 5 || strlen($password) < 5) {
                    echo 'Не отговаряте на изискването за минимална дължина от 5 символа!';
                } else {
                    $sql = "INSERT INTO users (username, password)
                    VALUES ('$username', '$password')";
                    mysqli_query($connection, $sql);
                    header('Location: index.php');
                    exit;
                }
            }
        }
    }
}
include './includes/footer.php';
?>
