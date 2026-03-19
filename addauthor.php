<?php

require_once('./connection.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
		return('First name and last name are required.');
	}


	$stmt = $pdo->prepare('INSERT INTO authors (first_name, last_name) VALUES (:first_name, :last_name)');
	$stmt->execute([
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name']
	]);

    // optionally get the new id if needed
    $author_id = $pdo->lastInsertId();

    // redirect to avoid resubmission and show a simple confirmation
    header('Location: index.php?added=1');
    exit;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add author</title>
    <style>
        body {
        }

        .formdiv {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;           
        }
        .first-name {
            display: flex;
            flex-direction: column;
            gap: 5px;
            justify-content: center;
        }
        .last-name {
            display: flex;
            flex-direction: column;
            gap: 5px;
            justify-content: center;
        }
        h1 {
            font-family: Arial, sans-serif;
        }
        label {
            font-family: Arial, sans-serif;
            font-size: 20px;
        }
        input {
            width: 100%;
            height: 30px;
            font-size: 20px;
        }
        button {
            display:flex;
            width:100%;
            padding:10px;
            background:#007bff;
            color:#fff;
            border:none;
            border-radius:3px; 
            font-size:16px;
            cursor:pointer;
            margin-top: 20px;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="formdiv">
        <h1>Add author</h1>
        <form action="addauthor.php" method="post" enctype="multipart/form-data">
            <div class="first-name">
                <label>First name</label>
                <input type="text" name="first_name">
            </div>
            <div class="last-name">
                <label>Last name</label>
                <input type="text" name="last_name">
            </div>
            <button type="submit">Add Author</button>
        </form>
    </div>
</body>
</html>