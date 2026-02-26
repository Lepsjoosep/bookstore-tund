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
    <title>Edit Book</title>
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
            border-radius: 4px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .form-group textarea {
            resize: vertical;
            height: 120px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Book</h1>
    <form action="update.php" method="post" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $book['id']; ?>">
    <input type="hidden" name="cover_path" value="<?= $book['cover_path']; ?>">
    
    <div class="form-group">
        <label>Title:</label>
        <input type="text" name="title" value="<?= $book['title']; ?>" required>
    </div>

    <div class="form-group">
        <label>Release Date:</label>
        <input type="number" name="release_date" value="<?= $book['release_date']; ?>">
    </div>

    <div class="form-group">
        <label>Price:</label>
        <input type="number" name="price" step="0.01" value="<?= $book['price'] ?? ''; ?>">
    </div>

    <div class="form-group">
        <label>Pages:</label>
        <input type="number" name="pages" value="<?= $book['pages'] ?? ''; ?>">
    </div>

    <div class="form-group">
        <label>Language:</label>
        <input type="text" name="language" value="<?= $book['language'] ?? ''; ?>">
    </div>
    <div class="form-group">
        <label>Summary:</label>
        <textarea name="summary"><?= htmlspecialchars($book['summary'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
        <label>Cover Image:</label>
        <input type="file" name="cover" accept="image/*">
    </div>

    <div class="form-group">
        <label>Author Name:</label>
        <input type="text" name="name" value="<?= ($book['first_name'] ?? '') . ' ' . ($book['last_name'] ?? ''); ?>">
    </div>

    <button type="submit">Save</button>
</form>
    </div>

</body>
</html>