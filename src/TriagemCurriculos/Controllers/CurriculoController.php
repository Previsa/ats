<?php

namespace App\TriagemCurriculos\Controllers;

use App\TriagemCurriculos\Models\CurriculoModel;

class CurriculoController {
    private $curriculoModel;

    // Inicializa o controlador com a conexão PDO
    public function __construct($pdo) {
        $this->curriculoModel = new CurriculoModel($pdo); // Certifique-se de passar o $pdo corretamente
    }

    // Método para upload de currículos (já implementado)
    public function uploadCurriculo($idCandidato) {
        if (isset($_FILES['curriculo']) && $_FILES['curriculo']['error'] == 0) {
            $uploadDir = __DIR__ . '/../../../Uploads/';

            // Verifica se o diretório de uploads existe, senão, cria o diretório
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . basename($_FILES['curriculo']['name']);

            if (move_uploaded_file($_FILES['curriculo']['tmp_name'], $filePath)) {
                // Registrar o currículo no banco de dados
                $this->curriculoModel->saveCurriculo($_FILES['curriculo']['name'], $idCandidato);

                echo json_encode(['message' => 'Upload realizado com sucesso.']);
            } else {
                echo json_encode(['error' => 'Erro ao fazer upload.']);
            }
        } else {
            echo json_encode(['error' => 'Arquivo inválido ou não encontrado.']);
        }
    }

    // Método para atualizar currículos
    public function atualizarCurriculo($idCandidato) {
        if (isset($_FILES['curriculo']) && $_FILES['curriculo']['error'] == 0) {
            $uploadDir = __DIR__ . '/../../../Uploads/';

            // Verifica se o diretório de uploads existe, senão, cria o diretório
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filePath = $uploadDir . basename($_FILES['curriculo']['name']);

            if (move_uploaded_file($_FILES['curriculo']['tmp_name'], $filePath)) {
                // Atualiza o currículo no banco de dados
                $this->curriculoModel->updateCurriculo($_FILES['curriculo']['name'], $idCandidato);
                echo json_encode(['message' => 'Currículo atualizado com sucesso.']);
            } else {
                echo json_encode(['error' => 'Erro ao atualizar o currículo.']);
            }
        } else {
            echo json_encode(['error' => 'Arquivo inválido ou não encontrado.']);
        }
    }

    // Método para deletar currículos
    public function deletarCurriculo($id) {
        // Busca o currículo no banco de dados pelo ID
        $curriculo = $this->curriculoModel->getCurriculoById($id);

        if ($curriculo) {
            $filePath = __DIR__ . '/../../../Uploads/' . $curriculo['nome_arquivo'];

            // Verifica se o arquivo existe e tenta removê-lo
            if (file_exists($filePath)) {
                unlink($filePath);  // Remove o arquivo do diretório
            }

            // Remove o registro do currículo do banco de dados
            $this->curriculoModel->deleteCurriculo($id);

            echo json_encode(['message' => 'Currículo deletado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Currículo não encontrado.']);
        }
    }

    // Método para visualizar currículos
    public function visualizarCurriculo($id) {
        // Usa o curriculoModel para buscar o currículo pelo ID
        $curriculo = $this->curriculoModel->getCurriculoById($id);

        if ($curriculo) {
            $filePath = __DIR__ . '/../../../Uploads/' . $curriculo['nome_arquivo'];
            
            // Verifica se o arquivo existe no diretório
            if (file_exists($filePath)) {
                // Configura o cabeçalho para download do arquivo
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $curriculo['nome_arquivo'] . '"');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            } else {
                echo json_encode(['error' => 'Arquivo não encontrado.']);
            }
        } else {
            echo json_encode(['error' => 'Currículo não encontrado.']);
        }
    }

    // Método para validar currículos (já implementado)
    public function validarCurriculo() {
        if (isset($_FILES['curriculo']) && $_FILES['curriculo']['error'] == 0) {
            $fileType = mime_content_type($_FILES['curriculo']['tmp_name']);
            $fileSize = $_FILES['curriculo']['size'];

            if ($fileType === 'application/pdf') {
                if ($fileSize <= 2 * 1024 * 1024) {
                    echo json_encode(['message' => 'Currículo válido.']);
                } else {
                    echo json_encode(['error' => 'O arquivo excede o tamanho máximo permitido de 2MB.']);
                }
            } else {
                echo json_encode(['error' => 'Formato inválido. Somente arquivos PDF são permitidos.']);
            }
        } else {
            echo json_encode(['error' => 'Erro ao enviar o arquivo.']);
        }
    }
}
