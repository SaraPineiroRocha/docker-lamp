<?php
$dotenv = parse_ini_file(__DIR__ . '/.env');
$host = $dotenv['DB_HOST'];
$db   = $dotenv['DB_NAME'];
$user = $dotenv['DB_USER'];
$pass = $dotenv['DB_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    Flight::set('db', $pdo);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
