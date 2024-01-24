<?php declare(strict_types=1);

namespace App\Client;

use RuntimeException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class BlockChainApiClient
{
    public function __construct(
        protected httpClientInterface $client,
        protected CacheInterface $cache,
        protected string $apiUrl
    ) {
    }

    public function getCurrentExchangeRates(): array
    {
        try {
            return $this->cache->get('blockchain_api_current_exchange_rates', function (ItemInterface $item) {
                $item->expiresAfter(3600);

                $response = $this->client->request('GET', $this->apiUrl);

                if ($response->getStatusCode() !== 200) {
                    throw new RuntimeException('Error fetching data from Blockchain.info');
                }

                $data = $response->toArray();

                return $this->processData($data);
            });
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to retrieve exchange rates: '.$e->getMessage());
        }
    }

    private function processData(array $data): array
    {
        $processedData = [];

        foreach ($data as $currencyCode => $rates) {
            $processedData[$currencyCode] = $rates['last'];
        }

        return $processedData;
    }
}
