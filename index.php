<?php
/**
 * Back-end Challenge.
 *
 * PHP version 7.2
 *
 * Este será o arquivo chamado na execução dos testes automátizados.
 *
 * @category Challenge
 * @package  Back-end
 * @author   Nicole Santos Silveira <silveiranicole.nss@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/apiki/back-end-challenge
 */
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Controller\CurrencyController;

// roteamento
$requestUri = $_SERVER['REQUEST_URI'];
$baseUri = '/exchange/';

// Verificar rota raiz invalida "apenas /"
if ($requestUri === '/') {
     // Retorna erro caso a URL seja apenas a do servidor
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'URL invalida. Use /exchange/{amount}/{from}/{to}/{rate}'
    ]);
    exit;
}

// rota base "/exchange"
if ($requestUri === $baseUri) {
     // Retorna erro caso a URL seja apenas /exchange sem o restante
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'URL invalida. Use /exchange/{amount}/{from}/{to}/{rate}'
    ]);
    exit;
}

// Caso o caminho seja "/exchange/{amount}/{from}/{to}/{rate}"
if (strpos($requestUri, $baseUri) === 0) {
     // Extrai o restante da URL
    $params = explode('/', str_replace($baseUri, '', $requestUri));

     // Verifica se tem a quantidade correta 
    if (count($params) < 4) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'URL invalido. Use /exchange/{amount}/{from}/{to}/{rate}'
        ]);
        exit;
    }

    // Coloca valores das variaveis correspondentes
    $amount = $params[0];
    $fromCurrency = $params[1] ?? '';
    $toCurrency = $params[2] ?? '';
    $rate = $params[3] ?? '';

    // Validar moedas e valores
    $validCurrencies = ['BRL', 'USD', 'EUR'];

    if (!in_array($fromCurrency, $validCurrencies, true)) {
        // Retorna erro caso a moeda de origem nao seja suportada
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Moeda de origem invalida. Use BRL, USD ou EUR.'
        ]);
        exit;
    }

    if (!in_array($toCurrency, $validCurrencies, true)) {
        // Retorna erro caso a moeda de destino nao seja suportada
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Moeda de destino invalida. Use BRL, USD ou EUR.'
        ]);
        exit;
    }

    if (!is_numeric($amount) || $amount <= 0) {
        // Retorna erro caso o valor a ser convertido nao seja valido
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Valor invalido. Deve ser um numero positivo.'
        ]);
        exit;
    }

    if (!is_numeric($rate) || $rate <= 0) {
        // Retorna erro caso a taxa de conversao nao seja valida
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Taxa de conversao invalida. Deve ser um numero positivo.'
        ]);
        exit;
    }

    // controlador responsável pela conversão de moedas
    $controller = new CurrencyController();
    try {
        // faz a conversão e envia a resposta em formato JSON
        $response = $controller->convert((float) $amount, $fromCurrency, $toCurrency, (float) $rate);
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode($response);
    } catch (Exception $e) {
        // Retorna erro generico para falhas 
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Erro interno no servidor: ' . $e->getMessage()
        ]);
    }
} else {
    // Retorna erro para a rota nao encontrada
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Rota nao encontrada.'
    ]);
}
