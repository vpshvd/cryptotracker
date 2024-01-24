<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExchangeRate;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExchangeRateRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRate::class);
    }

    public function findRatesInRange(
        string $baseCurrency,
        string $quoteCurrency,
        DateTime $startDate,
        DateTime $endDate
    ): array {
        return $this->createQueryBuilder('er')
            ->innerJoin('er.currencyPair', 'cp')
            ->where('cp.baseCurrencyCode = :baseCurrency')
            ->andWhere('cp.quoteCurrencyCode = :quoteCurrency')
            ->andWhere('er.timestamp BETWEEN :startDate AND :endDate')
            ->setParameter('baseCurrency', $baseCurrency)
            ->setParameter('quoteCurrency', $quoteCurrency)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }
}
