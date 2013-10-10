<?php
session_set_cookie_params(3600, '/', $_SERVER['HTTP_HOST'], false, true);
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Вход';
include './includes/header.php';

require './includes/connection.php';
if (!$connection) {
    echo 'no database';
    exit;
}
mysqli_set_charset($connection, 'utf8');



if (isset($_SESSION['isLogged']) == true) {
    header('Location: messages.php');
    exit;
} else {
    ?>
    <a href="register.php">Регистрирай се</a><br>
    <?php
    if (!empty($_POST)) {
        $username = trim($_POST['username']);
        $username = htmlspecialchars($username);
        $username = mysqli_real_escape_string($connection, $username);
        $pass = trim($_POST['pass']);
        $pass = htmlspecialchars($pass);
        $pass = mysqli_real_escape_string($connection, $pass);
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$pass'";
        $result = mysqli_query($connection, $sql);
        $count = mysqli_num_rows($result);
        //echo $count;
        //exit;
        if ($count == 1) {
            $_SESSION['isLogged'] = true;
            $_SESSION['username'] = $username;
            header('Location: messages.php');
            exit;
        } else {
            echo 'Грешна комбинация от потребителско име и парола!';
        }
    }
    ?>
    <form method="POST">
        <div>Име: <input type="text" name="username"/></div>
        <div>Парола: <input type="password" name="pass"/></div>
        <div><input type="submit" value="Вход"/></div>
    </form>

    <?php
}
include './includes/footer.php';
?>                                                                                             