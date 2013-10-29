<?php

session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Книги от автор';
include './includes/header.php';
require './includes/connection.php';
if (!$connection) {
    echo 'no database';
    exit;
}
mysqli_set_charset($connection, 'utf8');
print(isset($_SESSION['isLogged']) ? '<a href="destroy.php">Изход</a><br>' : '<a href="login.php">Вход</a><br>');

echo '<a href="index.php">Книги</a><br>';

if (isset($_GET ["authorid"]))
    $authorid = $_GET ["authorid"];
else {
    header('Location:  .');
    exit();
}

$q = mysqli_query($connection, "SELECT * FROM books_authors AS b1, books_authors AS b2
LEFT JOIN authors ON authors.author_id = b2.author_id
LEFT JOIN books ON b2.book_id = books.book_id
WHERE b1.author_id = $authorid 
AND b2.book_id = b1.book_id");
//10x to Ilia Penev!!!
$result = array();
if ($q) {
    if (mysqli_num_rows($q) > 0) {
        while ($row = mysqli_fetch_assoc($q)) {
            $result[$row['book_id']]['book'] = $row['book_title'];
            $result[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
        }
        echo '<table><tr><td>Книга</td><td>Автори</td></tr>';
        //foreach ($result as $value) {
        //  echo '<tr><td>' . $value['book'] . '</td><td>';
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
        echo 'Няма въведени книги от този автор!';
    }
} else {
    echo 'Несъществуващ автор!';
}
include './includes/footer.php';
?>