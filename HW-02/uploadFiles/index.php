<?php
session_set_cookie_params(3600, '/', $_SERVER['HTTP_HOST'], false, true);
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Вход';
include './includes/header.php';

if (isset($_SESSION['isLogged']) == true) {
    header('Location: files.php');
    exit;
} else {
    if (!empty($_POST)) {
        $username = trim($_POST['username']);
        $pass = trim($_POST['pass']);
        if ($username == 'user' && $pass == 'qwerty') {
            $_SESSION['isLogged'] = true;
            header('Location: files.php');
            exit;
        } else {
            echo 'Грешни данни!';
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
?>                                                                                             