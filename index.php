<?php

require_once('./connection.php');

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
        .logo {
            text-decoration: none;
            color: #000000;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="logo"><h1>Bookstore</h1></a>
        <div style="text-align:center; margin-top: 20px;">
            <div style="margin-top: 10px;display:flex; justify-content: center; gap: 10px;">
            <a href="add.php" style="padding:8px 16px; background:#28a745; color:#fff; border-radius:3px; text-decoration:none; width:20%;">Add New Book</a>
            <a href="addauthor.php" style="padding:8px 16px; background:#28a745; color:#fff; border-radius:3px; text-decoration:none; width:20%;">Add New Author</a>
            </div>
            <div style="margin-top: 10px;display:flex; justify-content: center; gap: 10px;">
                <a href="authorview.php" style="padding:8px 16px; background:blue; color:#fff; border-radius:3px; text-decoration:none; width:20%;">View Authors</a>
                <a href="bookview.php" style="padding:8px 16px; background:blue; color:#fff; border-radius:3px; text-decoration:none; width:20%;">View Books</a>
            </div>
        </div>
</body>
</html>