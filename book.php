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

// If the link was intended to create a new book, redirect to add form
if ($id === 'new') {
    header('Location: add.php');
    exit();
}

// If book not found, show a friendly message
if (!$book) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Book Not Found</title>
        <style>body{font-family:Arial, sans-serif;background:#f4f4f4;margin:0;padding:40px} .card{max-width:600px;margin:0 auto;background:#fff;padding:20px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.1);text-align:center} a{display:inline-block;margin-top:12px;padding:8px 14px;background:#6c757d;color:#fff;text-decoration:none;border-radius:4px}</style>
    </head>
    <body>
        <div class="card">
            <h1>Book not found</h1>
            <p>The book you're looking for does not exist.</p>
            <a href="index.php">Back to Books</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
    
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 20px 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .cover {
            display: block;
            max-width: 200px;
            margin: 0 auto 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        .details p {
            margin: 8px 0;
            border: 1px solid #000000;
            padding: 10px;
            border-radius: 10px;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .actions a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 3px;
        }
        .actions a.delete {
            background-color: #c62828;
        }
        .actions a.back {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php if ($book['cover_path']): ?>
        <img class="cover" src="<?= htmlspecialchars($book['cover_path']); ?>" alt="<?= htmlspecialchars($book['title']); ?>">
    <?php endif; ?>
    <h2><?= htmlspecialchars($book['title']); ?></h2>
    <div class="details">
        <p><strong>Author(s):</strong> <?= htmlspecialchars($book['authors']) ?: 'Unknown'; ?></p>
        <p><strong>Release Date:</strong> <?= htmlspecialchars($book['release_date']); ?></p>
        <p><strong>Price:</strong> €<?= number_format($book['price'], 2); ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($book['summary']) ?: 'No description available'; ?></p>
    </div>
    <div class="actions">
        <a href="edit.php?id=<?= $book['id']; ?>">Edit</a>
        <a class="delete" href="delete.php?id=<?= $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
        <a class="back" href="index.php">Back</a>
    </div>
    </div>
</body>
</html>
