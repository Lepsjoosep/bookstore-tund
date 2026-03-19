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

if ($id === 'new') {
    header('Location: add.php');
    exit();
}

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
        div {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;

        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            margin-top: 150px;
        }
        .info {
            display: flex;
            flex-direction: column;
            margin-top: 0px;
            gap: 20px;
        }
        .cover {
            max-width: 500px;
            max-height: 500px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin: 0px;
        }
        h3 {
            margin: 0px;
        }
        p {
            margin: 0px;
        }
        
        .details {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 40px;
        }
        h1
        {
            text-align: center;
            padding: 0px;
            margin: 0px;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
            display: flex;
            flex-direction: row;
        }
        .actions a {
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
        <img class="cover" src="<?= htmlspecialchars($book['cover_path']); ?>" alt="">
    <?php endif; ?>
<div class="info">
    <div class="details">
        <h1><?= htmlspecialchars($book['title']);?></h1>
        <h2><?= htmlspecialchars($book['authors']) ?: 'Unknown'; ?></h2>
        <h3>€<?= number_format($book['price'], 2); ?></h3>
        <p><?= htmlspecialchars($book['summary']) ?: 'No description available'; ?></p>
    </div>
    <div class="actions">
        <a href="edit.php?id=<?= $book['id']; ?>">Edit</a>
        <a class="delete" href="delete.php?id=<?= $book['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
        <a class="back" href="index.php">Back</a>
    </div>
</div>
    </div>
</body>
</html>
