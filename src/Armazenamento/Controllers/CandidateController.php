<?php
namespace App\Armazenamento\Controllers;

use App\Armazenamento\Models\CandidateModel;

class CandidateController {
    private $candidateModel;

    public function __construct($pdo) {
        $this->candidateModel = new CandidateModel($pdo);
    }

    // Endpoint: POST /candidatos (Criar candidatos)
    public function createCandidate($data) {
        // Verifica se os dados foram enviados corretamente
        if (empty($data['nome']) || empty($data['localidade']) || empty($data['experiencia'])) {
            echo json_encode(['error' => 'Dados incompletos para criação do candidato.']);
            return;
        }

        $result = $this->candidateModel->createCandidate($data);
        if ($result) {
            echo json_encode(['message' => 'Candidato criado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao criar o candidato.']);
        }
    }

    // Endpoint: GET /candidatos/{id} (Buscar candidato por ID)
    public function getCandidateById($id) {
        $candidate = $this->candidateModel->getCandidateById($id);
        if ($candidate) {
            echo json_encode($candidate);
        } else {
            echo json_encode(['error' => 'Candidato não encontrado.']);
        }
    }

    // Endpoint: PUT /candidatos/{id} (Atualizar candidato por ID)
    public function updateCandidate($id, $data) {
        // Verifica se os dados para atualização foram enviados corretamente
        if (empty($data['nome']) || empty($data['localidade']) || empty($data['experiencia'])) {
            echo json_encode(['error' => 'Dados incompletos para atualização do candidato.']);
            return;
        }

        $result = $this->candidateModel->updateCandidate($id, $data);
        if ($result) {
            echo json_encode(['message' => 'Candidato atualizado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao atualizar o candidato.']);
        }
    }

    // Endpoint: DELETE /candidatos/{id} (Excluir candidato por ID)
    public function deleteCandidate($id) {
        $result = $this->candidateModel->deleteCandidate($id);
        if ($result) {
            echo json_encode(['message' => 'Candidato excluído com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao excluir o candidato.']);
        }
    }
}
