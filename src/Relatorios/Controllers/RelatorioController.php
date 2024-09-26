<?php
namespace App\Relatorios\Controllers;

use App\Relatorios\Models\RelatorioModel;

class RelatorioController {
    private $relatorioModel;

    public function __construct($pdo) {
        $this->relatorioModel = new RelatorioModel($pdo);
    }

    // Criar um novo relatório
    public function createRelatorio($data) {
        $nome = $data['nome'];
        $caminhoArquivo = $data['caminho_arquivo']; // Gerado ou fornecido
        if ($this->relatorioModel->createRelatorio($nome, $caminhoArquivo)) {
            echo json_encode(['message' => 'Relatório criado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao criar relatório.']);
        }
    }

    // Buscar um relatório por ID
    public function getRelatorioById($id) {
        $relatorio = $this->relatorioModel->getRelatorioById($id);
        if ($relatorio) {
            echo json_encode($relatorio);
        } else {
            echo json_encode(['error' => 'Relatório não encontrado.']);
        }
    }

    // Excluir um relatório por ID
    public function deleteRelatorioById($id) {
        if ($this->relatorioModel->deleteRelatorioById($id)) {
            echo json_encode(['message' => 'Relatório excluído com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao excluir relatório.']);
        }
    }
}
