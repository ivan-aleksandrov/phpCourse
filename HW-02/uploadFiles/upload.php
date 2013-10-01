<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Качване на файл';
include './includes/header.php';

if (isset($_SESSION['isLogged']) == true) {
    ?>
    <a href="files.php">Всички файлове</a><br>
    Позволени файлове: картинки във формат .gif, .jpeg, .jpg и .png, с големина не повече от 5 MB!

    <form method="POST" enctype="multipart/form-data">
        <div>Файл: <input type="file" name="uploadedFile"/></div>
        <div><input type="submit" value="Добави"/></div>
    </form>
    <?php
    if (!empty($_FILES)) {
        if (count($_FILES) > 0) {
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            $temp = explode(".", $_FILES["uploadedFile"]["name"]);
            $extension = end($temp);
            if (($_FILES['uploadedFile']['type'] == 'image/gif')
               || ($_FILES['uploadedFile']['type'] == 'image/jpeg')
               || ($_FILES['uploadedFile']['type'] =='image/jpg')
               || ($_FILES['uploadedFile']['type'] =='image/png')
               || ($_FILES['uploadedFile']['type'] =='image/x-png')
               || ($_FILES['uploadedFile']['type'] =='image/pjpeg')
               && in_array($extension, $allowedExts)
               && ($_FILES['uploadedFile']['size'] <= 5242880)){
                if (move_uploaded_file($_FILES['uploadedFile']['tmp_name'], 'uploads' . DIRECTORY_SEPARATOR . $_FILES['uploadedFile']['name'])) {
                    echo 'Файлът е качен успешно!<br>';
                } else {
                    echo 'Грешка!<br>';
                }
            }
            else{
                echo 'Непозволен файл!<br>';
            }
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