<?php

require_once('./connection.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT b.*, a.first_name , a.last_name FROM books b
    LEFT JOIN book_authors ba ON b.id = ba.book_id
    LEFT JOIN authors a ON ba.author_id = a.id
    WHERE b.id = :id;');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="update.php" method="post">

    <input type="hidden" name="id" value="<?= $book['id']; ?>">
    
    <input type="text" name="title" value="<?= $book['title']; ?>">

    <input type="number" name="release_date" value="<?= $book['release_date']; ?>">

    <input type="file" name="cover" value="<?= $book['cover_path']; ?>">
</form>

</body>
</html>