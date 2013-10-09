<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Изпращане на съобщение';
include './includes/header.php';

if (isset($_SESSION['isLogged']) == true) {
    $connection = mysqli_connect('localhost', 'ivan', 'password', 'homework_msg');
    if (!$connection) {
        echo 'no database';
        exit;
    }
    mysqli_set_charset($connection, 'utf8');
    ?>
    <a href="messages.php">Всички съобщения</a><br>
    Максимална дължина на заглавието: 50 символа, максимална дължина на съобщението: 250 символа!

    <form method="POST">
        <div>Заглавие: <input type="text" name="messageTitle" maxlength="50"/></div>
        <div> <textarea name="messageBody" maxlength="250"></textarea></div>
        <div><input type="submit" value="Добави"/></div>
    </form>
    <?php
    if (!empty($_POST)) {
        $user = $_SESSION['username'];
        $msgTitle = trim($_POST['messageTitle']);
        $msgTitle = mysqli_real_escape_string($connection, $msgTitle);
        $msgText = trim($_POST['messageBody']);
        $msgText = mysqli_real_escape_string($connection, $msgText);
        $date = date('Y-m-d H:i:s');
        if (!$msgTitle || !$msgText) {
            echo 'Не може да има празни полета!';
            exit;
        } else {
            $sql = "INSERT INTO msg (msg_time, msg_title, msg_body, username)
            VALUES ('$date', '$msgTitle', '$msgText', '$user')";
            mysqli_query($connection, $sql);
            header('Location: messages.php');
            exit;
        }
    }
} else {
    header('Location: index.php');
    exit;
}
?>
<a href="destroy.php">Изход</a><br>
<?php
include './includes/footer.php';
?>