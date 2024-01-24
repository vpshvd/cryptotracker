<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'currency_pairs')]
class CurrencyPair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 3, nullable: false)]
    private string $baseCurrencyCode;

    #[ORM\Column(type: 'string', length: 3, nullable: false)]
    private string $quoteCurrencyCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseCurrencyCode(): string
    {
        return $this->baseCurrencyCode;
    }

    public function getQuoteCurrencyCode(): string
    {
        return $this->quoteCurrencyCode;
    }

    public function setBaseCurrencyCode(string $baseCurrencyCode): CurrencyPair
    {
        $this->baseCurrencyCode = $baseCurrencyCode;

        return $this;
    }

    public function setQuoteCurrencyCode(string $quoteCurrencyCode): CurrencyPair
    {
        $this->quoteCurrencyCode = $quoteCurrencyCode;

        return $this;
    }
}
