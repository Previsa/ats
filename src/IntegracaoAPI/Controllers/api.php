<?php

// Inclui o controlador do Solides
require_once 'src/IntegracaoAPI/Controllers/SolidesController.php';

// Crie uma instância do controlador SolidesController
$solidesController = new SolidesController();

// Verifique se o parâmetro 'route' está presente na URL
if (isset($_GET['route'])) {
    $route = $_GET['route'];
    
    // Defina as rotas possíveis
    switch ($route) {
        case 'status':
            echo $solidesController->testConnection();
            break;
        case 'importarVagas':
            echo $solidesController->importarVagas();
            break;
        case 'importarCandidatos':
            echo $solidesController->importarCandidatos();
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Rota não encontrada']);
            break;
    }
} else {
    echo json_encode(['error' => 'Nenhuma rota definida']);
}
