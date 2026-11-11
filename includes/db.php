<?php
$host   = 'localhost';
$dbname = 'fit_club';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Дерекқорға қосылу қатесі. MySQL қосулы ма және fit_club дерекқоры бар ма?');
}
