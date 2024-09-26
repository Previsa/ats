<?php

namespace App\Armazenamento\Models;

use PDO;

class CandidateModel {
    private $pdo;

    // Certifique-se de que a conexão PDO está sendo passada corretamente pelo construtor
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Busca todos os candidatos no banco de dados
    public function getAllCandidates() {
        $stmt = $this->pdo->prepare('SELECT * FROM candidatos');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os candidatos como um array associativo
    }

    // Busca candidatos com base em palavras-chave e localização
    public function searchCandidates($keywords, $location) {
        $sql = 'SELECT * FROM candidatos WHERE nome LIKE :keywords AND localidade = :location';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':keywords', '%' . $keywords . '%');
        $stmt->bindValue(':location', $location);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os candidatos que correspondem aos critérios
    }

    // Método para criar um novo candidato
    public function createCandidate($data) {
        $sql = "INSERT INTO candidatos (nome, localidade, experiencia) VALUES (:nome, :localidade, :experiencia)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $data['nome']);
        $stmt->bindValue(':localidade', $data['localidade']);
        $stmt->bindValue(':experiencia', $data['experiencia']);
    
        return $stmt->execute(); // Retorna true se a inserção foi bem-sucedida, false em caso de erro
    }

    // Método para buscar um candidato pelo ID
    public function getCandidateById($id) {
        $sql = "SELECT * FROM candidatos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
            
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null; // Retorna o candidato ou null se não encontrado
    }

    // Método para atualizar um candidato pelo ID
    public function updateCandidate($id, $data) {
        $sql = "UPDATE candidatos SET nome = :nome, localidade = :localidade, experiencia = :experiencia WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $data['nome']);
        $stmt->bindValue(':localidade', $data['localidade']);
        $stmt->bindValue(':experiencia', $data['experiencia']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute(); // Retorna true se a atualização foi bem-sucedida, false em caso de erro
    }

    // Método para excluir um candidato pelo ID
    public function deleteCandidate($id) {
        $sql = "DELETE FROM candidatos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute(); // Retorna true se a exclusão foi bem-sucedida, false em caso de erro
    }
}
