<?php
namespace App\Matching\Models;

class MatchingHistoryModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Método para salvar um match entre um candidato e uma vaga (Create)
    public function saveMatch($id_candidato, $id_vaga) {
        $stmt = $this->db->prepare('
            INSERT INTO match_history (id_candidato, id_vaga, data_match) VALUES (?, ?, NOW())
        ');
        $stmt->execute([$id_candidato, $id_vaga]);
    }

    // Método para buscar o histórico de matches de um candidato específico (Read)
    public function getMatchingHistory($id_candidato) {
        $stmt = $this->db->prepare('SELECT * FROM match_history WHERE id_candidato = ?');
        $stmt->execute([$id_candidato]);
        return $stmt->fetchAll();  // Retorna o histórico de matches do candidato
    }

    // Método para buscar todo o histórico de matches (opcional - pode ser útil)
    public function getAllMatchHistory() {
        $stmt = $this->db->query('SELECT * FROM match_history');
        return $stmt->fetchAll();  // Retorna todos os matches no banco de dados
    }

    // Método para deletar o histórico de matches de um candidato específico (Delete)
    public function deleteMatchingHistory($id_candidato) {
        $stmt = $this->db->prepare('DELETE FROM match_history WHERE id_candidato = ?');
        $stmt->execute([$id_candidato]);
    }
}
