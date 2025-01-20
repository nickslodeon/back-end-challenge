<?php

namespace App\Controller;

class CurrencyController
{
    // Lista de moedas suportadas pela API
    private $supportedCurrencies = ['BRL', 'USD', 'EUR'];

    /**
     * Converte o valor de uma moeda para outra.
     *
     * @param float  $amount       Valor a ser convertido
     * @param string $fromCurrency Moeda de origem
     * @param string $toCurrency   Moeda de destino
     * @param float  $rate         Taxa de conversão
     *
     * @return array Resposta com o valor convertido e o simbolo da moeda
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency, float $rate): array
    {
         // Verifica se as moedas sao validas
        if (!$this->isSupportedExchange($fromCurrency) || !$this->isSupportedExchange($toCurrency)) {
            http_response_code(400);
            return [
                'error' => 'Moedas invalidas. Use BRL, USD ou EUR.'
            ];
        }

         // Calcula o valor convertido multiplicando o montante pela taxa
        $convertedValue = $amount * $rate;

         // Pega o simbolo da moeda de destino
        $CurrencySymbol = $this->getExchangeSymbol($toCurrency);

        // Retorna o valor convertido e o simbolo da moeda
        return [
            'valorConvertido' => $convertedValue,
            'simboloMoeda' => $CurrencySymbol
        ];
    }

      /**
     * Verifica se a moeda fornecida é suportada para conversão.
     *
     * @param string $Currency Moeda a ser verificada (ex.: BRL, USD, EUR)
     *
     * @return bool Retorna true se a moeda for suportada, caso contrário, false
     */
    private function isSupportedExchange(string $Currency): bool
    {
        return in_array($Currency, $this->supportedCurrencies, true);
    }

     /**
     * Pega o simbolo da moeda fornecida.
     *
     * @param string $Currency Moeda da qual se deseja o simbolo (ex.: BRL, USD, EUR)
     *
     * @return string Retorna o simbolo da moeda ou uma string vazia se nao for encontrado
     */
    private function getExchangeSymbol(string $Currency): string
    {
        // Mapea as moedas suportadas para seus simbolos
        $symbols = [
            'BRL' => 'R$', // Real brasileiro
            'USD' => '$', // Dólar americano
            'EUR' => '€' // Euro
        ];

        // Retorna o simbolo da moeda ou vazio se a moeda nao estiver mapeada
        return $symbols[$Currency] ?? '';
    }
}
