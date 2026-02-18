<?php
require_once('./connection.php');

if (!isset($_POST['id'])) {
    die('No ID provided.');
}

$id = $_POST['id'];

// Handle file upload
$cover_path = $_POST['cover_path'] ?? null; // keep existing path by default

if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = './uploads/';
    
    // Make sure the uploads folder exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('cover_') . '.' . $ext;
    $destination = $upload_dir . $new_filename;

    if (move_uploaded_file($_FILES['cover']['tmp_name'], $destination)) {
        $cover_path = $destination;
    } else {
        die('Failed to upload cover image.');
    }
}

// Update book
$stmt = $pdo->prepare('UPDATE books SET title = :title, release_date = :release_date,
    price = :price, pages = :pages, language = :language, cover_path = :cover_path WHERE id = :id');

$stmt->execute([
    'title'        => $_POST['title'],
    'release_date' => $_POST['release_date'],
    'price'        => $_POST['price'],
    'pages'        => $_POST['pages'],
    'language'     => $_POST['language'],
    'cover_path'   => $cover_path,
    'id'           => $id
]);

// Update author
if (!empty($_POST['name'])) {
    $name_parts = explode(' ', trim($_POST['name']), 2);
    $first = $name_parts[0];
    $last  = $name_parts[1] ?? ''; // avoid undefined index if no last name

    $stmt = $pdo->prepare('UPDATE authors a 
        JOIN book_authors ba ON a.id = ba.author_id 
        SET a.first_name = :first, a.last_name = :last 
        WHERE ba.book_id = :book_id');

    $stmt->execute([
        'first'   => $first,
        'last'    => $last,
        'book_id' => $id
    ]);
}

header('Location: book.php?id=' . $id);
exit();