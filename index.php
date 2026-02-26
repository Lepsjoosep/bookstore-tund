<?php

require_once('./connection.php');

$search = $_GET['search'] ?? '';
$query = 'SELECT b.title,
                 b.id,
                 b.release_date,
                 b.cover_path,
                 GROUP_CONCAT(CONCAT(a.first_name, \' \', a.last_name)
                              SEPARATOR \', \') AS authors
          FROM books b
          LEFT JOIN book_authors ba ON b.id = ba.book_id
          LEFT JOIN authors a ON ba.author_id = a.id';

if ($search) {
    $query .= " WHERE b.title LIKE ? OR a.first_name LIKE ? OR a.last_name LIKE ?";
}

$query .= ' GROUP BY b.id';

$stmt = $pdo->prepare($query);
if ($search) {
    $search_term = '%' . $search . '%';
    $stmt->execute([$search_term, $search_term, $search_term]);
} else {
    $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 20px 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form.search {
            text-align: center;
            margin-bottom: 30px;
        }
        form.search input[type="text"] {
            width: 60%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-right: 5px;
        }
        form.search button {
            padding: 8px 16px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
        }
        form.search a.clear {
            margin-left: 10px;
            color: #007bff;
            text-decoration: none;
        }
        ul.book-list {
            list-style-type: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        ul.book-list li {
            background: #fafafa;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        ul.book-list img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .book-actions {
            margin-top: 10px;
        }
        .book-actions a {
            margin: 0 5px;
            padding: 5px 10px;
            text-decoration: none;
            background-color: #f0f0f0;
            border-radius: 3px;
        }
        .delete-link {
            background-color: #ffebee !important;
            color: #c62828;
        }
        .logo {
            text-decoration: none;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="logo"><h1>Bookstore</h1></a>
        <form class="search" method="get" action="index.php">
            <input type="text" name="search" placeholder="Search by title or author..." value="<?= htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
            <?php if ($search): ?>
                <a class="clear" href="index.php">Clear Search</a>
            <?php endif; ?>
        </form>
        <div style="text-align:center; margin-top: 20px;">
            <a href="add.php" style="display:inline-block; padding:8px 16px; background:#28a745; color:#fff; border-radius:3px; text-decoration:none;">Add New Book</a>
        </div>
        
        <ul class="book-list">

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
                <br><small>Author(s): <?= htmlspecialchars($book['authors'] ?? 'Unknown'); ?></small>
                <div class="book-actions">
                    <a href="edit.php?id=<?= $book['id']; ?>">Edit</a>
                    <a href="delete.php?id=<?= $book['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </div>
            </div>
        </li>
<?php } ?>
        </ul>
    </div>
</body>
</html>