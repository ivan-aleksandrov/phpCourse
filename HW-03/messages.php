<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Съобщения';
include './includes/header.php';

if (isset($_SESSION['isLogged']) == true) {
    ?>
    <a href="send.php">Напиши съобщение</a><br>
    <form method="POST">
        <div>
            Кои съобщения да са най-отгоре:
            <select name="data">

                <?php
                $arrayNames = array("новите", "старите");
                foreach ($arrayNames as $key => $value) {
                    if ($_POST['data'] == "$value") {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    echo '<option value="' . $value . '"' . $selected . '>' . $value . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Филтрирай"/>
        </div>
    </form>
    <?php
    $connection = mysqli_connect('localhost', 'ivan', 'password', 'homework_msg');
    if (!$connection) {
        echo 'no database';
        exit;
    }
    mysqli_set_charset($connection, 'utf8');
    if ($_POST) {
        $data = $_POST['data'];
    } else {
        $data = 'новите';
    }
    if ($data == 'новите') {
        $q = mysqli_query($connection, 'SELECT msg_time,username,msg_title,msg_body
        FROM msg ORDER BY msg_time DESC');
    } elseif ($data == 'старите') {
        $q = mysqli_query($connection, 'SELECT msg_time,username,msg_title,msg_body
        FROM msg ORDER BY msg_time ASC');
    }
    if (!$q) {
        echo 'error';
    }
    if ($q->num_rows > 0) {
        echo '<table><tr><td>Дата</td><td>Потребител</td><td>Заглавие</td><td>Съдържание</td></tr>';
        while ($row = $q->fetch_assoc()) {
            echo '<tr><td>' . $row['msg_time'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['msg_title'] . '</td>';
            echo '<td>' . $row['msg_body'] . '</td></tr>';
        }
        echo '</tr>';
    } else {
        echo 'Няма съобщения!';
    }
    ?>
    <a href="destroy.php">Изход!</a><br>
    <?php
} else {
    header('Location: index.php');
    exit;
}
include './includes/footer.php';
?>
