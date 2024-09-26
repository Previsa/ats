<?php
namespace App\Relatorios\Models;

use PDO;

class FiltroRelatorioModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Método para criar um filtro de relatório
    public function createFiltro($nome, $configuracao) {
        $sql = "INSERT INTO filtros_relatorios (nome, configuracao) VALUES (:nome, :configuracao)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':configuracao', $configuracao);
        return $stmt->execute();
    }

    // Método para buscar todos os filtros
    public function getFiltros() {
        $sql = "SELECT * FROM filtros_relatorios";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para excluir um filtro por ID
    public function deleteFiltroById($id) {
        $sql = "DELETE FROM filtros_relatorios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
