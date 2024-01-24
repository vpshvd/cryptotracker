<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\CurrencyPairRepository;
use App\Repository\ExchangeRateRepository;
use App\Service\ExchangeRateService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/exchange-rates', name: 'api_exchange_rates')]
class ExchangeRateController extends AbstractController
{

    public function __construct(
        protected SerializerInterface $serializer,
        protected ExchangeRateRepository $exchangeRateRepository,
        protected CurrencyPairRepository $currencyPairRepository,
        protected EntityManagerInterface $em
    ) {
    }

    #[Route('/{baseCurrency}/{quoteCurrency}', name: 'api_exchange_rates_range', methods: 'GET')]
    public function getExchangeRates(
        string $baseCurrency,
        string $quoteCurrency,
        Request $request,
        ExchangeRateService $exchangeRateService
    ): JsonResponse {
        $startDate = $request->query->get('start', 'now - 1 day');
        $endDate = $request->query->get('end', 'now');

        try {
            $startDate = new DateTime($startDate);
            $endDate = new DateTime($endDate);
            $exchangeRates = $exchangeRateService->getExchangeRatesInRange(
                $baseCurrency,
                $quoteCurrency,
                $startDate,
                $endDate
            );
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            array_map(static function ($rate) {
                return [
                    'rate' => $rate->getRate(),
                    'timestamp' => $rate->getTimestamp()->format('Y-m-d H:i:s'),
                ];
            }, $exchangeRates),
        );
    }
}
