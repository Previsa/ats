<?php

namespace App\TriagemCurriculos\Services;

class ValidacaoCurriculos {
    public function validarCurriculo($curriculo) {
        // Adicionar regras de validação, como formato e tamanho
        $allowedTypes = ['application/pdf'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($curriculo['type'], $allowedTypes)) {
            return ['error' => 'Formato inválido. Somente PDFs são permitidos.'];
        }

        if ($curriculo['size'] > $maxSize) {
            return ['error' => 'O arquivo é muito grande. Tamanho máximo permitido é 2MB.'];
        }

        return ['success' => true];
    }
}
