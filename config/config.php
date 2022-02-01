<?php
define('DB_HOST', 'localhost:8080');
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];
try {
    $pdo = new PDO("mysql:dbhost=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    $e->getMessage();
}
