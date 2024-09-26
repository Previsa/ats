<?php
namespace App\Relatorios\Models;

use PDO;

class RelatorioModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Método para criar um relatório
    public function createRelatorio($nome, $caminhoArquivo) {
        $sql = "INSERT INTO relatorios (nome, caminho_arquivo) VALUES (:nome, :caminho_arquivo)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':caminho_arquivo', $caminhoArquivo);
        return $stmt->execute();
    }

    // Método para buscar um relatório por ID
    public function getRelatorioById($id) {
        $sql = "SELECT * FROM relatorios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para excluir um relatório por ID
    public function deleteRelatorioById($id) {
        $sql = "DELETE FROM relatorios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
