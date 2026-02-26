<?php

require_once('./connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Basic validation
	if (empty($_POST['title'])) {
		die('Title is required.');
	}

	// Handle file upload
	$cover_path = null;
	if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
		$upload_dir = './uploads/';
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

	// Insert book
	$stmt = $pdo->prepare('INSERT INTO books (title, release_date, price, pages, language, summary, cover_path) VALUES (:title, :release_date, :price, :pages, :language, :summary, :cover_path)');
	$stmt->execute([
		'title' => $_POST['title'],
		'release_date' => $_POST['release_date'] ?: null,
		'price' => $_POST['price'] ?: null,
		'pages' => $_POST['pages'] ?: null,
		'language' => $_POST['language'] ?: null,
		'summary' => $_POST['summary'] ?? '',
		'cover_path' => $cover_path
	]);

	$book_id = $pdo->lastInsertId();

	// Handle author
	if (!empty($_POST['name'])) {
		$name_parts = explode(' ', trim($_POST['name']), 2);
		$first = $name_parts[0];
		$last = $name_parts[1] ?? '';

		// Try to find existing author
		$stmt = $pdo->prepare('SELECT id FROM authors WHERE first_name = :first AND last_name = :last LIMIT 1');
		$stmt->execute(['first' => $first, 'last' => $last]);
		$author = $stmt->fetch();

		if ($author) {
			$author_id = $author['id'];
		} else {
			$stmt = $pdo->prepare('INSERT INTO authors (first_name, last_name) VALUES (:first, :last)');
			$stmt->execute(['first' => $first, 'last' => $last]);
			$author_id = $pdo->lastInsertId();
		}

		// Link book and author
		$stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :author_id)');
		$stmt->execute(['book_id' => $book_id, 'author_id' => $author_id]);
	}

	header('Location: book.php?id=' . $book_id);
	exit();
}

// Show form
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Book</title>
	<style>
		body { font-family: Arial, sans-serif; background:#f4f4f4; }
		.container { max-width:700px; margin:40px auto; background:#fff; padding:20px 30px; box-shadow:0 2px 8px rgba(0,0,0,0.1); border-radius:4px; }
		h1 { text-align:center; margin-bottom:20px; }
		.form-group { margin-bottom:12px; }
		label { display:block; font-weight:bold; margin-bottom:6px; }
		input[type="text"], input[type="number"], textarea { width:100%; padding:8px; border:1px solid #ccc; border-radius:3px; }
		textarea { height:120px; resize:vertical; }
		button { display:block; width:100%; padding:10px; background:#007bff; color:#fff; border:none; border-radius:3px; font-size:16px; cursor:pointer; }
		button:hover { background:#0069d9; }
	</style>
</head>
<body>
	<div class="container">
		<h1>Add New Book</h1>
		<form action="add.php" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Title</label>
				<input type="text" name="title" required>
			</div>
			<div class="form-group">
				<label>Author (First Last)</label>
				<input type="text" name="name">
			</div>
			<div class="form-group">
				<label>Release Date</label>
				<input type="number" name="release_date">
			</div>
			<div class="form-group">
				<label>Price</label>
				<input type="number" step="0.01" name="price">
			</div>
			<div class="form-group">
				<label>Pages</label>
				<input type="number" name="pages">
			</div>
			<div class="form-group">
				<label>Language</label>
				<input type="text" name="language">
			</div>
			<div class="form-group">
				<label>Summary</label>
				<textarea name="summary"></textarea>
			</div>
			<div class="form-group">
				<label>Cover Image</label>
				<input type="file" name="cover" accept="image/*">
			</div>
			<button type="submit">Add Book</button>
		</form>
	</div>
</body>
</html>