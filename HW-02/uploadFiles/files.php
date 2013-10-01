<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Файлове';
include './includes/header.php';

if (isset($_SESSION['isLogged']) == true) {
    ?>
    <a href="upload.php">Качи файл</a><br>
    <?php
    echo 'Вашите файлове:<br>';

    $dir = opendir('./uploads/');
    echo '<ul>';
    while ($read = readdir($dir)) {

        if ($read != '.' && $read != '..' && $read != '.htaccess') {
            echo '<li><a href="' . "uploads" . DIRECTORY_SEPARATOR . $read . '">' . $read . '</a></li>';
        }
    }

    echo '</ul>';

    closedir($dir);
    ?>
    <a href="destroy.php">Изход!</a>
    <?php
} else {
    header('Location: index.php');
}
include './includes/footer.php';
?>
