<?php

namespace App\TriagemCurriculos\Models;

class CurriculoModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    // Salvar informações do currículo no banco de dados (Create)
    public function saveCurriculo($nomeArquivo, $idCandidato) {
        $stmt = $this->db->prepare('
            INSERT INTO curriculos (id_candidato, nome_arquivo, data_upload)
            VALUES (?, ?, NOW())
        ');
        $stmt->execute([$idCandidato, $nomeArquivo]);
    }

    // Atualizar informações do currículo no banco de dados (Update)
    public function updateCurriculo($nomeArquivo, $idCandidato) {
        $stmt = $this->db->prepare('
            UPDATE curriculos 
            SET nome_arquivo = ?, data_upload = NOW()
            WHERE id_candidato = ?
        ');
        $stmt->execute([$nomeArquivo, $idCandidato]);
    }

    // Buscar currículo no banco de dados pelo ID do currículo (Read)
    public function getCurriculoById($id) {
        $stmt = $this->db->prepare('SELECT * FROM curriculos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Excluir currículo do banco de dados pelo ID (Delete)
    public function deleteCurriculo($id) {
        $stmt = $this->db->prepare('DELETE FROM curriculos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
