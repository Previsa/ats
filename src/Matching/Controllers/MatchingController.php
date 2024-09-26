<?php
namespace App\Matching\Controllers;

use App\Matching\Models\VagaModel;
use App\Armazenamento\Models\CandidateModel;
use App\Matching\Models\MatchingHistoryModel;

class MatchingController {
    private $vagaModel;
    private $candidateModel;
    private $matchingHistoryModel;

    public function __construct($pdo) {
        $this->vagaModel = new VagaModel($pdo);
        $this->candidateModel = new CandidateModel($pdo);
        $this->matchingHistoryModel = new MatchingHistoryModel($pdo);
    }

    // Endpoint: GET /vagas/matching/{id_vaga}
    public function buscarMatchingParaVaga($id_vaga) {
        // Busca a vaga pelo ID
        $vaga = $this->vagaModel->getVagaById($id_vaga);
        if ($vaga) {
            // Busca todos os candidatos (exemplo simples de matching)
            $candidates = $this->candidateModel->getAllCandidates();
            echo json_encode(['vaga' => $vaga, 'candidates' => $candidates]);
        } else {
            echo json_encode(['error' => 'Vaga não encontrada.']);
        }
    }

    // Endpoint: POST /candidatos/{id}/match
    public function associateCandidateToVaga($id_candidato, $id_vaga) {
        $this->matchingHistoryModel->saveMatch($id_candidato, $id_vaga);
        echo json_encode(['message' => 'Candidato associado à vaga com sucesso.']);
    }

    // Endpoint: GET /match/history/{id_candidato}
    public function getCandidateMatchHistory($id_candidato) {
        $history = $this->matchingHistoryModel->getMatchHistoryByCandidate($id_candidato);
        if ($history) {
            echo json_encode(['history' => $history]);
        } else {
            echo json_encode(['error' => 'Nenhum histórico de match encontrado para este candidato.']);
        }
    }

    // Endpoint: DELETE /match/history/{id_candidato}
    public function deleteCandidateMatchHistory($id_candidato) {
        $this->matchingHistoryModel->deleteMatchByCandidate($id_candidato);
        echo json_encode(['message' => 'Histórico de match deletado com sucesso.']);
    }
}
