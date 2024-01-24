<?php declare(strict_types=1);

namespace App\Command;

use App\Service\ExchangeRateUpdaterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-exchange-rates',
    description: 'Updates the exchange rates from the Blockchain API.',
    hidden: false
)]
class UpdateExchangeRatesCommand extends Command
{

    public function __construct(protected ExchangeRateUpdaterService $exchangeRateUpdater)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->exchangeRateUpdater->updateExchangeRates();
        $output->writeln('Exchange rates updated successfully.');

        return Command::SUCCESS;
    }
}
