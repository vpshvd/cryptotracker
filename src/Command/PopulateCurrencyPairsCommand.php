<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\CurrencyPair;
use App\Repository\CurrencyPairRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:populate-currency-pairs',
    description: 'Populates the CurrencyPair table with predefined currency pairs.',
    hidden: false
)]
class PopulateCurrencyPairsCommand extends Command
{

    public function __construct(
        protected EntityManagerInterface $em,
        protected CurrencyPairRepository $currencyPairRepository,
        protected string $baseCurrencyCode,
        protected array $quoteCurrencyCodes
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->quoteCurrencyCodes as $quoteCurrencyCode) {
            $existingPair = $this->currencyPairRepository->findOneBy([
                'baseCurrencyCode' => $this->baseCurrencyCode,
                'quoteCurrencyCode' => $quoteCurrencyCode,
            ]);

            if (!$existingPair) {
                $currencyPair = new CurrencyPair();
                $currencyPair
                    ->setBaseCurrencyCode($this->baseCurrencyCode)
                    ->setQuoteCurrencyCode($quoteCurrencyCode);

                $this->em->persist($currencyPair);
            } else {
                $output->writeln("Currency pair $this->baseCurrencyCode/$quoteCurrencyCode already exists.");
            }
        }

        $this->em->flush();
        $output->writeln('Currency pairs populated successfully.');

        return Command::SUCCESS;
    }
}
