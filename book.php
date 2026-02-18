<?php

require_once('./connection.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT b.*, 
                     GROUP_CONCAT(CONCAT(a.first_name, " ", a.last_name) SEPARATOR ", ") as authors
                     FROM books b
                     LEFT JOIN book_authors ba ON b.id = ba.book_id
                     LEFT JOIN authors a ON ba.author_id = a.id
                     WHERE b.id = :id
                     GROUP BY b.id;');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $book['title']; ?></title>
</head>
<body>
    <?php if ($book['cover_path']): ?>
        <img src="<?= htmlspecialchars($book['cover_path']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" style="width: 200px; height: auto; margin-bottom: 20px;">
    <?php endif; ?>
    <h2><?= htmlspecialchars($book['title']); ?></h2>
    <p><strong>Author(s):</strong> <?= htmlspecialchars($book['authors']) ?: 'Unknown'; ?></p>
    <p><strong>Release Date:</strong> <?= htmlspecialchars($book['release_date']); ?></p>
    <p><strong>Price:</strong> €<?= number_format($book['price'], 2); ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($book['summary']) ?: 'No description available'; ?></p>
    <a href="edit.php?id=<?= $book['id']; ?>">Edit</a>
    <a href="delete.php?id=<?= $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
    <br><br>
    <a href="index.php">Back to Books</a>
</body>
</html>
