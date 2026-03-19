<?php

require_once '/connection.php';

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT a.*, 
                     GROUP_CONCAT(DISTINCT b.title SEPARATOR ", ") as books
                     FROM authors a
                     LEFT JOIN book_authors ba ON a.id = ba.author_id
                     LEFT JOIN books b ON ba.book_id = b.id
                     WHERE a.id = :id
                     GROUP BY a.id');
$stmt->execute(['id' => $id]);
$author = $stmt->fetch();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
    </style>
</head>
<body>
    
</body>
</html>