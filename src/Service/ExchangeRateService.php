<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\CurrencyPairRepository;
use App\Repository\ExchangeRateRepository;
use DateTime;
use RuntimeException;

class ExchangeRateService
{
    public function __construct(
        protected ExchangeRateRepository $exchangeRateRepository,
        protected CurrencyPairRepository $currencyPairRepository
    ) {
    }

    public function getExchangeRatesInRange(
        string $baseCurrency,
        string $quoteCurrency,
        DateTime $startDate,
        DateTime $endDate
    ): array {
        $currencyPair = $this->currencyPairRepository->findOneBy([
            'baseCurrencyCode' => $baseCurrency,
            'quoteCurrencyCode' => $quoteCurrency,
        ]);

        if (!$currencyPair) {
            throw new RuntimeException('Invalid currency pair');
        }

        if ($startDate > $endDate) {
            throw new RuntimeException('Start date must be before end date');
        }

        return $this->exchangeRateRepository->findRatesInRange(
            $baseCurrency,
            $quoteCurrency,
            $startDate,
            $endDate
        );
    }
}
