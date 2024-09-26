<?php

namespace App\IntegracaoAPI\Services;

class SolidesAPI
{
    private $apiUrl;
    private $apiToken;

    public function __construct() {
        // Obter URL e Token da API usando getenv()
        $this->apiUrl = getenv('SOLIDES_API_URL') ?: 'https://api.solides.com/v1';
        $this->apiToken = getenv('SOLIDES_API_TOKEN') ?: 'seu_token_aqui';
    }

    // Método para testar a conexão com a API
    public static function connectToSolidesAPI()
    {
        $apiUrl = getenv('SOLIDES_API_URL') ?: 'https://api.solides.com/v1';
        $apiToken = getenv('SOLIDES_API_TOKEN') ?: 'seu_token_aqui';

        // Inicializa cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl . '/me'); // Endpoint para testar a conexão
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $apiToken,
            'Content-Type: application/json'
        ]);

        // Executa a requisição e obtém a resposta
        $response = curl_exec($ch);

        // Verifica se houve erros
        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
            return false;
        }

        curl_close($ch);

        // Decodifica a resposta JSON
        $data = json_decode($response, true);

        if (isset($data['error'])) {
            echo 'Erro da API: ' . $data['error']['message'];
            return false;
        } else {
            echo 'Conexão realizada com sucesso!';
            print_r($data);
            return true;
        }
    }

    // Método para importar vagas da API Solides
    public function importVagas() {
        // Inicializa cURL para o endpoint de vagas
        $ch = curl_init($this->apiUrl . '/vagas');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);

        // Verifica se houve erros
        if (curl_errno($ch)) {
            curl_close($ch);
            throw new \Exception('Erro na requisição: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decodifica a resposta JSON
        $data = json_decode($response, true);

        // Verifica se há algum erro na resposta
        if (isset($data['error'])) {
            throw new \Exception('Erro da API: ' . $data['error']['message']);
        }

        // Retorna as vagas (ou array vazio se não houver vagas)
        return $data['vagas'] ?? [];
    }

    // Método para importar candidatos da API Solides
    public function importCandidatos() {
        // Inicializa cURL para o endpoint de candidatos
        $ch = curl_init($this->apiUrl . '/candidatos');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);

        // Verifica se houve erros
        if (curl_errno($ch)) {
            curl_close($ch);
            throw new \Exception('Erro na requisição: ' . curl_error($ch));
        }

        curl_close($ch);

        // Decodifica a resposta JSON
        $data = json_decode($response, true);

        // Verifica se há algum erro na resposta
        if (isset($data['error'])) {
            throw new \Exception('Erro da API: ' . $data['error']['message']);
        }

        // Retorna os candidatos (ou array vazio se não houver candidatos)
        return $data['candidatos'] ?? [];
    }
}
