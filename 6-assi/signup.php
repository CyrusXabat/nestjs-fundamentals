<?php
session_start();
require 'includes/header.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $userQuery = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'customer')");
    $userQuery->execute([$name, $email, $password]);

    $userId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $userId;
    header('Location: profile.php');
    exit;
}
?>

<main>
    <section class="signup">
        <h2>Signup</h2>
        <form action="signup.php" method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" required