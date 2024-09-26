<?php

// Configurações do banco de dados
$host = '127.0.0.1';  // Localhost
$db   = 'ats_db';     // Nome do banco de dados
$user = 'root';       // Usuário do banco
$pass = '';           // Senha do banco (se existir)
$charset = 'utf8mb4';

// DSN para conexão PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções de configuração PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Conexão com o banco de dados foi bem-sucedida!";
} catch (PDOException $e) {
    throw new PDOException("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
