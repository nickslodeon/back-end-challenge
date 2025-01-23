<?php
namespace App;

class Respostas
{
    // Envia uma resposta de sucesso em formato JSON
    public static function sucesso(array $dados, int $codigoStatus = 200)
    {
        http_response_code($codigoStatus);
        header('Content-Type: application/json');
        echo json_encode($dados);
        exit;
    }

    // Envia uma resposta de erro em formato JSON
    public static function erro(string $mensagem, int $codigoStatus)
    {
        http_response_code($codigoStatus);
        header('Content-Type: application/json');
        echo json_encode(['erro' => $mensagem]);
        exit;
    }
}
