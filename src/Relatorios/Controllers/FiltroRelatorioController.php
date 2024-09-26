<?php
namespace App\Relatorios\Controllers;

use App\Relatorios\Models\FiltroRelatorioModel;

class FiltroRelatorioController {
    private $filtroRelatorioModel;

    public function __construct($pdo) {
        $this->filtroRelatorioModel = new FiltroRelatorioModel($pdo);
    }

    // Criar um novo filtro
    public function createFiltro($data) {
        $nome = $data['nome'];
        $configuracao = json_encode($data['configuracao']); // Armazenar como JSON
        if ($this->filtroRelatorioModel->createFiltro($nome, $configuracao)) {
            echo json_encode(['message' => 'Filtro criado com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao criar filtro.']);
        }
    }

    // Buscar todos os filtros
    public function getFiltros() {
        $filtros = $this->filtroRelatorioModel->getFiltros();
        echo json_encode($filtros);
    }

    // Excluir um filtro por ID
    public function deleteFiltroById($id) {
        if ($this->filtroRelatorioModel->deleteFiltroById($id)) {
            echo json_encode(['message' => 'Filtro excluído com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao excluir filtro.']);
        }
    }
}
