<?php

// Certifique-se de que o arquivo de configuração do banco de dados está no diretório correto.
require 'config/database.php';  // Inclui o arquivo de conexão ao banco de dados

try {
    // Teste de consulta simples no banco de dados
    $stmt = $pdo->query("SELECT 1");

    if ($stmt !== false) {
        echo "Conexão com o banco de dados estabelecida com sucesso!";
    } else {
        echo "Falha ao estabelecer conexão com o banco de dados.";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
