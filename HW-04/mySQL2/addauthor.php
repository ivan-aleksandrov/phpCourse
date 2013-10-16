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
if (isset($_POST ['submit'])) {
    $error = false;
    $authorName = htmlspecialchars($_POST ['authorName']);
    $authorName = mysqli_real_escape_string($connection, $authorName);
    $authorNameLength = mb_strlen($authorName);
    if ($authorNameLength < 3) {
        echo 'Името на автора трябва да е над три символа!';
        $error = true;
    }
    $q = mysqli_query($connection, "SELECT * FROM authors WHERE author_name='$authorName'");
    if ($q) {
        if (mysqli_num_rows($q) > 0) {
            echo 'Авторът вече присъства!<br>';
            $error = true;
        }
    }
    if (!$error) {
        $q = mysqli_query($connection, "INSERT INTO authors (author_name) VALUES ('$authorName')");
        if ($q) {
            echo 'Авторът е добавен!<br>';
        } else {
            echo 'Грешка при добавянето на автора!<br>';
        }
    }
}
?>
<a href="index.php">Книги</a>
<form method="POST">
    <input type="text" value="<?php if (isset($_POST ['submit']) && $_POST ['authorName'] != '') echo $_POST ['authorName']; ?>"
           name="authorName" maxlength="250" /> <input type="submit" name="submit"
           value="Добави автор" />
</form>
<?php
$q = mysqli_query($connection, "SELECT * FROM authors ORDER by author_name ASC");
echo '<table><tr><td>Автори</td></tr>';
$authors = array();
while ($row = mysqli_fetch_assoc($q)) {
    $authors[] = $row;
}
foreach ($authors as $value) {
    echo '<tr><td>';
    $authorId = $value['author_id'];
    echo "<a href='booksfromauthor.php?authorid=$authorId'>" . $value['author_name'] . '</a>';
    echo '</td></tr>';
}
echo '</table>';
include './includes/footer.php';
?>