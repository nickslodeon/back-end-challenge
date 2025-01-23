<?php

namespace App;

use App\Validacao;
use App\Respostas;

class Rota
{
    // Metodo principal que lida com a requisicao
    public static function tratarRequisicao(string $requestUri, string $baseUri)
    {
        // Se a URL for apenas "/", devolve um erro
        if ($requestUri === '/') {
            Respostas::erro('URL invalida. Use /exchange/{quantidade}/{origem}/{destino}/{taxa}', 400);
        }

        // Se a URL for apenas a base, devolve outro erro
        if ($requestUri === $baseUri) {
            Respostas::erro('URL invalida. Use /exchange/{quantidade}/{origem}/{destino}/{taxa}', 400);
        }

        // Confere se a URL comeca com a base e processa os parametros
        if (strpos($requestUri, $baseUri) === 0) {
            // Divide os parametros que vem depois da base
            $parametros = explode('/', str_replace($baseUri, '', $requestUri));

            // Confere se a quantidade de parametros esta correta
            if (count($parametros) < 4) {
                Respostas::erro('URL incompleta. Use /exchange/{quantidade}/{origem}/{destino}/{taxa}', 400);
            }

            // Pega os valores dos parametros
            $quantidade = $parametros[0];
            $moedaOrigem = $parametros[1];
            $moedaDestino = $parametros[2];
            $taxa = $parametros[3];

            // Valida os parametros usando a classe Validacao
            Validacao::validarMoeda($moedaOrigem, 'Moeda de origem invalida. Use BRL, USD ou EUR.');
            Validacao::validarMoeda($moedaDestino, 'Moeda de destino invalida. Use BRL, USD ou EUR.');
            Validacao::validarNumeroPositivo($quantidade, 'Quantidade invalida. Deve ser um numero positivo.');
            Validacao::validarNumeroPositivo($taxa, 'Taxa de conversao invalida. Deve ser um numero positivo.');

            // Calcula o valor convertido
            $valorConvertido = $quantidade * $taxa;

            // Prepara a resposta
            $resposta = [
                'valorConvertido' => $valorConvertido,
                'simboloMoeda' => ($moedaDestino === 'USD' ? '$' : ($moedaDestino === 'EUR' ? '€' : 'R$'))
            ];

            // Envia a resposta
            Respostas::sucesso($resposta);
        } else {
            // Se nao for nenhuma rota valida, retorna erro 404
            Respostas::erro('Rota nao encontrada. Use http://localhost:8000/exchange/{quantidade}/{origem}/{destino}/{taxa}', 404);
        }
    }
}