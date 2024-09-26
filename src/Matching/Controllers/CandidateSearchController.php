<?php
namespace App\Matching\Controllers;

use App\Armazenamento\Models\CandidateModel;

class CandidateSearchController {
    private $candidateModel;

    public function __construct($pdo) {
        $this->candidateModel = new CandidateModel($pdo);
    }

    // Endpoint: GET /candidatos/busca?palavras-chave={keywords}&localizacao={cidade}
    public function buscarCandidatos($palavrasChave, $localizacao) {
        // Usa o model para buscar candidatos com base nas palavras-chave e localização
        $candidates = $this->candidateModel->searchCandidates($palavrasChave, $localizacao);

        // Retorna os candidatos ou um erro se não forem encontrados
        if ($candidates) {
            echo json_encode($candidates);
        } else {
            echo json_encode(['error' => 'Nenhum candidato encontrado.']);
        }
    }
}
