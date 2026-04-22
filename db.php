<?php
// db.php
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']) || php_sapi_name() === 'cli-server';

if ($isLocal) {
    // Configurações Banco de Dados LOCAL
    $host = 'localhost';
    $db   = 'atlas_travel'; // O nome do seu banco local
    $user = 'root';
    $pass = 'm@P599152'; // Substitua por 'root' se usar MAMP
} else {
    // Configurações Banco de Dados PRODUÇÃO (Hostinger)
    $host = 'mysql-182367286.hostinger.com';
    $db   = 'u182367286_atlasTravel';
    $user = 'u182367286_atlasTravel';
    $pass = 'm@P599152';
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
