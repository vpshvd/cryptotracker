<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'exchange_rates')]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CurrencyPair::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CurrencyPair $currencyPair;

    #[ORM\Column(type: 'float')]
    private float $rate;

    #[ORM\Column(type: 'datetime')]
    private DateTime $timestamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyPair(): CurrencyPair
    {
        return $this->currencyPair;
    }

    public function setCurrencyPair(CurrencyPair $currencyPair): ExchangeRate
    {
        $this->currencyPair = $currencyPair;

        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): ExchangeRate
    {
        $this->rate = $rate;

        return $this;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTime $timestamp): ExchangeRate
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
