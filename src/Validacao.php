<?php
namespace App;

class Validacao
{
    // Lista de moedas validas
    private static $moedasValidas = ['BRL', 'USD', 'EUR'];

    // Valida se a moeda e valida
    public static function validarMoeda(string $moeda, string $mensagemErro)
    {
        if (!in_array($moeda, self::$moedasValidas, true)) {
            Respostas::erro($mensagemErro, 400);
        }
    }

    // Valida se o numero e positivo
    public static function validarNumeroPositivo($valor, string $mensagemErro)
    {
        if (!is_numeric($valor) || $valor <= 0) {
            Respostas::erro($mensagemErro, 400);
        }
    }
}

