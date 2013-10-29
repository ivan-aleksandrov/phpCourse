<?php
session_start();
mb_internal_encoding('UTF-8');
$pageTitle = 'Книга';
include './includes/header.php';
require './includes/connection.php';
if (!$connection) {
    echo 'no database';
    exit;
}
mysqli_set_charset($connection, 'utf8');
print(isset($_SESSION['isLogged']) ? '<a href="destroy.php">Изход</a><br>' : '<a href="login.php">Вход</a><br>');

echo '<a href="index.php">Книги</a><br>';

if (isset($_GET ["bookid"]))
    $bookid = $_GET ["bookid"];
else {
    header('Location:  .');
    exit();
}

//$bookid = $_GET['bookid'];
$bookid = htmlspecialchars($bookid);
$bookid = mysqli_real_escape_string($connection, $bookid);
$q = mysqli_query($connection, "SELECT books.book_id, books.book_title, authors.author_id, authors.author_name
FROM authors
LEFT JOIN books_authors ON authors.author_id = books_authors.author_id
LEFT JOIN books ON books_authors.book_id = books.book_id
WHERE books.book_id =$bookid");

if ($q) {
    if (mysqli_num_rows($q) > 0) {
        while ($row = mysqli_fetch_assoc($q)) {
            $result[$row['book_id']]['book'] = $row['book_title'];
            $result[$row['book_id']]['authors'][$row['author_id']] = $row['author_name'];
        }
        echo '<table><tr><td>Книга</td><td>Автори</td></tr>';
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
        echo '</table>';
        if (isset($_SESSION['username'])) {
            ?>
            <form method="POST">
                <div><textarea name="comment" rows="5" cols="50" maxlenght="250"
                               title="Въведете коментар за книгата" required></textarea></div>
                <div><input type="submit" value="Коментирай"/></div>
            </form>
            <?php
            if (!empty($_POST)) {
                $username = $_SESSION['username'];
                $comment = htmlspecialchars($_POST['comment']);
                $comment = mysqli_real_escape_string($connection, $comment);
                $commentLength = mb_strlen($comment);
                $date = date('Y-m-d H:i:s');
                if ($commentLength < 10) {
                    echo 'Коментарът трябва да съдържа поне 10 символа!<br>';
                    exit;
                } else {
                    $q = mysqli_query($connection, "INSERT INTO comments (username, book_id, comment_body, date)
                        VALUES ('$username', '$bookid', '$comment', '$date')");
                    mysqli_query($connection, $q);
                    header("Location: book.php?bookid=$bookid");
                    exit;
                }
            }
        }
        echo '<br>Коментари:<br>';
        $qry = mysqli_query($connection, "SELECT * FROM comments WHERE book_id=$bookid");
        if ($qry) {
            if (mysqli_num_rows($qry) > 0) {
                echo '<table><tr><td>Потребител</td><td>Дата</td><td>Коментар</td></tr>';
                while ($row = $qry->fetch_assoc()) {
                    echo '<tr><td>' . $row['username'] . '</td><td>' . $row['date'] . '</td><td>' . $row['comment_body'] . '</td></tr>';
                }
                echo '</table>';
            } else {
                echo 'Няма коментари!';
            }
        }
    } else {
        echo 'Несъществуваща книга!';
    }
} else {
    echo 'Несъществуваща книга!';
}