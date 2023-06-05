<?php
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <header>
        <?php if(isset($user) && $user->isAuthorized()): ?>
            <a href="/logout" class="btn btn-danger">LogOut</a>
        <?php else: ?>
            <a href="/" class="btn btn-primary">Home</a>
            <a href="/registration" class="btn btn-primary">Registration</a>
            <a href="/login" class="btn btn-primary">LogIn</a>
        <?php endif; ?>
    </header>

    <body class="container-fluid">
        <?php if (isset($message['type'], $message['text']) && $message): ?>
            <div class="alert alert-<?= $message['type'] ?>" role="alert">
                <?= $message['text'] ?>
            </div>
        <?php endif; ?>

        <main class="d-flex justify-content-center align-items-center flex-column">
