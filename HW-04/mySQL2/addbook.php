<?php
mb_internal_encoding('UTF-8');
$pageTitle = 'Добавяне на автор';
include './includes/header.php';
require './includes/connection.php';
if (!$connection) {
    echo 'no database';
    exit;
}
mysqli_set_charset($connection, 'utf8');
$q=  mysqli_query($connection, "SELECT * FROM authors ");
?>
<a href="index.php">Книги</a>
<form method="POST">
    <input type="text" value="<?php if (isset ( $_POST ['submit'] ) && $_POST ['title'] != '') echo $_POST ['title'];?>"
		name="title" maxlength="250"/>
    <br>Автори:<br><select name="authors[]" multiple="multiple">
        <?php while($row= mysqli_fetch_assoc($q)){
            $authorId=$row['author_id'];
            echo "<option value='$authorId'>";
            echo $row['author_name'];
            echo '</option>';
        }
        ?>
    </select><input type="submit" name="submit" value="Добави книга"/>
</form>
<?php

if(isset($_POST['submit'])){
    $error=false;
    $title=  htmlspecialchars($_POST['title']);
    $title= mysqli_real_escape_string($connection, $title);
    $titleLength=  mb_strlen($title);
    if(!isset($_POST['authors'])){
        echo 'Трябва да изберете поне един автор!<br>';
        $error=true; 
    }else{
        $authors=$_POST['authors'];
    }
    if($titleLength<3){
        echo "Името на книгата трябва да е по-дълго от три символа!<br>";
        $error=true;
    }
    $q = mysqli_query($connection, "SELECT * FROM books WHERE book_title='$title'");
    if ($q) {
        if (mysqli_num_rows($q) > 0) {
            echo 'Книгата вече присъства!<br>';
            $error = true;
        }
    }
    if(!$error){
        $q=  mysqli_query($connection, "INSERT INTO books (book_title) VALUES ('$title')");
        if(!$q){
            echo 'Проблем с добавянето на книгата!<br>';
            exit();
        }
        $lastInsertedId=  mysqli_insert_id($connection);
        foreach($authors as $value){
            $value2[]="($lastInsertedId, $value)";
        }
        $query="INSERT INTO books_authors VALUES".implode(', ', $value2);
        $q=  mysqli_query($connection, $query);
        if($q){
            echo "Книгата $title е добавена!<br>";
        }
        else{
            echo 'Има проблем с добавянето на книгата!<br>';
        }
    }
}
include './includes/footer.php';
?>