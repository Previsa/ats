<?php
namespace App\Matching\Models;

class VagaModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Método para buscar vaga pelo ID
    public function getVagaById($id_vaga) {
        $stmt = $this->db->prepare('SELECT * FROM vagas WHERE id = ?');
        $stmt->execute([$id_vaga]);
        return $stmt->fetch();
    }
}
