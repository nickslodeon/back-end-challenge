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

// Carrega as classes automaticamente
require __DIR__ . '/vendor/autoload.php';

use App\Rota;

// Captura a URL requisitada e define a base
$requestUri = $_SERVER['REQUEST_URI'];
$baseUri = '/exchange/';

// Passa a bola para a classe Rota lidar com a requisiçao
Rota::tratarRequisicao($requestUri, $baseUri);