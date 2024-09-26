<?php

namespace App\IntegracaoAPI\Models;

class SolidesModel {
    private $db;
    private $apiUrl;
    private $apiToken;

    public function __construct($pdo) {
        // Usa a conexão $pdo injetada
        $this->db = $pdo;

        // Pega as configurações da API a partir do .env
        $this->apiUrl = getenv('SOLIDES_API_URL');
        $this->apiToken = getenv('SOLIDES_API_TOKEN');
    }

    // Verificar a conexão com a API
    public function checkAPIConnection() {
        $ch = curl_init($this->apiUrl . '/me');  // Endpoint para verificar status
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ? json_decode($response, true) : false;
    }

    // Pega as vagas da API Solides
    public function getVagasFromAPI() {
        $ch = curl_init($this->apiUrl . '/vagas');  // Endpoint de vagas
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ? json_decode($response, true) : [];
    }

    // Salva uma vaga no banco de dados
    public function saveVaga($vaga) {
        $stmt = $this->db->prepare('
            INSERT INTO vagas (titulo, descricao, localidade, requisitos, salario, status, data_criacao, tipo_trabalho, setor, empresa, data_fechamento)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $vaga['titulo'], 
            $vaga['descricao'], 
            $vaga['localidade'], 
            $vaga['requisitos'], 
            $vaga['salario'],
            $vaga['status'], 
            $vaga['data_criacao'], 
            $vaga['tipo_trabalho'], 
            $vaga['setor'], 
            $vaga['empresa'], 
            $vaga['data_fechamento']
        ]);
    }

    // Método para buscar as vagas salvas no banco de dados
    public function getVagas() {
        // Busca todas as vagas da tabela vagas
        $stmt = $this->db->prepare('SELECT * FROM vagas');
        $stmt->execute();
        return $stmt->fetchAll();  // Retorna todas as vagas como um array
    }

    // Pega os candidatos da API Solides
    public function getCandidatosFromAPI() {
        $ch = curl_init($this->apiUrl . '/candidatos');  // Endpoint de candidatos
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ? json_decode($response, true) : [];
    }

    // Salva um candidato no banco de dados
    public function saveCandidato($candidato) {
        $stmt = $this->db->prepare('
            INSERT INTO candidatos (nome, email, telefone, experiencia, escolaridade, localidade, salario_pretendido, data_aplicacao)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $candidato['nome'], 
            $candidato['email'], 
            $candidato['telefone'], 
            $candidato['experiencia'], 
            $candidato['escolaridade'], 
            $candidato['localidade'], 
            $candidato['salario_pretendido'], 
            $candidato['data_aplicacao']
        ]);
    }

    // Método para buscar os candidatos salvos no banco de dados
    public function getCandidatos() {
        // Busca todos os candidatos da tabela candidatos
        $stmt = $this->db->prepare('SELECT * FROM candidatos');
        $stmt->execute();
        return $stmt->fetchAll();  // Retorna todos os candidatos como um array
    }
}
