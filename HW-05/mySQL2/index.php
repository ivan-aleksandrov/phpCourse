<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Всички книги';
include './includes/header.php';

require './includes/connection.php';
if (!$connection) {
    echo 'no database';
    exit;
}
mysqli_set_charset($connection, 'utf8');

$q = mysqli_query($connection, 'SELECT * 
FROM books_authors 
LEFT JOIN authors ON authors.author_id = books_authors.author_id
LEFT JOIN books ON books_authors.book_id = books.book_id
ORDER BY books.book_title ASC');

$result = array();
while ($row = mysqli_fetch_assoc($q)) {
    $result[$row['book_id']]['book'] = $row['book_title'];
    $result[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
}
print(isset($_SESSION['isLogged']) ? '<a href="destroy.php">Изход</a><br>' : '<a href="login.php">Вход</a><br>');
?>
<a href="addbook.php">Добави книга</a>
<a href="addauthor.php">Добави автор</a>
<a href="search.php">Търсене на книга</a>
<table>
    <tr>
        <td>Книги</td>
        <td>Автори</td>
    </tr>
    <?php
    foreach ($result as $key => $value) {
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
    ?>
</table>
<?php
include './includes/footer.php';
?>