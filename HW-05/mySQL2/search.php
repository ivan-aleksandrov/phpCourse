<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Добавяне на автор';
include './includes/header.php';
require './includes/connection.php';
if (!$connection) {
    echo 'no database';
    exit;
}
mysqli_set_charset($connection, 'utf8');
print(isset($_SESSION['isLogged']) ? '<a href="destroy.php">Изход</a><br>' : '<a href="login.php">Вход</a><br>');
?>
<a href="index.php">Книги</a><br>
<form method="GET">
    <input type="text"
           value="<?php if (isset($_POST ['submit']) && $_POST ['keyword'] != '') echo $_POST ['keyword']; ?>"
           name="keyword" /> <input type="submit" 
           value="Търси книга" />
</form>
<?php
if (isset($_GET ["keyword"])) {
    $bookTitle = $_GET ["keyword"];

    $bookTitle = htmlspecialchars($bookTitle);
    $bookTitle = mysqli_real_escape_string($connection, $bookTitle);
    $q = mysqli_query($connection, "SELECT * FROM books_authors
LEFT JOIN authors ON authors.author_id = books_authors.author_id
LEFT JOIN books ON books_authors.book_id = books.book_id
WHERE books.book_title LIKE '%$bookTitle%'");

    if ($q) {
        if (mysqli_num_rows($q) > 0) {
            $result = array();
            while ($row = mysqli_fetch_assoc($q)) {
                $result[$row['book_id']]['book'] = $row['book_title'];
                $result[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
            }
            echo '<table><tr><td>Книга</td><td>Автори</td></tr>';
            //foreach ($result as $value) {
            //  echo '<tr><td>';
            //echo $value['book'];
            //echo '</td><td>';
            foreach ($result as $key => $value) {
                //echo '<pre>'.print_r($value['book'], true).'</pre>';
                //  echo '<pre>'.print_r($key, true).'</pre>';
                echo '<tr><td>';
                $books = array();
                $books[] = "<a href='book.php?bookid=$key'>" . $value['book'] . '</a>';
                echo implode(', ', $books);
                echo '</td><td>';
                $authors = array();
                foreach ($value['authors'] as $key => $value2)
                    $authors[] = "<a href='booksfromauthor.php?authorid=$key'>" . $value2 . '</a>';
                echo implode(', ', $authors);
                echo '</td></tr>';
            }
            echo '</table>';
        } else {
            echo 'Не е намерено съвпадение!<br>';
        }
    }
}
include './includes/footer.php';
?>