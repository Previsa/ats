<?php

namespace App\IntegracaoAPI\Controllers;

use App\IntegracaoAPI\Services\SolidesAPI;
use App\IntegracaoAPI\Models\SolidesModel;

class SolidesController {
    private $solidesAPI;
    private $solidesModel;

    public function __construct($pdo) {
        // Inicializa os serviços da API do Solides e o modelo de interação com o banco de dados
        $this->solidesAPI = new SolidesAPI();
        $this->solidesModel = new SolidesModel($pdo);
    }

    // Testar conexão com a API Solides
    public function testConnection() {
        $status = $this->solidesAPI->connectToSolidesAPI();

        if ($status) {
            echo json_encode(['status' => 'Conexão bem-sucedida']);
        } else {
            echo json_encode(['status' => 'Falha na conexão com a API do Solides']);
        }
    }

    // Método para verificar o status da conexão com a API (conforme solicitado)
    public function status() {
        // Chamando o método de teste de conexão para verificar o status
        $this->testConnection();
    }

    // Importar vagas da API do Solides e salvar no banco de dados
    public function importarVagas() {
        try {
            $vagas = $this->solidesAPI->importVagas();

            if ($vagas && is_array($vagas)) {
                foreach ($vagas as $vaga) {
                    $this->solidesModel->saveVaga($vaga);
                }
                echo json_encode(['message' => 'Vagas importadas com sucesso.', 'vagas_importadas' => count($vagas)]);
            } else {
                echo json_encode(['error' => 'Nenhuma vaga encontrada ou erro ao importar.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Importar candidatos da API do Solides e salvar no banco de dados
    public function importarCandidatos() {
        try {
            $candidatos = $this->solidesAPI->importCandidatos();

            if ($candidatos && is_array($candidatos)) {
                foreach ($candidatos as $candidato) {
                    $this->solidesModel->saveCandidato($candidato);
                }
                echo json_encode(['message' => 'Candidatos importados com sucesso.', 'candidatos_importados' => count($candidatos)]);
            } else {
                echo json_encode(['error' => 'Nenhum candidato encontrado ou erro ao importar.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Método para listar as vagas salvas no banco de dados
    public function listarVagas() {
        try {
            $vagas = $this->solidesModel->getVagas();

            if ($vagas && is_array($vagas)) {
                echo json_encode(['vagas' => $vagas]);
            } else {
                echo json_encode(['error' => 'Nenhuma vaga encontrada no banco de dados.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Método para listar os candidatos salvos no banco de dados
    public function listarCandidatos() {
        try {
            $candidatos = $this->solidesModel->getCandidatos();

            if ($candidatos && is_array($candidatos)) {
                echo json_encode(['candidatos' => $candidatos]);
            } else {
                echo json_encode(['error' => 'Nenhum candidato encontrado no banco de dados.']);
            }
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
