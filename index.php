<?php

require_once('./connection.php');

$search = $_GET['search'] ?? '';
$query = 'SELECT DISTINCT b.title, b.id, b.release_date, b.cover_path FROM books b
          LEFT JOIN book_authors ba ON b.id = ba.book_id
          LEFT JOIN authors a ON ba.author_id = a.id';

if ($search) {
    $query .= " WHERE b.title LIKE :search OR a.first_name LIKE :search OR a.last_name LIKE :search";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    $stmt = $pdo->query($query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <style>
        li { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; }
        .book-actions { margin-top: 10px; }
        .book-actions a { margin-right: 10px; padding: 5px 10px; text-decoration: none; background-color: #f0f0f0; border-radius: 3px; }
        .delete-link { background-color: #ffebee !important; color: #c62828; }
    </style>
</head>
<body>
    <h1>Bookstore</h1>
    <form method="get" action="index.php">
        <input type="text" name="search" placeholder="Search by title or author..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <?php if ($search): ?>
            <a href="index.php">Clear Search</a>
        <?php endif; ?>
    </form>
    
    <ul style="list-style-type: none; padding: 0;">

<?php while ($book = $stmt->fetch()) { ?>
        <li>
            <?php if ($book['cover_path']): ?>
                <img src="<?= htmlspecialchars($book['cover_path']); ?>" alt="<?= htmlspecialchars($book['title']); ?>" style="width: 100px; height: auto; margin-right: 10px; vertical-align: top;">
            <?php endif; ?>
            <div style="display: inline-block; vertical-align: top;">
                <a href="book.php?id=<?= $book['id'] ?>">
                    <strong><?= htmlspecialchars($book['title']); ?></strong>
                </a>
                <br><small>Released: <?= htmlspecialchars($book['release_date']); ?></small>
                <div class="book-actions">
                    <a href="edit.php?id=<?= $book['id']; ?>">Edit</a>
                    <a href="delete.php?id=<?= $book['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </div>
            </div>
        </li>
<?php } ?>
    </ul>
</body>
</html>