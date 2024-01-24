<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\ExchangeRate;
use App\Entity\CurrencyPair;
use App\Repository\CurrencyPairRepository;
use App\Repository\ExchangeRateRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Client\BlockChainApiClient;

class ExchangeRateUpdaterService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected BlockChainApiClient $apiClient,
        protected string $baseCurrencyCode,
        protected CurrencyPairRepository $currencyPairRepository,
        protected ExchangeRateRepository $exchangeRateRepository
    ) {
    }

    public function updateExchangeRates(): void
    {
        $currentHour = (new DateTime())->setTime((int)date('G'), 0);

        $currentExchangeRates = $this->apiClient->getCurrentExchangeRates();

        foreach ($currentExchangeRates as $quoteCurrency => $rate) {
            $currencyPair = $this->currencyPairRepository->findOneBy([
                'baseCurrencyCode' => $this->baseCurrencyCode,
                'quoteCurrencyCode' => $quoteCurrency,
            ]);

            if (!$currencyPair) {
                continue;
            }

            $existingRate = $this->exchangeRateRepository->findOneBy([
                'currencyPair' => $currencyPair,
                'timestamp' => $currentHour,
            ]);

            if (!$existingRate) {
                $exchangeRate = new ExchangeRate();
                $exchangeRate
                    ->setCurrencyPair($currencyPair)
                    ->setRate($rate)
                    ->setTimestamp($currentHour);

                $this->entityManager->persist($exchangeRate);
            }
        }

        $this->entityManager->flush();
    }
}
