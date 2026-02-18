<?php
require_once('./connection.php');

if (!isset($_GET['id'])) {
    die('No ID provided.');
}

$id = $_GET['id'];

// Delete book_authors entries first (foreign key constraint)
$stmt = $pdo->prepare('DELETE FROM book_authors WHERE book_id = :id');
$stmt->execute(['id' => $id]);

// Delete the book
$stmt = $pdo->prepare('DELETE FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);

// Redirect back to index
header('Location: index.php');
exit();
?>
