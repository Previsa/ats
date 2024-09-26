<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

use App\IntegracaoAPI\Controllers\SolidesController;
use App\TriagemCurriculos\Controllers\CurriculoController;
use App\Armazenamento\Controllers\CandidateController;
use App\Matching\Controllers\CandidateSearchController;
use App\Matching\Controllers\MatchingController;
use App\Relatorios\Controllers\RelatorioController;
use App\Relatorios\Controllers\FiltroRelatorioController;
use App\TriagemCurriculos\Services\ValidacaoCurriculos;
use Dotenv\Dotenv;

// Carrega as variáveis de ambiente do .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Inicializa os controladores
$solidesController = new SolidesController($pdo);
$curriculoController = new CurriculoController($pdo);
$candidateController = new CandidateController($pdo);
$candidateSearchController = new CandidateSearchController($pdo);
$matchingController = new MatchingController($pdo);
$relatorioController = new RelatorioController($pdo);
$filtroRelatorioController = new FiltroRelatorioController($pdo);

// Obtém a URI solicitada e o método HTTP
$requestUri = $_SERVER['REQUEST_URI'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

// CRUD de Relatórios

// Criar relatório - POST /relatorios
if ($requestUri === '/relatorios' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $relatorioController->createRelatorio($data);
    exit;
}

// Buscar relatório por ID - GET /relatorios/{id}
if (preg_match('/\/relatorios\/(\d+)/', $requestUri, $matches) && $method === 'GET') {
    $id = $matches[1];
    $relatorioController->getRelatorioById($id);
    exit;
}

// Excluir relatório por ID - DELETE /relatorios/{id}
if (preg_match('/\/relatorios\/(\d+)/', $requestUri, $matches) && $method === 'DELETE') {
    $id = $matches[1];
    $relatorioController->deleteRelatorioById($id);
    exit;
}

// CRUD de Filtros de Relatórios

// Criar filtro - POST /relatorios/filtros
if ($requestUri === '/relatorios/filtros' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $filtroRelatorioController->createFiltro($data);
    exit;
}

// Buscar filtros - GET /relatorios/filtros
if ($requestUri === '/relatorios/filtros' && $method === 'GET') {
    $filtroRelatorioController->getFiltros();
    exit;
}

// Excluir filtro por ID - DELETE /relatorios/filtros/{id}
if (preg_match('/\/relatorios\/filtros\/(\d+)/', $requestUri, $matches) && $method === 'DELETE') {
    $id = $matches[1];
    $filtroRelatorioController->deleteFiltroById($id);
    exit;
}

// CRUD de candidatos

// Criar candidato - POST /candidatos
if ($requestUri === '/candidatos' && $method === 'POST') {
    $candidateData = json_decode(file_get_contents('php://input'), true);
    if ($candidateData) {
        $candidateController->createCandidate($candidateData); // Use CandidateController para CRUD
    } else {
        echo json_encode(['error' => 'Dados do candidato ausentes.']);
    }
    exit;
}

// Buscar candidato por ID - GET /candidatos/{id}
if (preg_match('/\/candidatos\/(\d+)/', $requestUri, $matches) && $method === 'GET') {
    $id = $matches[1];
    $candidateController->getCandidateById($id); // Use CandidateController
    exit;
}

// Atualizar candidato por ID - PUT /candidatos/{id}
if (preg_match('/\/candidatos\/(\d+)/', $requestUri, $matches) && $method === 'PUT') {
    $id = $matches[1];
    
    // Lê os dados da requisição PUT
    $candidateData = json_decode(file_get_contents('php://input'), true);
    
    if ($candidateData) {
        $candidateController->updateCandidate($id, $candidateData); // Use CandidateController
    } else {
        echo json_encode(['error' => 'Dados para atualização ausentes.']);
    }
    exit;
}

// Excluir candidato por ID - DELETE /candidatos/{id}
if (preg_match('/\/candidatos\/(\d+)/', $requestUri, $matches) && $method === 'DELETE') {
    $id = $matches[1];
    $candidateController->deleteCandidate($id); // Use CandidateController
    exit;
}

// Corrige a verificação da rota para `/curriculos/visualizar`
if (strpos($requestUri, '/curriculos/visualizar') === 0 && $method === 'GET') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $curriculoController->visualizarCurriculo($id);
    } else {
        echo json_encode(['error' => 'ID do currículo ausente.']);
    }
    exit;
}

// Corrige a verificação da rota para `/curriculos/deletar`
if (strpos($requestUri, '/curriculos/deletar') === 0 && $method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $curriculoController->deletarCurriculo($id);
    } else {
        echo json_encode(['error' => 'ID do currículo ausente.']);
    }
    exit;
}

// Verifica se a URL é `/triagem/validacao` para validação de currículos
if ($requestUri === '/triagem/validacao' && $method === 'POST') {
    $validacao = new ValidacaoCurriculos();
    $curriculo = $_FILES['curriculo'] ?? null;
    if ($curriculo) {
        $resultado = $validacao->validarCurriculo($curriculo);
        echo json_encode($resultado);
    } else {
        echo json_encode(['error' => 'Nenhum currículo enviado para validação.']);
    }
    exit;
}

// Rota para fazer upload de currículos
if ($requestUri === '/curriculos/upload' && $method === 'POST') {
    $idCandidato = $_POST['id_candidato'] ?? null;
    if ($idCandidato) {
        $curriculoController->uploadCurriculo($idCandidato);
    } else {
        echo json_encode(['error' => 'ID do candidato ausente.']);
    }
    exit;
}

// Verifica se a URL é /vagas/importar
if ($requestUri === '/vagas/importar' && $method === 'POST') {
    $solidesController->importarVagas();
    exit;
}

// Verifica se a URL é /vagas/listar
if ($requestUri === '/vagas/listar' && $method === 'GET') {
    $solidesController->listarVagas();
    exit;
}

// Verifica se a URL é /candidatos/importar
if ($requestUri === '/candidatos/importar' && $method === 'POST') {
    $solidesController->importarCandidatos();
    exit;
}

// Verifica se a URL é /candidatos/listar
if ($requestUri === '/candidatos/listar' && $method === 'GET') {
    $solidesController->listarCandidatos();
    exit;
}

// Verifica se a URL é /status
if ($requestUri === '/status' && $method === 'GET') {
    $solidesController->status();
    exit;
}

// Verifica se a URL corresponde a `/candidatos/busca`
if (strpos($requestUri, '/candidatos/busca') === 0 && $method === 'GET') {
    $palavrasChave = $_GET['palavras-chave'] ?? null;
    $localizacao = $_GET['localizacao'] ?? null;

    if ($palavrasChave && $localizacao) {
        // Chamando o método de busca de candidatos
        $candidateSearchController->buscarCandidatos($palavrasChave, $localizacao);
    } else {
        echo json_encode(['error' => 'Palavras-chave ou localização ausentes.']);
    }
    exit;
}

// Verifica se há um padrão na URL (exemplo: /candidatos/{id}/match)
if (preg_match('/\/candidatos\/(\d+)\/match/', $requestUri, $matches)) {
    $id_candidato = $matches[1];
    $id_vaga = $_GET['id_vaga'] ?? null;

    if ($method === 'POST') {
        if ($id_vaga) {
            $matchingController->associateCandidateToVaga($id_candidato, $id_vaga);
        } else {
            echo json_encode(['error' => 'ID da vaga não fornecido.']);
        }
    }
    exit;
}

// Verifica se a URL corresponde a `/match/history`
if (preg_match('/\/match\/history/', $requestUri)) {
    $id_candidato = $_GET['id_candidato'] ?? null;

    if ($method === 'GET' && $id_candidato) {
        // Chama o método para buscar o histórico de matching
        $matchingController->getCandidateMatchHistory($id_candidato);
    } elseif ($method === 'DELETE' && $id_candidato) {
        // Chama o método para deletar o histórico de matching
        $matchingController->deleteCandidateMatchHistory($id_candidato);
    } else {
        echo json_encode(['error' => 'ID do candidato ausente.']);
    }
    exit;
}

// Verifica se a URL é /vagas/matching/{id}
if (preg_match('/\/vagas\/matching\/(\d+)/', $requestUri, $matches) && $method === 'GET') {
    $idVaga = $matches[1];

    // Chama o método para fazer o matching de candidatos para essa vaga
    $matchingController->buscarMatchingParaVaga($idVaga);
    exit;
}

// Se a rota não foi reconhecida, exibe um erro genérico
echo json_encode(['error' => 'Rota não encontrada ou método inválido']);
